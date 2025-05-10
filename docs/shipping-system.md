# Tài liệu hướng dẫn sử dụng hệ thống vận chuyển

## Giới thiệu
Hệ thống quản lý vận chuyển giúp theo dõi và quản lý quá trình giao hàng từ khi đơn hàng được xác nhận đến khi giao thành công cho khách hàng. Hệ thống bao gồm quản lý đơn vận chuyển (Shipment) và người vận chuyển (Shipper).

## Các khái niệm chính

### 1. Đơn vận chuyển (Shipment)
- Đơn vận chuyển được tạo sau khi đơn hàng được xác nhận
- Mỗi đơn vận chuyển liên kết với một đơn hàng
- Theo dõi trạng thái vận chuyển, thông tin vận đơn và người vận chuyển

### 2. Người vận chuyển (Shipper)
- Là đối tác giao hàng hoặc nhân viên giao hàng
- Có thể quản lý thông tin cá nhân và trạng thái hoạt động
- Được gán cho các đơn vận chuyển

## Trạng thái vận chuyển
- **Chờ xử lý (pending)**: Đơn vận chuyển vừa được tạo
- **Đang xử lý (processing)**: Đơn vận chuyển đang được chuẩn bị 
- **Đã gửi hàng (shipped)**: Đơn hàng đã được giao cho đơn vị vận chuyển
- **Đã giao hàng (delivered)**: Đơn hàng đã được giao thành công cho khách
- **Thất bại (failed)**: Đơn hàng giao không thành công

## Hướng dẫn sử dụng

### Quản lý đơn vận chuyển

#### Xem danh sách đơn vận chuyển
1. Đăng nhập vào hệ thống quản trị
2. Truy cập menu **Quản lý vận chuyển**
3. Hệ thống hiển thị danh sách các đơn vận chuyển
4. Có thể lọc theo trạng thái, thời gian và từ khóa tìm kiếm

#### Xem chi tiết đơn vận chuyển
1. Từ danh sách đơn vận chuyển, nhấp vào biểu tượng xem chi tiết
2. Hệ thống hiển thị thông tin chi tiết của đơn vận chuyển:
   - Thông tin cơ bản
   - Địa chỉ giao hàng
   - Lịch sử vận chuyển
   - Sản phẩm vận chuyển
   - Người vận chuyển

#### Cập nhật trạng thái đơn vận chuyển
1. Từ trang chi tiết đơn vận chuyển, chọn phần **Cập nhật vận chuyển**
2. Chọn trạng thái mới
3. Nhập thông tin cập nhật (nếu cần)
4. Nhấp vào nút **Cập nhật**

#### Thêm thông tin theo dõi
1. Từ trang chi tiết đơn vận chuyển, chọn tab **Lịch sử vận chuyển**
2. Nhấp vào nút **Thêm mốc theo dõi**
3. Nhập thông tin ngày, nội dung và vị trí
4. Nhấp vào nút **Thêm**

#### Xem đơn vận chuyển chưa được phân công
1. Truy cập menu **Quản lý vận chuyển**
2. Chọn tab **Chưa phân công**
3. Hệ thống hiển thị danh sách các đơn chưa được gán shipper

### Quản lý người vận chuyển (Shipper)

#### Thêm người vận chuyển mới
1. Truy cập menu **Quản lý người vận chuyển**
2. Nhấp vào nút **Thêm mới**
3. Điền thông tin người vận chuyển:
   - Tên
   - Số điện thoại
   - Email
   - Công ty (nếu có)
   - Địa chỉ
   - Trạng thái hoạt động
4. Nhấp vào nút **Lưu**

#### Gán người vận chuyển cho đơn hàng
1. Từ trang chi tiết đơn vận chuyển, chọn phần **Người vận chuyển**
2. Nhấp vào nút **Phân công**
3. Chọn người vận chuyển từ danh sách
4. Nhấp vào nút **Xác nhận**

#### Theo dõi hiệu suất của người vận chuyển
1. Truy cập menu **Quản lý người vận chuyển**
2. Nhấp vào tên người vận chuyển để xem chi tiết
3. Hệ thống hiển thị:
   - Thông tin cá nhân
   - Đánh giá hiệu suất
   - Danh sách đơn vận chuyển đã xử lý
   - Thống kê thời gian giao hàng

## Cách sử dụng API hệ thống vận chuyển

Hệ thống cung cấp các API để tích hợp với các dịch vụ bên ngoài:

### API lấy danh sách người vận chuyển
- Endpoint: `/api/admin/shippers`
- Method: GET
- Response: Danh sách shipper dưới dạng JSON

### API cập nhật trạng thái vận chuyển
- Endpoint: `/api/shipments/{id}/update-status`
- Method: PUT
- Body: `{ "status": "shipped", "notes": "Đã giao cho đơn vị vận chuyển" }`

### API thêm thông tin theo dõi
- Endpoint: `/api/shipments/{id}/add-tracking`
- Method: POST
- Body: `{ "date": "2023-11-15 14:30:00", "description": "Đang vận chuyển", "location": "Hà Nội" }`

## Xử lý các tình huống đặc biệt

### Khi đơn vận chuyển thất bại
1. Cập nhật trạng thái đơn vận chuyển thành "failed"
2. Thêm ghi chú về lý do thất bại
3. Hệ thống sẽ tự động thông báo cho admin và khách hàng

### Khi cần thay đổi người vận chuyển
1. Từ trang chi tiết đơn vận chuyển, chọn phần **Người vận chuyển**
2. Nhấp vào nút **Thay đổi**
3. Chọn người vận chuyển mới
4. Nhấp vào nút **Xác nhận**

## Thống kê và báo cáo
Hệ thống cung cấp các báo cáo thống kê về:
- Số lượng đơn vận chuyển theo trạng thái
- Thời gian giao hàng trung bình
- Hiệu suất của từng người vận chuyển
- Tỷ lệ giao hàng thành công/thất bại

## Hỗ trợ
Nếu gặp vấn đề trong quá trình sử dụng, vui lòng liên hệ:
- Email: support@clothesshop.com
- Hotline: 1900 1234 