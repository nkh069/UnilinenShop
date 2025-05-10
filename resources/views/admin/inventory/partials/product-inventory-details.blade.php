@if($product)
    <div class="product-details mb-4">
        <div class="d-flex align-items-center">
            <div class="product-image me-3">
                @if($product->productImages->count() > 0)
                    <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="no-image bg-light d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 80px;">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </div>
            <div class="product-info">
                <h5 class="mb-1">{{ $product->name }}</h5>
                <p class="text-muted mb-1">SKU: {{ $product->sku ?? 'N/A' }}</p>
                <p class="text-muted mb-0">
                    Danh mục: {{ $product->category->name ?? 'N/A' }} | 
                    Tổng tồn kho: <strong>{{ $product->stock_quantity }}</strong>
                </p>
            </div>
        </div>
    </div>

    <div class="inventory-details">
        <h6 class="border-bottom pb-2 mb-3">Chi tiết tồn kho theo biến thể</h6>
        
        @if($inventories->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i> Sản phẩm này chưa có dữ liệu tồn kho theo biến thể.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 120px;">Kích thước / Màu</th>
                            @foreach($productColors as $color)
                                <th class="text-center" style="width: 120px;">
                                    <span style="display: inline-block; width: 15px; height: 15px; border-radius: 50%; background-color: {{ $color->color_code ?? '#ccc' }}; margin-right: 5px; vertical-align: middle;"></span>
                                    {{ $color->name }}
                                </th>
                            @endforeach
                            @if(is_array($product->colors))
                                @foreach($product->colors as $color)
                                    @if(!isset($existingColors) || !in_array($color, $existingColors))
                                        <th class="text-center" style="width: 120px;">{{ $color }}</th>
                                    @endif
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productSizes as $size)
                            <tr>
                                <th class="table-light">{{ $size->name }}</th>
                                @foreach($productColors as $color)
                                    @php
                                        $inventory = $inventoryMatrix[$size->name][$color->name] ?? null;
                                    @endphp
                                    <td class="text-center">
                                        @if($inventory)
                                            <span class="fw-bold {{ $inventory->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $inventory->quantity }}
                                            </span>
                                            <div class="small text-muted">
                                                @if($inventory->quantity <= $inventory->low_stock_threshold && $inventory->quantity > 0)
                                                    <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Sắp hết</span>
                                                @elseif($inventory->quantity <= 0)
                                                    <span class="text-danger"><i class="bi bi-x-circle"></i> Hết hàng</span>
                                                @else
                                                    <i class="bi bi-check-circle"></i> Còn hàng
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                @if(is_array($product->colors))
                                    @foreach($product->colors as $color)
                                        @if(!isset($existingColors) || !in_array($color, $existingColors))
                                            @php
                                                $inventory = $inventoryMatrix[$size->name][$color] ?? null;
                                            @endphp
                                            <td class="text-center">
                                                @if($inventory)
                                                    <span class="fw-bold {{ $inventory->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $inventory->quantity }}
                                                    </span>
                                                    <div class="small text-muted">
                                                        @if($inventory->quantity <= $inventory->low_stock_threshold && $inventory->quantity > 0)
                                                            <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Sắp hết</span>
                                                        @elseif($inventory->quantity <= 0)
                                                            <span class="text-danger"><i class="bi bi-x-circle"></i> Hết hàng</span>
                                                        @else
                                                            <i class="bi bi-check-circle"></i> Còn hàng
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                        
                        @if(is_array($product->sizes))
                            @foreach($product->sizes as $sizeValue)
                                @php
                                    $existingSizes = $productSizes->pluck('name')->toArray(); 
                                @endphp
                                @if(!in_array($sizeValue, $existingSizes))
                                    <tr>
                                        <th class="table-light">{{ $sizeValue }}</th>
                                        @foreach($productColors as $color)
                                            @php
                                                $inventory = $inventoryMatrix[$sizeValue][$color->name] ?? null;
                                            @endphp
                                            <td class="text-center">
                                                @if($inventory)
                                                    <span class="fw-bold {{ $inventory->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $inventory->quantity }}
                                                    </span>
                                                    <div class="small text-muted">
                                                        @if($inventory->quantity <= $inventory->low_stock_threshold && $inventory->quantity > 0)
                                                            <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Sắp hết</span>
                                                        @elseif($inventory->quantity <= 0)
                                                            <span class="text-danger"><i class="bi bi-x-circle"></i> Hết hàng</span>
                                                        @else
                                                            <i class="bi bi-check-circle"></i> Còn hàng
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        @if(is_array($product->colors))
                                            @foreach($product->colors as $colorValue)
                                                @php
                                                    $existingColors = $productColors->pluck('name')->toArray();
                                                @endphp
                                                @if(!in_array($colorValue, $existingColors))
                                                    @php
                                                        $inventory = $inventoryMatrix[$sizeValue][$colorValue] ?? null;
                                                    @endphp
                                                    <td class="text-center">
                                                        @if($inventory)
                                                            <span class="fw-bold {{ $inventory->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ $inventory->quantity }}
                                                            </span>
                                                            <div class="small text-muted">
                                                                @if($inventory->quantity <= $inventory->low_stock_threshold && $inventory->quantity > 0)
                                                                    <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Sắp hết</span>
                                                                @elseif($inventory->quantity <= 0)
                                                                    <span class="text-danger"><i class="bi bi-x-circle"></i> Hết hàng</span>
                                                                @else
                                                                    <i class="bi bi-check-circle"></i> Còn hàng
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <h6 class="border-bottom pb-2 mb-3">Danh sách biến thể tồn kho</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Kích thước</th>
                                <th>Màu sắc</th>
                                <th>Số lượng</th>
                                <th>Ngưỡng cảnh báo</th>
                                <th>Vị trí</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->size ?? 'Mặc định' }}</td>
                                    <td>
                                        @if($item->color)
                                            <span style="display: inline-block; width: 15px; height: 15px; border-radius: 50%; background-color: 
                                                @php
                                                    $colorObj = $productColors->where('name', $item->color)->first();
                                                    echo $colorObj ? $colorObj->color_code : '#ccc';
                                                @endphp
                                            ; margin-right: 5px; vertical-align: middle;"></span>
                                            {{ $item->color }}
                                        @else
                                            Mặc định
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $item->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $item->low_stock_threshold }}</td>
                                    <td>{{ $item->location ?? 'N/A' }}</td>
                                    <td>
                                        @if($item->quantity <= 0)
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @elseif($item->quantity <= $item->low_stock_threshold)
                                            <span class="badge bg-warning text-dark">Sắp hết</span>
                                        @else
                                            <span class="badge bg-success">Còn hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.inventory.delete-variant', $item->id) }}" method="POST" class="d-inline delete-variant-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa biến thể này?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if($inventories->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu biến thể tồn kho</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('admin.inventory.add-stock-form', ['product_id' => $product->id]) }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Nhập hàng
            </a>
            <a href="{{ route('admin.inventory.adjust', ['product_id' => $product->id]) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Điều chỉnh
            </a>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> Không tìm thấy thông tin sản phẩm.
    </div>
@endif 