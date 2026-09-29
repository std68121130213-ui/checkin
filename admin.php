<?php
require "config.php"; require_admin();
$msg="";
if(isset($_GET["approve"])){ $id=(int)$_GET["approve"]; $conn->query("UPDATE users SET status='approved' WHERE id=$id"); $msg="อนุมัติผู้ใช้แล้ว"; }
if(isset($_GET["reject"])){ $id=(int)$_GET["reject"]; $conn->query("UPDATE users SET status='rejected' WHERE id=$id"); $msg="ปฏิเสธผู้ใช้แล้ว"; }
if(isset($_GET["delete_user"])){ $id=(int)$_GET["delete_user"]; if($id!=$_SESSION["user_id"]) $conn->query("DELETE FROM users WHERE id=$id"); }
if(isset($_GET["delete_activity"])){ $id=(int)$_GET["delete_activity"]; $conn->query("DELETE FROM activities WHERE id=$id"); }
if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["create_activity"])){
    $title=trim($_POST["title"]); $desc=trim($_POST["description"]); $date=$_POST["activity_date"];
    $start=$_POST["start_time"]; $end=$_POST["end_time"]; $location=trim($_POST["location"]);
    $stmt=$conn->prepare("INSERT INTO activities(title,description,activity_date,start_time,end_time,location,created_by) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi",$title,$desc,$date,$start,$end,$location,$_SESSION["user_id"]); $stmt->execute(); $msg="เพิ่มกิจกรรมแล้ว";
}
$users=$conn->query("SELECT * FROM users ORDER BY created_at DESC");
$activities=$conn->query("SELECT * FROM activities ORDER BY activity_date DESC");
$attendance=$conn->query("SELECT attendance.*,users.fullname,users.role,activities.title FROM attendance JOIN users ON users.id=attendance.user_id JOIN activities ON activities.id=attendance.activity_id ORDER BY attendance.checkin_time DESC");
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>แอดมิน</title><link rel="stylesheet" href="style.css"></head>
<body><nav><a href="dashboard.php">หน้าหลัก</a><a href="admin.php">แอดมิน</a><a href="logout.php">ออกจากระบบ</a></nav>
<div class="container">
<?php if($msg): ?><div class="msg"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<div class="card"><h2>เพิ่มกิจกรรม</h2><form method="post">
<input type="hidden" name="create_activity" value="1">
<label>ชื่อกิจกรรม</label><input name="title" required>
<label>รายละเอียด</label><textarea name="description"></textarea>
<label> วันที่จัดกิจกรรม</label>

<div class="date-box">
    <input type="date"
       id="activity_date"
       name="activity_date"
       required
       onclick="this.showPicker()">

    <span id="date_text">
        เลือกวันที่จัดกิจกรรม
    </span>
</div>

<p class="small">
    คลิกช่องวันที่เพื่อเลือกจากปฏิทิน
</p>
<label>เวลาเริ่ม</label><input type="time" name="start_time">
<label>เวลาสิ้นสุด</label><input type="time" name="end_time">
<label>สถานที่</label><input name="location">
<button>บันทึกกิจกรรม</button></form></div>

<div class="card"><h2>ผู้ลงทะเบียน</h2><table><tr><th>ชื่อ</th><th>Username</th><th>สถานะผู้ใช้</th><th>อนุมัติบัญชี</th><th>จัดการ</th></tr>
<?php while($u=$users->fetch_assoc()): ?><tr><td><?=htmlspecialchars($u['fullname'])?></td><td><?=htmlspecialchars($u['username'])?></td><td><?=htmlspecialchars($u['role'])?></td><td><?=htmlspecialchars($u['status'])?></td>
<td><?php if($u['status']==='pending'): ?><a class="btn success" href="?approve=<?=$u['id']?>">อนุมัติ</a> <a class="btn danger" href="?reject=<?=$u['id']?>">ปฏิเสธ</a><?php endif; ?>
<?php if($u['id']!=$_SESSION['user_id']): ?><a class="btn danger" href="?delete_user=<?=$u['id']?>" onclick="return confirm('ลบผู้ใช้นี้?')">ลบ</a><?php endif; ?></td></tr><?php endwhile; ?></table></div>

<div class="card"><h2>กิจกรรมทั้งหมด</h2><table><tr><th>กิจกรรม</th><th>วันที่</th><th>เวลา</th><th>สถานที่</th><th>ลบ</th></tr>
<?php while($a=$activities->fetch_assoc()): ?><tr><td><?=htmlspecialchars($a['title'])?></td><td><?=$a['activity_date']?></td><td><?=$a['start_time']?> - <?=$a['end_time']?></td><td><?=htmlspecialchars($a['location'])?></td><td><a class="btn danger" href="?delete_activity=<?=$a['id']?>" onclick="return confirm('ลบกิจกรรมและข้อมูลเช็คชื่อทั้งหมด?')">ลบ</a></td></tr><?php endwhile; ?></table></div>

<div class="card"><h2>ประวัติการเช็คชื่อ</h2><table><tr><th>ชื่อ</th><th>สถานะ</th><th>กิจกรรม</th><th>เวลาเช็คชื่อ</th><th>รายละเอียด</th></tr>
<?php while($r=$attendance->fetch_assoc()): ?><tr><td><?=htmlspecialchars($r['fullname'])?></td><td><?=htmlspecialchars($r['role'])?></td><td><?=htmlspecialchars($r['title'])?></td><td><?=$r['checkin_time']?></td><td><?=htmlspecialchars($r['detail'])?></td></tr><?php endwhile; ?></table></div>
</div></body></html>