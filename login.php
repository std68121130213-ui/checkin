<?php
require "config.php";
if(isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $username=trim($_POST["username"]);
    $password=$_POST["password"];
    $stmt=$conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s",$username); $stmt->execute();
    $result=$stmt->get_result(); $u=$result->fetch_assoc();
if($u && password_verify($password,$u["password"])){
        if($u["status"]!=="approved") $error="บัญชียังไม่ได้รับการอนุมัติ หรือถูกปฏิเสธ";
        else {
            $_SESSION["user_id"]=$u["id"]; $_SESSION["fullname"]=$u["fullname"];
            $_SESSION["role"]=$u["role"];
            header("Location: dashboard.php"); exit;
        }
    } else $error="ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
}
?>
<!doctype html><html lang="th"><head><meta charset="utf-8"><title>เข้าสู่ระบบ</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container"><div class="card">
<h2>ระบบเช็คชื่อและกิจกรรม</h2>
<?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
<form method="post">
<label>ชื่อผู้ใช้</label><input name="username" required>
<label>รหัสผ่าน</label><input type="password" name="password" required>
<button>เข้าสู่ระบบ</button>
<a class="btn" href="register.php">ลงทะเบียน</a>
</form>
<p class="small">บัญชีเริ่มต้น: admin / password</p>
</div></div></body></html>