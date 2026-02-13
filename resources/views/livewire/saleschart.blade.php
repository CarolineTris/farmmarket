<div>
  <!-- Filter Controls -->
  <div class="mb-4 flex flex-wrap justify-between items-center gap-4">
    <!-- View Mode Toggle -->
    <div class="space-x-2">
      <button wire:click="setViewMode('monthly')"
        class="px-3 py-1 rounded text-sm font-medium transition
          {{ $viewMode === 'monthly' ? 'bg-green-600 text-white' : 'bg-white border border-green-600 text-green-700 hover:bg-green-100' }}">
        Monthly
      </button>
      <button wire:click="setViewMode('weekly')"
        class="px-3 py-1 rounded text-sm font-medium transition
          {{ $viewMode === 'weekly' ? 'bg-green-600 text-white' : 'bg-white border border-green-600 text-green-700 hover:bg-green-100' }}">
        Weekly
      </button>
    </div>

    <!-- Category Filter -->
    <div>
      <select wire:change="setCategory($event.target.value)"
        class="px-3 py-1 rounded border border-green-600 text-sm text-green-800 bg-white">
        <option value="all">All Categories</option>
        @foreach($categoryOptions as $value => $label)
          <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- Chart Canvas -->
  <canvas id="salesChart" height="100"></canvas>

  <!-- Chart.js Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const ctx = document.getElementById('salesChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: @json($months),
          datasets: [
            {
              label: 'Total Revenue (UGX)',
              data: @json($revenue),
              backgroundColor: 'rgba(34,197,94,0.2)',
              borderColor: 'rgba(34,197,94,1)',
              borderWidth: 2,
              fill: true,
              tension: 0.3,
              pointBackgroundColor: 'rgba(34,197,94,1)',
            },
            {
              label: 'Average Sale (UGX)',
              data: @json($average),
              backgroundColor: 'rgba(59,130,246,0.2)',
              borderColor: 'rgba(59,130,246,1)',
              borderWidth: 2,
              fill: false,
              tension: 0.3,
              pointBackgroundColor: 'rgba(59,130,246,1)',
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    });
  </script>
</div>
