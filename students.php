<?php
require __DIR__ . '/config/database.php';
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
$msg=''; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action=$_POST['action']??'';
  try {
    if($action==='add'){
      $stmt=$pdo->prepare('INSERT INTO sinh_vien (cmt,ma_sv,ho_ten,ngay_sinh,lop_id,khoa_id) VALUES (?,?,?,?,?,?)');
      $stmt->execute([trim($_POST['cmt']),trim($_POST['ma_sv']),trim($_POST['ho_ten']),$_POST['ngay_sinh'],(int)$_POST['lop_id'],(int)$_POST['khoa_id']]);
      $msg='Đã thêm sinh viên.';
    } elseif($action==='delete'){
      $stmt=$pdo->prepare('DELETE FROM sinh_vien WHERE id=?'); $stmt->execute([(int)$_POST['id']]); $msg='Đã xóa sinh viên.';
    }
  } catch(Throwable $e){$err='Không thực hiện được: '.$e->getMessage();}
}
$edit=null;
if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM sinh_vien WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update'){
 try{$s=$pdo->prepare('UPDATE sinh_vien SET cmt=?,ma_sv=?,ho_ten=?,ngay_sinh=?,lop_id=?,khoa_id=? WHERE id=?');$s->execute([trim($_POST['cmt']),trim($_POST['ma_sv']),trim($_POST['ho_ten']),$_POST['ngay_sinh'],(int)$_POST['lop_id'],(int)$_POST['khoa_id'],(int)$_POST['id']]);$msg='Đã cập nhật sinh viên.';$edit=null;}catch(Throwable $e){$err='Không cập nhật được: '.$e->getMessage();}
}
$khoas=$pdo->query('SELECT id,ten_khoa FROM khoa ORDER BY ten_khoa')->fetchAll();
$lops=$pdo->query('SELECT id,ma_lop,ten_lop FROM lop ORDER BY ma_lop')->fetchAll();
$q=trim($_GET['q']??'');
$sql='SELECT sv.*,l.ma_lop,k.ten_khoa FROM sinh_vien sv JOIN lop l ON l.id=sv.lop_id JOIN khoa k ON k.id=sv.khoa_id';
$params=[];if($q!==''){$sql.=' WHERE sv.ma_sv LIKE ? OR sv.ho_ten LIKE ? OR sv.cmt LIKE ?';$params=["%$q%","%$q%","%$q%"];}
$sql.=' ORDER BY sv.id DESC';$st=$pdo->prepare($sql);$st->execute($params);$rows=$st->fetchAll();
?><!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sinh viên - ExamFlow</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="layout"><aside class="sidebar"><div class="brand">▦ ExamFlow</div><a href="index.php">Tổng quan</a><a class="active" href="students.php">Sinh viên</a><a href="courses.php">Học phần</a><a href="exams.php">Kỳ thi & ca thi</a><a href="rooms.php">Phòng thi</a><a href="results.php">Kết quả thi</a></aside><main class="main"><div class="top"><div><h1>Quản lý sinh viên</h1><div class="muted">Xem, tìm kiếm, thêm, sửa và xóa sinh viên.</div></div><a class="btn secondary" href="index.php">← Tổng quan</a></div>
<?php if($msg):?><div class="notice"><?=h($msg)?></div><?php endif?><?php if($err):?><div class="notice error"><?=h($err)?></div><?php endif?>
<div class="panel"><h2><?=$edit?'Sửa sinh viên':'Thêm sinh viên'?></h2><form method="post"><input type="hidden" name="action" value="<?=$edit?'update':'add'?>"><?php if($edit):?><input type="hidden" name="id" value="<?=h($edit['id'])?>"><?php endif?><div class="formgrid">
<div><label>CMND/CCCD</label><input name="cmt" required maxlength="12" value="<?=h($edit['cmt']??'')?>"></div><div><label>Mã sinh viên</label><input name="ma_sv" required maxlength="15" value="<?=h($edit['ma_sv']??'')?>"></div><div><label>Họ và tên</label><input name="ho_ten" required value="<?=h($edit['ho_ten']??'')?>"></div><div><label>Ngày sinh</label><input type="date" name="ngay_sinh" required value="<?=h($edit['ngay_sinh']??'')?>"></div>
<div><label>Lớp</label><select name="lop_id" required><?php foreach($lops as $l):?><option value="<?=$l['id']?>" <?=isset($edit['lop_id'])&&$edit['lop_id']==$l['id']?'selected':''?>><?=h($l['ma_lop'].' - '.$l['ten_lop'])?></option><?php endforeach?></select></div><div><label>Khoa</label><select name="khoa_id" required><?php foreach($khoas as $k):?><option value="<?=$k['id']?>" <?=isset($edit['khoa_id'])&&$edit['khoa_id']==$k['id']?'selected':''?>><?=h($k['ten_khoa'])?></option><?php endforeach?></select></div></div><button class="btn" type="submit"><?=$edit?'Lưu thay đổi':'Thêm sinh viên'?></button> <?php if($edit):?><a class="btn secondary" href="students.php">Hủy</a><?php endif?></form></div>
<div class="panel"><div class="top"><h2>Danh sách sinh viên (<?=count($rows)?>)</h2><form method="get"><input class="search" name="q" placeholder="Tìm mã SV, họ tên, CCCD..." value="<?=h($q)?>"><button class="btn" type="submit">Tìm</button></form></div><table><thead><tr><th>Mã SV</th><th>Họ tên</th><th>Ngày sinh</th><th>Lớp</th><th>Khoa</th><th>Thao tác</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=h($r['ma_sv'])?></td><td><?=h($r['ho_ten'])?></td><td><?=h($r['ngay_sinh'])?></td><td><?=h($r['ma_lop'])?></td><td><?=h($r['ten_khoa'])?></td><td><div class="actions"><a class="btn secondary" href="?edit=<?=$r['id']?>">Sửa</a><form method="post" onsubmit="return confirm('Bạn chắc chắn muốn xóa sinh viên này?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn danger" type="submit">Xóa</button></form></div></td></tr><?php endforeach?></tbody></table></div></main></div></body></html>
