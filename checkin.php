<?php
require "config.php"; require_login();
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT * FROM activities WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
$a=$stmt->get_result()->fetch_assoc();
if(!$a) die("ไม่พบกิจกรรม");
$msg=""; $error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $detail=trim($_POST["detail"]);
    $stmt=$conn->prepare("INSERT INTO attendance(user_id,activity_id,detail) VALUES(?,?,?)");
    $stmt->bind_param("iis",$_SESSION["user_id"],$id,$detail);
    if($stmt->execute()) $msg="เช็คชื่อสำเร็จ";
    else $error="คุณเช็คชื่อกิจกรรมนี้ไปแล้ว";
}
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>เช็คชื่อ</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container"><div class="card"><h2>เช็คชื่อ: <?=htmlspecialchars($a['title'])?></h2>
<?php if($msg): ?><div class="msg"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post"><label>รายละเอียดเพิ่มเติม</label><textarea name="detail" placeholder="เช่น มาถึงตรงเวลา"></textarea>
<button class="success">ยืนยันการเช็คชื่อ</button> <a class="btn" href="dashboard.php">กลับ</a></form>
</div></div></body></html>