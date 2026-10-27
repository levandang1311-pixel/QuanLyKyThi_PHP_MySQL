# ExamFlow — Website quản lý kỳ thi (PHP + MySQL)

## Yêu cầu
- XAMPP có Apache và MySQL.
- MySQL XAMPP đang chạy cổng `3307` (theo cấu hình máy hiện tại).
- Database `quan_ly_ky_thi` đã được tạo và nhập cấu trúc + dữ liệu mẫu.

## Cài đặt
1. Giải nén thư mục `QuanLyKyThi_PHP_MySQL` vào `C:\xampp\htdocs\`.
2. Mở `config/database.php` và kiểm tra host `127.0.0.1`, port `3307`, database `quan_ly_ky_thi`, user `root`, password `''`. Nếu MySQL của bạn có mật khẩu thì cập nhật `$password`.
3. Mở XAMPP, khởi động Apache và MySQL.
4. Mở trình duyệt tại `http://localhost/QuanLyKyThi_PHP_MySQL/`.

## Chức năng hiện có
- Tổng quan: đếm số sinh viên, học phần, kỳ thi, phòng thi, ca thi, đăng ký thi.
- Sinh viên: danh sách, tìm kiếm, thêm, sửa, xóa.
- Học phần: xem, thêm, xóa.
- Phòng thi: xem, thêm, xóa.
- Kỳ thi & ca thi: xem lịch ca thi và sĩ số.
- Kết quả thi: xem trạng thái và điểm.

## Lưu ý
Website sử dụng đúng tên bảng/cột theo file SQL dự án. Dữ liệu đang lấy trực tiếp từ MySQL; thao tác xóa có thể bị khóa ngoại từ chối nếu bản ghi đã được dùng ở bảng khác.
