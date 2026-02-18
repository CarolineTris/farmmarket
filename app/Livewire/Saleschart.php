<?php
namespace App\Livewire;

use App\Models\OrderItem;
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
    public $rangeTotal = 0.0;
    public $rangeAverage = 0.0;
    public $peakLabel = null;
    public $peakValue = 0.0;

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
        $query = OrderItem::query()
            ->where('farmer_id', $farmerId)
            ->where('status', 'completed');

        if ($this->category !== 'all') {
            $query->whereHas('product', function ($q) {
                $q->where('category', $this->category);
            });
        }

        if ($this->viewMode === 'monthly') {
            $range = now()->subMonths(5)->startOfMonth();
            $keys = collect(range(0, 5))
                ->map(fn ($i) => now()->subMonths(5 - $i)->format('Y-m'));
            $labels = collect(range(0, 5))
                ->map(fn ($i) => now()->subMonths(5 - $i)->format('M Y'));

            $rows = $query
                ->where('updated_at', '>=', $range)
                ->selectRaw("DATE_FORMAT(updated_at, '%Y-%m') as bucket, SUM(subtotal) as total, AVG(subtotal) as average")
                ->groupBy('bucket')
                ->orderBy('bucket')
                ->get();
        } else {
            $range = now()->subWeeks(5)->startOfWeek();
            $keys = collect(range(0, 5))
                ->map(fn ($i) => now()->subWeeks(5 - $i)->format('o-W'));
            $labels = collect(range(0, 5))
                ->map(fn ($i) => 'Week ' . now()->subWeeks(5 - $i)->format('W'));

            $rows = $query
                ->where('updated_at', '>=', $range)
                ->selectRaw("DATE_FORMAT(updated_at, '%x-%v') as bucket, SUM(subtotal) as total, AVG(subtotal) as average")
                ->groupBy('bucket')
                ->orderBy('bucket')
                ->get();
        }

        $totals = $keys->map(fn () => 0.0);
        $averages = $keys->map(fn () => 0.0);

        foreach ($rows as $row) {
            $index = $keys->search($row->bucket);
            if ($index !== false) {
                $totals[$index] = (float) $row->total;
                $averages[$index] = round((float) $row->average, 2);
            }
        }

        $this->months = $labels->toArray();
        $this->revenue = $totals->toArray();
        $this->average = $averages->toArray();

        $this->rangeTotal = (float) collect($this->revenue)->sum();
        $this->rangeAverage = (float) (count($this->average) ? collect($this->average)->avg() : 0);

        $peakIndex = collect($this->revenue)->search(collect($this->revenue)->max());
        if ($peakIndex !== false && collect($this->revenue)->max() > 0) {
            $this->peakLabel = $this->months[$peakIndex] ?? null;
            $this->peakValue = (float) ($this->revenue[$peakIndex] ?? 0);
        } else {
            $this->peakLabel = null;
            $this->peakValue = 0.0;
        }
    }

    public function render()
    {
        return view('livewire.saleschart');
    }
}
