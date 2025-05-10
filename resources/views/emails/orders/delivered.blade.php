@component('mail::message')
# Đơn hàng đã giao thành công!

Xin chào {{ $order->user->name }},

Đơn hàng #{{ $order->order_number }} của bạn đã được giao thành công vào {{ $order->delivered_at->format('d/m/Y H:i') }}.

## Chi tiết đơn hàng:

@component('mail::table')
| Sản phẩm | Số lượng | Giá |
| :------- |:--------:| ---:|
@foreach($order->items as $item)
| {{ $item->product->name }} ({{ $item->size }}, {{ $item->color }}) | {{ $item->quantity }} | {{ number_format($item->price) }}đ |
@endforeach
@endcomponent

**Tổng đơn hàng:** {{ number_format($order->total) }}đ

## Hãy đánh giá sản phẩm để nhận thưởng!

Hãy chia sẻ ý kiến của bạn về sản phẩm đã mua và nhận 100 điểm thưởng cho mỗi đánh giá.

@component('mail::button', ['url' => route('orders.review', $order->order_number), 'color' => 'success'])
Đánh giá ngay
@endcomponent

Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!

Trân trọng,<br>
{{ config('app.name') }}
@endcomponent 