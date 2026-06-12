<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(20);

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        if ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
        }

        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status order berhasil diperbarui!');
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }

    public function export(Request $request)
    {
        $query = Order::with('items');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->get();

        $filename = 'pesanan_' . date('Y-m-d_His') . '.xls';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";

        // Styles - WrapText only for table cells
        $xml .= '<Styles>
            <Style ss:ID="Default"><Font ss:FontName="Times New Roman" ss:Size="12"/></Style>
            <Style ss:ID="Title"><Font ss:FontName="Times New Roman" ss:Size="16" ss:Bold="1"/><Alignment ss:Vertical="Center"/></Style>
            <Style ss:ID="Sub"><Font ss:FontName="Times New Roman" ss:Size="10" ss:Color="#666666"/><Alignment ss:Vertical="Center"/></Style>
            <Style ss:ID="Header"><Font ss:FontName="Times New Roman" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#FA7302" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="Cell"><Font ss:FontName="Times New Roman" ss:Size="11"/><Alignment ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="CellCenter"><Font ss:FontName="Times New Roman" ss:Size="11"/><Alignment ss:Horizontal="Center" ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="CellRight"><Font ss:FontName="Times New Roman" ss:Size="11"/><Alignment ss:Horizontal="Right" ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="EvenRow"><Font ss:FontName="Times New Roman" ss:Size="11"/><Interior ss:Color="#FFF3E8" ss:Pattern="Solid"/><Alignment ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="EvenCenter"><Font ss:FontName="Times New Roman" ss:Size="11"/><Interior ss:Color="#FFF3E8" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center" ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
            <Style ss:ID="EvenRight"><Font ss:FontName="Times New Roman" ss:Size="11"/><Interior ss:Color="#FFF3E8" ss:Pattern="Solid"/><Alignment ss:Horizontal="Right" ss:Vertical="Top" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>
        </Styles>' . "\n";

        $xml .= '<Worksheet ss:Name="Pesanan"><Table>' . "\n";

        // Column widths
        $xml .= '<Column ss:Width="35"/><Column ss:Width="100"/><Column ss:Width="130"/><Column ss:Width="110"/><Column ss:Width="180"/><Column ss:Width="120"/><Column ss:Width="200"/><Column ss:Width="100"/><Column ss:Width="70"/><Column ss:Width="120"/>' . "\n";

        // Title
        $xml .= '<Row><Cell ss:StyleID="Title"><Data ss:Type="String">Laporan Pesanan - Syamama Kitchen</Data></Cell></Row>' . "\n";
        $xml .= '<Row><Cell ss:StyleID="Sub"><Data ss:Type="String">Diekspor pada: ' . now()->format('d F Y H:i') . '</Data></Cell></Row>' . "\n";
        $xml .= '<Row></Row>' . "\n";

        // Header
        $headers = ['No', 'Kode Order', 'Pelanggan', 'Telepon', 'Alamat', 'Catatan', 'Item Pesanan', 'Total (Rp)', 'Status', 'Tanggal'];
        $xml .= '<Row>';
        foreach ($headers as $h) {
            $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">' . $h . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        $no = 1;
        foreach ($orders as $order) {
            $items = $order->items->map(fn($i) => $i->product_name . ' x' . $i->quantity)->implode(', ');
            $total = number_format($order->total_amount, 0, ',', '.');
            $status = match($order->status) {
                'pending' => 'Pending', 'processing' => 'Proses',
                'completed' => 'Selesai', 'cancelled' => 'Batal', default => $order->status,
            };
            $even = ($no % 2 == 0);
            $s = $even ? 'EvenRow' : 'Cell';
            $sc = $even ? 'EvenCenter' : 'CellCenter';
            $sr = $even ? 'EvenRight' : 'CellRight';

            $xml .= '<Row>';
            $xml .= "<Cell ss:StyleID=\"{$sc}\"><Data ss:Type=\"Number\">{$no}</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">{$order->order_code}</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">" . htmlspecialchars($order->customer_name) . "</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">{$order->customer_phone}</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">" . htmlspecialchars($order->customer_address) . "</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">" . htmlspecialchars($order->customer_note ?? '-') . "</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$s}\"><Data ss:Type=\"String\">" . htmlspecialchars($items) . "</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$sr}\"><Data ss:Type=\"String\">{$total}</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$sc}\"><Data ss:Type=\"String\">{$status}</Data></Cell>";
            $xml .= "<Cell ss:StyleID=\"{$sc}\"><Data ss:Type=\"String\">" . $order->created_at->format('d/m/Y H:i') . "</Data></Cell>";
            $xml .= '</Row>' . "\n";
            $no++;
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return response($xml)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportPdf(Request $request)
    {
        $query = Order::with('items');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.pdf', [
            'orders' => $orders,
            'exportDate' => now()->format('d F Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'pesanan_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
