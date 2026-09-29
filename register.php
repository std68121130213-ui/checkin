<?php
require "config.php";
$msg=""; $error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $username=trim($_POST["username"]); $fullname=trim($_POST["fullname"]);
    $password=$_POST["password"]; $role=$_POST["role"];
    if(!in_array($role,['student','pupil','director'])) $role='student';
    if(strlen($password)<6) $error="รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร";
    else {
        $hash=password_hash($password,PASSWORD_DEFAULT);
        $stmt=$conn->prepare("INSERT INTO users(username,password,fullname,role) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss",$username,$hash,$fullname,$role);
        if($stmt->execute()) $msg="ลงทะเบียนสำเร็จ กรุณารอแอดมินอนุมัติ";
        else $error="ชื่อผู้ใช้นี้อาจถูกใช้แล้ว";
    }
}
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>ลงทะเบียน</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container"><div class="card"><h2>ลงทะเบียน</h2>
<?php if($msg): ?><div class="msg"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post">
<label>ชื่อ-นามสกุล</label><input name="fullname" required>
<label>ชื่อผู้ใช้</label><input name="username" required>
<label>รหัสผ่าน</label><input type="password" name="password" required>
<label>สถานะ</label>
<select name="role"><option value="student">นักศึกษา</option><option value="pupil">นักเรียน</option><option value="director">ผู้อำนวยการ</option></select>
<button>ลงทะเบียน</button> <a class="btn" href="login.php">กลับเข้าสู่ระบบ</a>
</form></div></div></body></html>