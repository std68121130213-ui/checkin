<?php
require "config.php"; require_login();
$activities=$conn->query("SELECT * FROM activities ORDER BY activity_date DESC, start_time DESC");
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>หน้าหลัก</title><link rel="stylesheet" href="style.css"></head>
<body><nav>
<a href="dashboard.php">หน้าหลัก</a>
<?php if($_SESSION['role']==='admin'): ?><a href="admin.php">แอดมิน</a><?php endif; ?>
<a href="logout.php">ออกจากระบบ</a>
</nav><div class="container">
<div class="card"><h2>สวัสดี <?=htmlspecialchars($_SESSION['fullname'])?></h2><p>สถานะ: <?=htmlspecialchars($_SESSION['role'])?></p></div>
<h2>กิจกรรม</h2>
<?php while($a=$activities->fetch_assoc()): ?>
<div class="card"><h3><?=htmlspecialchars($a['title'])?></h3>
<p><?=nl2br(htmlspecialchars($a['description']))?></p>
<p>วันที่: <?=htmlspecialchars($a['activity_date'])?> | เวลา: <?=htmlspecialchars($a['start_time'])?> - <?=htmlspecialchars($a['end_time'])?></p>
<p>สถานที่: <?=htmlspecialchars($a['location'])?></p>
<a class="btn success" href="checkin.php?id=<?=$a['id']?>">เช็คชื่อกิจกรรมนี้</a>
</div>
<?php endwhile; ?>
</div></body></html>