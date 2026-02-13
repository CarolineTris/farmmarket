<?php
namespace App\Livewire;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesChart extends Component
{
    public $months = [];
    public $revenue = [];
    public $average = [];

    public $viewMode = 'monthly'; // 'monthly' or 'weekly'
    public $category = 'all';     // 'all' or specific category
    public $categoryOptions = [];

    public function mount()
    {
        $this->categoryOptions = config('product_categories.list', []);
        $this->loadChartData();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->loadChartData();
    }

    public function setCategory($category)
    {
        $validCategories = array_keys($this->categoryOptions);
        $this->category = in_array($category, array_merge(['all'], $validCategories), true) ? $category : 'all';
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $farmerId = Auth::id();
        $query = Sale::where('farmer_id', $farmerId)
            ->where('status', 'completed');

        if ($this->category !== 'all') {
            $query->where('category', $this->category); // Adjust field name if needed
        }

        if ($this->viewMode === 'monthly') {
            $range = now()->subMonths(5)->startOfMonth();
            $groupFormat = '%M';
            $labelFormat = 'F';
            $labels = collect(range(0, 5))->map(fn($i) => now()->subMonths(5 - $i)->format('F'));
        } else {
            $range = now()->subWeeks(5)->startOfWeek();
            $groupFormat = '%W';
            $labelFormat = 'W';
            $labels = collect(range(0, 5))->map(fn($i) => 'Week ' . now()->subWeeks(5 - $i)->format('W'));
        }

        $sales = $query->where('created_at', '>=', $range)
            ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as label, SUM(amount) as total, AVG(amount) as average")
            ->groupByRaw("DATE_FORMAT(created_at, '{$groupFormat}')")
            ->orderByRaw("MIN(created_at)")
            ->get();

        $totals = $labels->map(fn() => 0);
        $averages = $labels->map(fn() => 0);

        foreach ($sales as $Sale) {
            $index = $labels->search($Sale->label);
            if ($index !== false) {
                $totals[$index] = $Sale->total;
                $averages[$index] = round($Sale->average);
            }
        }

        $this->months = $labels->toArray();
        $this->revenue = $totals->toArray();
        $this->average = $averages->toArray();
    }

    public function render()
    {
        return view('livewire.saleschart');
    }
}
