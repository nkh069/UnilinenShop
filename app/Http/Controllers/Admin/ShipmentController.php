<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Shipper;
use App\Models\ActivityLog;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipments = Shipment::with(['order', 'shipper'])->orderByDesc('created_at')->paginate(15);
        return view('admin.shipments.index', compact('shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::where('status', 'processing')->orWhere('status', 'pending')->get();
        $shippers = Shipper::where('status', true)->orderBy('name')->get();
        return view('admin.shipments.create', compact('orders', 'shippers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shipment = Shipment::with(['order', 'shipper'])->findOrFail($id);
        $availableShippers = Shipper::where('status', true)->orderBy('name')->get();
        return view('admin.shipments.show', compact('shipment', 'availableShippers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shipment = Shipment::findOrFail($id);
        $shippers = Shipper::where('status', true)->orderBy('name')->get();
        return view('admin.shipments.edit', compact('shipment', 'shippers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shipment = Shipment::findOrFail($id);
        
        // Kiểm tra nếu là thêm mốc theo dõi mới
        if ($request->has('action') && $request->action === 'add_tracking_history') {
            $historyItem = [
                'date' => $request->history_date,
                'description' => $request->history_description,
            ];
            
            if ($request->filled('history_location')) {
                $historyItem['location'] = $request->history_location;
            }
            
            // Khởi tạo mảng tracking_history nếu chưa có
            $trackingHistory = $shipment->tracking_history ?? [];
            $trackingHistory[] = $historyItem;
            
            $shipment->tracking_history = $trackingHistory;
            $shipment->save();
            
            return redirect()->back()->with('success', 'Đã thêm mốc theo dõi mới.');
        }
        
        // Kiểm tra nếu đổi người vận chuyển
        if ($request->has('action') && $request->action === 'assign_shipper') {
            $request->validate([
                'shipper_id' => 'required|exists:shippers,id'
            ]);
            
            $shipment->shipper_id = $request->shipper_id;
            $shipment->save();
            
            return redirect()->back()->with('success', 'Đã phân công người vận chuyển mới.');
        }
        
        // Cập nhật thông tin vận chuyển
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,failed',
            'shipping_method' => 'required|in:standard,express',
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:255',
            'tracking_url' => 'nullable|url',
            'notes' => 'nullable|string',
            'shipper_id' => 'nullable|exists:shippers,id'
        ]);
        
        $shipment->status = $request->status;
        $shipment->shipping_method = $request->shipping_method;
        $shipment->tracking_number = $request->tracking_number;
        $shipment->carrier = $request->carrier;
        $shipment->tracking_url = $request->tracking_url;
        $shipment->notes = $request->notes;
        $shipment->shipper_id = $request->shipper_id;
        
        // Cập nhật thời gian gửi hàng và giao hàng dựa trên trạng thái
        if ($request->filled('shipped_at')) {
            $shipment->shipped_at = $request->shipped_at;
        } elseif ($request->status === 'shipped' && !$shipment->shipped_at) {
            $shipment->shipped_at = now();
        }
        
        if ($request->filled('delivered_at')) {
            $shipment->delivered_at = $request->delivered_at;
        } elseif ($request->status === 'delivered' && !$shipment->delivered_at) {
            $shipment->delivered_at = now();
        }
        
        // Cập nhật đơn hàng liên quan nếu trạng thái thay đổi
        if ($shipment->isDirty('status')) {
            $order = $shipment->order;
            
            if ($request->status === 'shipped' && $order->status === 'processing') {
                $order->status = 'shipped';
                $order->save();
            } elseif ($request->status === 'delivered') {
                $order->status = 'delivered';
                $order->delivered_at = now();
                $order->save();
            }
        }
        
        $shipment->save();
        
        return redirect()->route('admin.shipments.show', $shipment->id)
            ->with('success', 'Thông tin vận chuyển đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Update shipment status.
     */
    public function updateStatus(Request $request, string $id)
    {
        // Logic để cập nhật trạng thái vận chuyển
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái vận chuyển.');
    }
    
    /**
     * Display pending shipments.
     */
    public function pending(Request $request)
    {
        // Debug để phát hiện lỗi
        \Log::info('Pending shipments route called');
        
        $query = Shipment::with(['order', 'order.user'])
            ->where('status', 'pending')
            ->orderByDesc('created_at');
            
        // Tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('shipping_address', 'like', "%{$search}%")
                        ->orWhere('shipping_city', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                  });
            });
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Lấy danh sách shipper có sẵn
        $availableShippers = \App\Models\User::where('role', 'shipper')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $shipments = $query->paginate(15);
        
        return view('admin.shipments.pending', compact('shipments', 'availableShippers'));
    }
    
    /**
     * Hiển thị đơn vận chuyển chưa phân công shipper
     */
    public function unassigned(Request $request)
    {
        // Debug để phát hiện lỗi
        \Log::info('Unassigned shipments route called');
        
        $query = Shipment::with(['order', 'order.user'])
            ->whereNull('shipper_id')
            ->orderByDesc('created_at');
            
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to showing pending and processing shipments
            $query->whereIn('status', ['pending', 'processing']);
        }
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                  });
            });
        }
        
        $shipments = $query->paginate(15);
        $availableShippers = Shipper::where('status', true)->orderBy('name')->get();
            
        return view('admin.shipments.unassigned', compact('shipments', 'availableShippers'));
    }

    /**
     * Phân công shipper cho đơn vận chuyển
     */
    public function assignShipper(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipper_id' => 'required|exists:shippers,id'
        ]);

        if (!is_null($shipment->shipper_id)) {
            return redirect()->back()
                ->with('error', 'Đơn vận chuyển này đã được phân công cho shipper khác.');
        }

        $shipment->shipper_id = $request->shipper_id;
        $shipment->assigned_at = now();
        $shipment->save();

        // Ghi log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'assign_shipment',
            'model_type' => 'App\Models\Shipment',
            'model_id' => $shipment->id,
            'description' => 'Đã phân công đơn vận chuyển #' . $shipment->tracking_number . ' cho shipper ID: ' . $request->shipper_id
        ]);

        return redirect()->back()
            ->with('success', 'Đã phân công đơn vận chuyển thành công.');
    }

    /**
     * Thay đổi shipper cho đơn vận chuyển
     */
    public function changeShipper(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipper_id' => 'required|exists:shippers,id'
        ]);

        $oldShipperId = $shipment->shipper_id;
        
        // Chỉ cho phép thay đổi khi trạng thái là pending hoặc processing
        if (!in_array($shipment->status, ['pending', 'processing'])) {
            return redirect()->back()
                ->with('error', 'Chỉ có thể thay đổi shipper cho đơn vận chuyển có trạng thái đang chờ hoặc đang xử lý.');
        }

        $shipment->shipper_id = $request->shipper_id;
        $shipment->updated_at = now();
        $shipment->save();

        // Ghi log hoạt động
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'change_shipment_shipper',
            'model_type' => 'App\Models\Shipment',
            'model_id' => $shipment->id,
            'description' => 'Đã thay đổi shipper từ ID: ' . $oldShipperId . ' sang ID: ' . $request->shipper_id . ' cho đơn vận chuyển #' . $shipment->tracking_number
        ]);

        return redirect()->back()
            ->with('success', 'Đã thay đổi shipper cho đơn vận chuyển thành công.');
    }
}
