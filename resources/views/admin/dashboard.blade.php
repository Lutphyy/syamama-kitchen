@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <h1>📊 Dashboard</h1>
    <span class="text-light">Selamat datang, {{ auth()->user()->name }}! 👋</span>
</div>

<!-- Stats Cards -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon orange">📦</div>
        <div class="stat-info">
            <h3>{{ $totalProducts }}</h3>
            <p>Total Produk</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow">🧾</div>
        <div class="stat-info">
            <h3>{{ $totalOrders }}</h3>
            <p>Total Pesanan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">💰</div>
        <div class="stat-info">
            <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            <p>Pendapatan Hari Ini</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">⏳</div>
        <div class="stat-info">
            <h3>{{ $pendingOrders }}</h3>
            <p>Order Pending</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
    <div class="chart-container">
        <h3>📈 Tren Penjualan (30 Hari)</h3>
        <canvas id="salesChart" height="250"></canvas>
    </div>
    <div class="chart-container">
        <h3>💰 Pendapatan Bulanan</h3>
        <canvas id="revenueChart" height="250"></canvas>
    </div>
</div>

<!-- Recent Orders -->
<div class="chart-container">
    <h3>🧾 Pesanan Terbaru</h3>
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
                                    @case('pending') ⏳ Pending @break
                                    @case('processing') 🔄 Proses @break
                                    @case('completed') ✅ Selesai @break
                                    @case('cancelled') ❌ Batal @break
                                @endswitch
                            </span>
                        </td>
                        <td class="text-sm text-light">{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-light" style="padding:2rem;">Belum ada pesanan 📭</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Sales Trend Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
        datasets: [{
            label: 'Jumlah Order',
            data: {!! json_encode($salesData->pluck('count')) !!},
            borderColor: '#FF8C42',
            backgroundColor: 'rgba(255, 140, 66, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#FF8C42',
            pointRadius: 4,
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

// Revenue Chart
const revCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'))) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 140, 66, 0.8)',
                'rgba(255, 201, 60, 0.8)',
                'rgba(107, 66, 38, 0.8)',
                'rgba(76, 175, 80, 0.8)',
                'rgba(33, 150, 243, 0.8)',
                'rgba(229, 57, 53, 0.8)',
            ],
            borderRadius: 8,
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
