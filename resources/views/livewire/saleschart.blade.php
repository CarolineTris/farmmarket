<div
  x-data="salesChartWidget(
    @js($months),
    @js($revenue),
    @js($average),
    @js($viewMode),
    @js($category)
  )"
  x-init="init()"
  x-effect="updateData(@js($months), @js($revenue), @js($average), @js($viewMode), @js($category))"
>
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
          <option value="{{ $value }}" @selected($category === $value)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- Chart Canvas -->
  <canvas x-ref="canvas" height="100"></canvas>

  <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
    <div class="rounded border border-green-200 bg-green-50 px-3 py-2">
      <p class="text-xs text-green-700">Range Revenue</p>
      <p class="text-sm font-semibold text-green-900">UGX {{ number_format($rangeTotal, 2) }}</p>
    </div>
    <div class="rounded border border-blue-200 bg-blue-50 px-3 py-2">
      <p class="text-xs text-blue-700">Average Sale</p>
      <p class="text-sm font-semibold text-blue-900">UGX {{ number_format($rangeAverage, 2) }}</p>
    </div>
    <div class="rounded border border-amber-200 bg-amber-50 px-3 py-2">
      <p class="text-xs text-amber-700">Peak Period</p>
      <p class="text-sm font-semibold text-amber-900">
        @if($peakLabel)
          {{ $peakLabel }} (UGX {{ number_format($peakValue, 2) }})
        @else
          No completed sales in range
        @endif
      </p>
    </div>
  </div>

</div>

<script>
  function salesChartWidget(labels, revenue, average, viewMode, category) {
    return {
      chart: null,
      signature: '',
      init() {
        this.render(labels, revenue, average, viewMode, category);
      },
      updateData(nextLabels, nextRevenue, nextAverage, nextViewMode, nextCategory) {
        this.render(nextLabels, nextRevenue, nextAverage, nextViewMode, nextCategory);
      },
      render(nextLabels, nextRevenue, nextAverage, nextViewMode, nextCategory) {
        const nextSignature = JSON.stringify([nextLabels, nextRevenue, nextAverage, nextViewMode, nextCategory]);
        if (nextSignature === this.signature) {
          return;
        }
        this.signature = nextSignature;

        if (this.chart) {
          this.chart.destroy();
        }

        const ctx = this.$refs.canvas.getContext('2d');
        this.chart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: nextLabels,
            datasets: [
              {
                label: 'Total Revenue (UGX)',
                data: nextRevenue,
                backgroundColor: 'rgba(34,197,94,0.2)',
                borderColor: 'rgba(34,197,94,1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: 'rgba(34,197,94,1)',
              },
              {
                label: 'Average Sale (UGX)',
                data: nextAverage,
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
      }
    };
  }
</script>
