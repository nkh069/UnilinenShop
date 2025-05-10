<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HÓA ĐƠN BÁN HÀNG</h1>
        <p>Số: {{ $invoice->invoice_number }}</p>
    </div>

    <div class="company-info">
        <h3>Thông tin công ty</h3>
        <p>Clothes Shop</p>
        <p>123 Đường ABC, Quận 1, TP.HCM</p>
        <p>Điện thoại: 0123456789</p>
        <p>Email: info@clothesshop.com</p>
    </div>

    <div class="invoice-info">
        <h3>Thông tin khách hàng</h3>
        <p>Tên: {{ $invoice->order->user->name }}</p>
        <p>Email: {{ $invoice->order->user->email }}</p>
        <p>Địa chỉ: {{ $invoice->order->shipping_address }}</p>
        <p>Ngày đặt: {{ $invoice->created_at->formatVN() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price) }}₫</td>
                <td>{{ number_format($item->subtotal) }}₫</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Tạm tính:</span>
            <span>{{ number_format($invoice->order->total_amount + $invoice->order->discount_amount - $invoice->order->tax_amount - $invoice->order->shipping_amount) }}₫</span>
        </div>
        @if($invoice->order->discount_amount > 0)
        <div class="total-row">
            <span>Giảm giá:</span>
            <span>-{{ number_format($invoice->order->discount_amount) }}₫</span>
        </div>
        @endif
        <div class="total-row">
            <span>Phí vận chuyển:</span>
            <span>{{ number_format($invoice->order->shipping_amount) }}₫</span>
        </div>
        <div class="total-row">
            <span>Thuế (10%):</span>
            <span>{{ number_format($invoice->order->tax_amount) }}₫</span>
        </div>
        <div class="total-row" style="font-weight: bold;">
            <span>Tổng cộng:</span>
            <span>{{ number_format($invoice->order->total_amount) }}₫</span>
        </div>
    </div>

    <div class="footer">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
        <p>Hóa đơn này được tạo tự động và có giá trị pháp lý.</p>
    </div>
</body>
</html> 