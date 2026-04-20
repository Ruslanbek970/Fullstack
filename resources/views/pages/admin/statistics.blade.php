@extends('layouts.app')

@section('title', __('site.stats_title'))

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Статистика</h1>
        <p class="small text-secondary mb-0">Данные из базы: лайки по категориям, статусы мемов, активность по будням, новые мемы за неделю.</p>
        <p class="small mt-2 mb-0">
            Всего мемов: <strong>{{ $polarExtra['memes_total'] }}</strong>
            · Лайков: <strong>{{ $polarExtra['likes_total'] }}</strong>
            · Дизлайков: <strong>{{ $polarExtra['dislikes_total'] }}</strong>
            · Комментариев за 7 дней: <strong>{{ $polarExtra['comments_week'] }}</strong>
        </p>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="p-3 border border-secondary rounded h-100 bg-black bg-opacity-25">
                <div class="fw-semibold mb-2">Bar — лайки по категориям</div>
                <canvas id="chartBar"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 border border-secondary rounded h-100 bg-black bg-opacity-25">
                <div class="fw-semibold mb-2">Pie — мемы по статусу</div>
                <canvas id="chartPie"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 border border-secondary rounded h-100 bg-black bg-opacity-25">
                <div class="fw-semibold mb-2">Polar — новые мемы по будням (текущая неделя)</div>
                <canvas id="chartPolar"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-3 border border-secondary rounded h-100 bg-black bg-opacity-25">
                <div class="fw-semibold mb-2">Line — новые мемы по дням (7 дней)</div>
                <canvas id="chartLine"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function () {
  const barLabels = @json($barLabels);
  const barData = @json($barData);
  const barDataDislikes = @json($barDataDislikes);
  const pieLabels = @json($pieLabels);
  const pieData = @json($pieData);
  const polarLabels = @json($weekdayLabels);
  const polarData = @json($polarData);
  const lineLabels = @json($lineLabels);
  const lineData = @json($lineData);

  new Chart(document.getElementById('chartBar'), {
    type: 'bar',
    data: { labels: barLabels, datasets: [
      { label: 'Лайки', data: barData, backgroundColor: 'rgba(255,193,7,.55)' },
      { label: 'Дизлайки', data: barDataDislikes, backgroundColor: 'rgba(108,117,125,.55)' },
    ] },
    options: { responsive: true, plugins: { legend: { labels: { color: '#ccc' } } }, scales: { x: { ticks: { color: '#aaa' } }, y: { ticks: { color: '#aaa' } } } }
  });

  new Chart(document.getElementById('chartPie'), {
    type: 'pie',
    data: { labels: pieLabels, datasets: [{ data: pieData, backgroundColor: ['#6c757d','#5ea8ff','#dc3545'] }] },
    options: { responsive: true, plugins: { legend: { labels: { color: '#ccc' } } } }
  });

  new Chart(document.getElementById('chartPolar'), {
    type: 'polarArea',
    data: { labels: polarLabels, datasets: [{ data: polarData, backgroundColor: ['#444','#555','#666','#777','#888'] }] },
    options: { responsive: true, plugins: { legend: { labels: { color: '#ccc' } } } }
  });

  new Chart(document.getElementById('chartLine'), {
    type: 'line',
    data: { labels: lineLabels, datasets: [{ label: 'Создано мемов', data: lineData, borderColor: '#5ea8ff', backgroundColor: 'rgba(94,168,255,.2)', tension: 0.3, fill: true }] },
    options: { responsive: true, plugins: { legend: { labels: { color: '#ccc' } } }, scales: { x: { ticks: { color: '#aaa' } }, y: { ticks: { color: '#aaa' }, beginAtZero: true } } }
  });
});
</script>
@endpush
