<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipper;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipperController extends Controller
{
    /**
     * Hiển thị danh sách shipper
     */
    public function index()
    {
        $shippers = Shipper::orderBy('name')->paginate(15);
        return view('admin.shippers.index', compact('shippers'));
    }

    /**
     * Hiển thị form tạo shipper
     */
    public function create()
    {
        return view('admin.shippers.create');
    }

    /**
     * Lưu shipper mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:shippers',
            'id_card' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        $data = $request->except('avatar');
        
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/shippers', 'public');
            $data['avatar'] = $avatarPath;
        }
        
        $data['status'] = $request->has('status');
        
        Shipper::create($data);
        
        return redirect()->route('admin.shippers.index')
            ->with('success', 'Shipper đã được tạo thành công');
    }

    /**
     * Hiển thị thông tin chi tiết shipper
     */
    public function show(string $id)
    {
        $shipper = Shipper::findOrFail($id);
        $shipments = Shipment::where('shipper_id', $id)
            ->with('order')
            ->orderByDesc('created_at')
            ->paginate(10);
            
        return view('admin.shippers.show', compact('shipper', 'shipments'));
    }

    /**
     * Hiển thị form chỉnh sửa shipper
     */
    public function edit(string $id)
    {
        $shipper = Shipper::findOrFail($id);
        return view('admin.shippers.edit', compact('shipper'));
    }

    /**
     * Cập nhật thông tin shipper
     */
    public function update(Request $request, string $id)
    {
        $shipper = Shipper::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:shippers,email,'.$id,
            'id_card' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string'
        ]);

        $data = $request->except(['avatar', '_token', '_method']);
        
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($shipper->avatar) {
                Storage::disk('public')->delete($shipper->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars/shippers', 'public');
            $data['avatar'] = $avatarPath;
        }
        
        $data['status'] = $request->has('status');
        
        $shipper->update($data);
        
        return redirect()->route('admin.shippers.show', $shipper->id)
            ->with('success', 'Thông tin shipper đã được cập nhật thành công');
    }

    /**
     * Xóa shipper
     */
    public function destroy(string $id)
    {
        $shipper = Shipper::findOrFail($id);
        
        // Kiểm tra xem shipper có đơn vận chuyển đang xử lý không
        $pendingShipments = Shipment::where('shipper_id', $id)
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();
            
        if ($pendingShipments > 0) {
            return back()->with('error', 'Không thể xóa shipper này vì đang có '.$pendingShipments.' đơn vận chuyển đang xử lý');
        }
        
        // Gỡ liên kết shipper khỏi các đơn vận chuyển đã hoàn thành
        Shipment::where('shipper_id', $id)->update(['shipper_id' => null]);
        
        // Xóa avatar nếu có
        if ($shipper->avatar) {
            Storage::disk('public')->delete($shipper->avatar);
        }
        
        $shipper->delete();
        
        return redirect()->route('admin.shippers.index')
            ->with('success', 'Shipper đã được xóa thành công');
    }
    
    /**
     * Hiển thị danh sách shipper đang hoạt động để gán cho đơn vận chuyển
     */
    public function selectForShipment()
    {
        $shippers = Shipper::where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'company']);
            
        return response()->json($shippers);
    }
    
    /**
     * Gán shipper cho đơn vận chuyển
     */
    public function assignToShipment(Request $request, string $shipmentId)
    {
        $request->validate([
            'shipper_id' => 'required|exists:shippers,id'
        ]);
        
        $shipment = Shipment::findOrFail($shipmentId);
        $shipment->shipper_id = $request->shipper_id;
        $shipment->save();
        
        return back()->with('success', 'Đã gán shipper cho đơn vận chuyển này');
    }
} 