<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 24px;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(4) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }
        .status-paid {
            background-color: #28a745;
        }
        .status-unpaid {
            background-color: #dc3545;
        }
        .status-partial {
            background-color: #ffc107;
        }
        .status-cancelled {
            background-color: #6c757d;
        }
        .header {
            display: flex;
            justify-content: space-between;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .footer {
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>CLOTHES SHOP</h2>
                            </td>
                            <td class="text-right">
                                <strong>Hóa đơn #:</strong> {{ $invoice->invoice_number }}<br>
                                <strong>Ngày tạo:</strong> {{ $invoice->issue_date->format('d/m/Y') }}<br>
                                <strong>Ngày đến hạn:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}<br>
                                <strong>Trạng thái:</strong> 
                                @if($invoice->status == 'paid')
                                    <span class="status status-paid">Đã thanh toán</span>
                                @elseif($invoice->status == 'unpaid')
                                    <span class="status status-unpaid">Chưa thanh toán</span>
                                @elseif($invoice->status == 'partially_paid')
                                    <span class="status status-partial">Thanh toán một phần</span>
                                @elseif($invoice->status == 'cancelled')
                                    <span class="status status-cancelled">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>Thông tin công ty:</strong><br>
                                Clothes Shop<br>
                                123 Đường ABC, Quận 1<br>
                                Thành phố Hồ Chí Minh, Việt Nam<br>
                                Email: contact@clothesshop.com<br>
                                Điện thoại: (028) 1234 5678
                            </td>
                            <td class="text-right">
                                <strong>Thông tin khách hàng:</strong><br>
                                {{ $invoice->user->name }}<br>
                                {{ $invoice->user->email }}<br>
                                {{ $invoice->user->phone ?? 'Không có thông tin' }}<br>
                                {{ $invoice->user->address ?? 'Không có thông tin' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td colspan="4">Thông tin đơn hàng</td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>Mã đơn hàng:</strong> {{ $invoice->order->order_number }}<br>
                    <strong>Ngày đặt hàng:</strong> {{ $invoice->order->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Địa chỉ giao hàng:</strong> {{ $invoice->order->shipping_address }}, {{ $invoice->order->shipping_city }}, {{ $invoice->order->shipping_country }}
                </td>
            </tr>
            
            <tr class="heading">
                <td>STT</td>
                <td>Sản phẩm</td>
                <td class="text-right">Số lượng</td>
                <td class="text-right">Giá tiền</td>
            </tr>
            
            @foreach($invoice->order->orderItems as $index => $item)
            <tr class="item">
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $item->product_name }}
                    @if($item->size || $item->color)
                        <br>
                        <small>
                            @if($item->size) Size: {{ $item->size }} @endif
                            @if($item->color) Màu: {{ $item->color }} @endif
                        </small>
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}₫</td>
            </tr>
            @endforeach
            
            <tr class="total">
                <td colspan="3" class="text-right">Tạm tính:</td>
                <td class="text-right">{{ number_format($invoice->total_amount - $invoice->tax_amount - $invoice->shipping_amount + $invoice->discount_amount, 0, ',', '.') }}₫</td>
            </tr>
            
            <tr class="total">
                <td colspan="3" class="text-right">Thuế (VAT):</td>
                <td class="text-right">{{ number_format($invoice->tax_amount, 0, ',', '.') }}₫</td>
            </tr>
            
            <tr class="total">
                <td colspan="3" class="text-right">Phí vận chuyển:</td>
                <td class="text-right">{{ number_format($invoice->shipping_amount, 0, ',', '.') }}₫</td>
            </tr>
            
            @if($invoice->discount_amount > 0)
            <tr class="total">
                <td colspan="3" class="text-right">Giảm giá:</td>
                <td class="text-right">-{{ number_format($invoice->discount_amount, 0, ',', '.') }}₫</td>
            </tr>
            @endif
            
            <tr class="total">
                <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                <td class="text-right"><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}₫</strong></td>
            </tr>
        </table>
        
        @if($invoice->notes)
        <div style="margin-top: 20px;">
            <strong>Ghi chú:</strong>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p>Cảm ơn bạn đã mua hàng tại Clothes Shop!</p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email: support@clothesshop.com</p>
        </div>
    </div>
</body>
</html> 