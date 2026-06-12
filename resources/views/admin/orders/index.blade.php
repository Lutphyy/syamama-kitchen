@extends('admin.layouts.app')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="admin-header">
    <h1>Kelola Pesanan</h1>
    <div style="display:flex; gap:0.5rem;">
        <a href="{{ route('admin.orders.export', request()->all()) }}" class="btn btn-sm btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:4px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
            Export CSV
        </a>
        <a href="{{ route('admin.orders.exportPdf', request()->all()) }}" class="btn btn-sm btn-danger">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:4px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
            Export PDF
        </a>
    </div>
</div>

<!-- Status Tabs -->
<div class="category-pills mb-3">
    <a href="{{ route('admin.orders.index') }}" class="category-pill {{ !request('status') ? 'active' : '' }}">
        Semua ({{ $statusCounts['all'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="category-pill {{ request('status') == 'pending' ? 'active' : '' }}">
        Pending ({{ $statusCounts['pending'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="category-pill {{ request('status') == 'completed' ? 'active' : '' }}">
        Selesai ({{ $statusCounts['completed'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="category-pill {{ request('status') == 'cancelled' ? 'active' : '' }}">
        Batal ({{ $statusCounts['cancelled'] }})
    </a>
</div>

<!-- Search & Filter -->
<form action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-1 mb-3" style="flex-wrap:wrap;">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="form-control" style="max-width:250px;">
    <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="max-width:170px;">
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Reset</a>
</form>

<!-- Orders Table -->
<div class="table-wrap desktop-table">
    <table class="table">
        <thead>
            <tr>
                <th>Kode Order</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><span class="font-bold">{{ $order->order_code }}</span></td>
                    <td>
                        <div class="font-bold">{{ $order->customer_name }}</div>
                        <div class="text-sm text-light">{{ $order->customer_phone }}</div>
                    </td>
                    <td>
                        <span class="text-sm">{{ $order->items->count() }} item</span>
                    </td>
                    <td class="font-bold text-primary">{{ $order->formatted_total }}</td>
                    <td>
                        <span class="badge badge-{{ $order->status }}">
                            @switch($order->status)
                                @case('pending') Pending @break
                                @case('completed') Selesai @break
                                @case('cancelled') Batal @break
                                @default Pending @break
                            @endswitch
                        </span>
                    </td>
                    <td class="text-sm text-light">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-secondary" onclick="showOrderDetail({{ json_encode($order->load('items')) }})">Detail</button>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex gap-1">
                                @csrf @method('PUT')
                                <select name="status" class="form-control" style="padding:0.3rem 0.5rem; font-size:0.8rem; min-width:100px;" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-light" style="padding:2rem;">Belum ada pesanan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Card View for Orders -->
<div class="mobile-cards" style="grid-template-columns:1fr;">
    @forelse($orders as $order)
        <div class="mobile-card">
            <div class="mobile-card-header" style="flex-direction:row;justify-content:space-between;align-items:flex-start;">
                <div>
                    <h4 style="margin:0 0 0.3rem 0;font-size:0.9rem;font-weight:800;">{{ $order->order_code }}</h4>
                    <span class="badge badge-{{ $order->status }}" style="font-size:0.65rem;">
                        @switch($order->status)
                            @case('pending') Pending @break
                            @case('completed') Selesai @break
                            @case('cancelled') Batal @break
                            @default Pending @break
                        @endswitch
                    </span>
                </div>
                <span class="text-primary font-bold" style="font-size:0.9rem;">{{ $order->formatted_total }}</span>
            </div>
            <div class="mobile-card-body">
                <div class="mobile-card-row">
                    <span class="text-light" style="font-size:0.75rem;">Pelanggan:</span>
                    <span style="font-size:0.8rem;font-weight:600;">{{ $order->customer_name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="text-light" style="font-size:0.75rem;">Telepon:</span>
                    <span style="font-size:0.75rem;">{{ $order->customer_phone }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="text-light" style="font-size:0.75rem;">Item:</span>
                    <span style="font-size:0.75rem;">{{ $order->items->count() }} item</span>
                </div>
                <div class="mobile-card-row">
                    <span class="text-light" style="font-size:0.75rem;">Tanggal:</span>
                    <span style="font-size:0.75rem;">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            <div class="mobile-card-actions" style="flex-direction:column;gap:0.5rem;">
                <button class="btn btn-sm btn-secondary" onclick="showOrderDetail({{ json_encode($order->load('items')) }})" style="width:100%;">Lihat Detail</button>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="width:100%;">
                    @csrf @method('PUT')
                    <select name="status" class="form-control" style="padding:0.5rem;font-size:0.8rem;width:100%;" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </form>
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:2rem;" class="text-light">Belum ada pesanan</div>
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $orders->withQueryString()->links() }}
</div>

<!-- Order Detail Modal -->
<div class="modal-overlay" id="orderDetailModal">
    <div class="modal" style="max-width:600px;">
        <div class="modal-header">
            <h3>Detail Pesanan</h3>
            <button class="modal-close" onclick="closeModal('orderDetailModal')">✕</button>
        </div>
        <div class="modal-body" id="orderDetailContent">
            <!-- Filled by JS -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function showOrderDetail(order) {
    let itemsHtml = order.items.map(item =>
        `<div class="summary-row">
            <span class="text-sm">${item.product_name} × ${item.quantity}</span>
            <span class="text-sm font-bold">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</span>
        </div>`
    ).join('');

    let statusBadge = {
        'pending': 'Pending',
        'processing': 'Proses',
        'completed': 'Selesai',
        'cancelled': 'Batal'
    };

    document.getElementById('orderDetailContent').innerHTML = `
        <div style="margin-bottom:1.5rem;">
            <div class="flex justify-between items-center mb-2">
                <span class="font-bold text-lg">${order.order_code}</span>
                <span class="badge badge-${order.status}">${statusBadge[order.status]}</span>
            </div>
            <div style="background:var(--bg-warm); border-radius:12px; padding:1rem; margin-bottom:1rem;">
                <p><strong>Nama:</strong> ${order.customer_name}</p>
                <p><strong>Telp:</strong> ${order.customer_phone}</p>
                <p><strong>Alamat:</strong> ${order.customer_address}</p>
                ${order.customer_note ? `<p><strong>Catatan:</strong> ${order.customer_note}</p>` : ''}
            </div>
            <h4 style="margin-bottom:0.5rem;">Item Pesanan</h4>
            ${itemsHtml}
            <div class="summary-row summary-total">
                <span class="font-bold">Total</span>
                <span class="total-price">Rp ${Number(order.total_amount).toLocaleString('id-ID')}</span>
            </div>
        </div>
    `;
    openModal('orderDetailModal');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('active');
    });
});
</script>
@endsection
