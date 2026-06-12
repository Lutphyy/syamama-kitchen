@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <h1>Dashboard</h1>
    <span class="text-light">Selamat datang, {{ auth()->user()->name }}!</span>
</div>

<!-- Stats Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon orange">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/></svg>
        </div>
        <div class="stat-info">
            <h3>{{ $totalProducts }}</h3>
            <p>Total Produk</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        </div>
        <div class="stat-info">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Pesanan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.94s4.18 1.36 4.18 3.85c0 1.89-1.44 2.98-3.12 3.19z"/></svg>
        </div>
        <div class="stat-info">
            <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            <p>Pendapatan Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:24px;height:24px;"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
        </div>
        <div class="stat-info">
            <h3>{{ $pendingOrders }}</h3>
            <p>Order Pending</p>
        </div>
    </div>
</div>

<!-- Period Filter -->
<div class="flex items-center gap-1 mb-3">
    <span class="text-sm text-light font-bold">Periode:</span>
    <a href="{{ route('admin.dashboard', ['period' => '7']) }}" class="btn btn-sm {{ $period == '7' ? 'btn-primary' : 'btn-outline' }}">7 Hari</a>
    <a href="{{ route('admin.dashboard', ['period' => '30']) }}" class="btn btn-sm {{ $period == '30' ? 'btn-primary' : 'btn-outline' }}">30 Hari</a>
    <a href="{{ route('admin.dashboard', ['period' => '90']) }}" class="btn btn-sm {{ $period == '90' ? 'btn-primary' : 'btn-outline' }}">3 Bulan</a>
    <a href="{{ route('admin.dashboard', ['period' => '365']) }}" class="btn btn-sm {{ $period == '365' ? 'btn-primary' : 'btn-outline' }}">1 Tahun</a>
</div>

<!-- Charts -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
    <div class="chart-container">
        <h3>Tren Penjualan</h3>
        <canvas id="salesChart" height="250"></canvas>
    </div>
    <div class="chart-container">
        <h3>Pendapatan</h3>
        <canvas id="revenueChart" height="250"></canvas>
    </div>
</div>

<!-- Recent Orders -->
<div class="chart-container">
    <div class="flex items-center justify-between mb-2">
        <h3>Pesanan Terbaru</h3>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('admin.orders.export') }}" class="btn btn-sm btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:4px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.orders.exportPdf') }}" class="btn btn-sm btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:4px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                Export PDF
            </a>
        </div>
    </div>
    <div class="table-wrap" style="box-shadow:none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><span class="font-bold">{{ $order->order_code }}</span></td>
                        <td>{{ $order->customer_name }}</td>
                        <td class="font-bold text-primary">{{ $order->formatted_total }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                @switch($order->status)
                                    @case('pending') Pending @break
                                    @case('processing') Proses @break
                                    @case('completed') Selesai @break
                                    @case('cancelled') Batal @break
                                @endswitch
                            </span>
                        </td>
                        <td class="text-sm text-light">{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-light" style="padding:2rem;">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
        datasets: [{
            label: 'Jumlah Order',
            data: {!! json_encode($salesData->pluck('count')) !!},
            borderColor: '#FA7302',
            backgroundColor: 'rgba(250, 115, 2, 0.08)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#FA7302',
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

const revCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueLabels) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($revenueTotals) !!},
            backgroundColor: 'rgba(4, 127, 213, 0.7)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection
