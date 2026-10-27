<?php
require __DIR__ . '/config/database.php';
$stats = [];
foreach ([
    'Sinh viên' => 'sinh_vien',
    'Học phần' => 'hoc_phan',
    'Kỳ thi' => 'ky_thi',
    'Phòng thi' => 'phong_thi',
    'Ca thi' => 'ca_thi',
    'Đăng ký thi' => 'dang_ky_thi',
] as $label => $table) {
    $stats[$label] = (int)$pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
}
$recent = $pdo->query("SELECT dk.id, dk.so_bao_danh, sv.ma_sv, sv.ho_ten, hp.ten_hoc_phan, ct.ngay_thi, ct.gio_bat_dau
 FROM dang_ky_thi dk JOIN sinh_vien sv ON sv.id=dk.sinh_vien_id
 JOIN hoc_phan hp ON hp.id=dk.hoc_phan_id JOIN ca_thi ct ON ct.id=dk.ca_thi_id
 ORDER BY dk.id DESC LIMIT 8")->fetchAll();
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ExamFlow - Quản lý kỳ thi</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="layout">
<aside class="sidebar"><div class="brand">▦ ExamFlow</div>
<a class="active" href="index.php">Tổng quan</a><a href="students.php">Sinh viên</a><a href="courses.php">Học phần</a><a href="exams.php">Kỳ thi & ca thi</a><a href="rooms.php">Phòng thi</a><a href="results.php">Kết quả thi</a>
</aside><main class="main"><div class="top"><div><h1>Tổng quan kỳ thi</h1><div class="muted">Dữ liệu lấy trực tiếp từ MySQL database quan_ly_ky_thi.</div></div><a class="btn" href="exams.php">+ Xem lịch thi</a></div>
<div class="cards"><?php foreach($stats as $label=>$n): ?><div class="card"><div class="muted"><?=h($label)?></div><div class="num"><?=number_format($n)?></div></div><?php endforeach ?></div>
<div class="panel"><h2>Đăng ký thi mới nhất</h2><?php if(!$recent): ?><div class="empty">Chưa có dữ liệu đăng ký thi.</div><?php else: ?><table><thead><tr><th>SBD</th><th>Mã SV</th><th>Họ tên</th><th>Học phần</th><th>Ngày thi</th><th>Giờ</th></tr></thead><tbody><?php foreach($recent as $r): ?><tr><td><?=h($r['so_bao_danh'])?></td><td><?=h($r['ma_sv'])?></td><td><?=h($r['ho_ten'])?></td><td><?=h($r['ten_hoc_phan'])?></td><td><?=h($r['ngay_thi'])?></td><td><?=h(substr($r['gio_bat_dau'],0,5))?></td></tr><?php endforeach ?></tbody></table><?php endif ?></div>
<div class="panel"><h2>Trạng thái kết nối</h2><p>✓ Kết nối MySQL thành công · Cổng 3307 · Database <b>quan_ly_ky_thi</b></p><p class="muted">Website hiển thị dữ liệu đã được nhập từ các file SQL của bạn.</p></div>
</main></div></body></html>
