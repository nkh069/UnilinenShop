<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['order', 'user']);
        
        // Tìm kiếm theo số hóa đơn
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%");
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Lọc theo khoảng thời gian
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }
        
        // Sắp xếp
        $query->orderBy('issue_date', 'desc');
        
        $invoices = $query->paginate(15);
        
        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::whereIn('status', ['processing', 'shipped', 'delivered'])
            ->whereDoesntHave('invoice')
            ->get();
        
        $users = User::all();
        
        return view('admin.invoices.create', compact('orders', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:paid,unpaid,partially_paid,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        // Lấy thông tin đơn hàng
        $order = Order::with('user')->findOrFail($request->order_id);
        
        // Tạo hóa đơn mới
        $invoice = new Invoice();
        $invoice->invoice_number = 'INV-' . strtoupper(Str::random(8));
        $invoice->order_id = $order->id;
        $invoice->user_id = $order->user_id;
        $invoice->total_amount = $order->total_amount;
        $invoice->tax_amount = $order->tax_amount;
        $invoice->shipping_amount = $order->shipping_amount;
        $invoice->discount_amount = $order->discount_amount;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date;
        $invoice->status = $request->status;
        $invoice->notes = $request->notes;
        $invoice->save();
        
        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Hóa đơn đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['order.orderItems.product', 'user'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $orders = Order::whereIn('status', ['processing', 'shipped', 'delivered'])->get();
        $users = User::all();
        
        return view('admin.invoices.edit', compact('invoice', 'orders', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:paid,unpaid,partially_paid,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $invoice = Invoice::findOrFail($id);
        $oldStatus = $invoice->status;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date;
        $invoice->status = $request->status;
        $invoice->notes = $request->notes;
        
        // Cập nhật thông tin thanh toán
        if ($request->filled('payment_details')) {
            $invoice->payment_details = $request->payment_details;
        }
        
        // Đồng bộ với đơn hàng khi trạng thái thay đổi
        if ($oldStatus != $request->status) {
            $order = $invoice->order;
            
            if ($order) {
                // Cập nhật trạng thái đơn hàng tương ứng
                if ($request->status === 'paid' && $order->payment_status !== 'paid') {
                    $order->payment_status = 'paid';
                    // Nếu đơn hàng ở trạng thái đã giao hàng, KHÔNG cần chuyển sang hoàn thành
                    // 'completed' không phải là giá trị enum hợp lệ
                    $order->save();
                } elseif ($request->status === 'cancelled' && $order->status !== 'cancelled') {
                    $order->status = 'cancelled';
                    $order->cancelled_at = now();
                    $order->save();
                    
                    // Hoàn trả hàng vào kho nếu đơn hàng chưa xử lý xong
                    if ($order->status === 'pending' || $order->status === 'processing') {
                        foreach ($order->items as $item) {
                            $inventory = \App\Models\Inventory::where('product_id', $item->product_id)
                                ->where('size', $item->size)
                                ->where('color', $item->color)
                                ->first();
                            
                            if ($inventory) {
                                $inventory->quantity += $item->quantity;
                                $inventory->save();
                            }
                        }
                    }
                }
            }
        }
        
        $invoice->save();
        
        // Nếu hóa đơn chuyển sang trạng thái đã thanh toán, cập nhật thống kê doanh thu
        if ($request->status === 'paid' && $oldStatus !== 'paid') {
            try {
                \Illuminate\Support\Facades\Artisan::call('revenue:update');
            } catch (\Exception $e) {
                // Ghi log lỗi nếu cần
            }
        }
        
        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Hóa đơn đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Hóa đơn đã được xóa thành công.');
    }
    
    /**
     * Download the invoice as PDF.
     */
    public function download(string $id)
    {
        $invoice = Invoice::with(['order.orderItems.product', 'user'])->findOrFail($id);
        
        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
