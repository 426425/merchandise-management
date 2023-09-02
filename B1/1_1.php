<?php
include 'database.php'; // 调用 database.php 文件
global $conn;

connectDatabase(); // 连接数据库

// 检查是否提交了注册表单
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // 获取用户输入的账号和密码
    $username = $_POST["user"];
    $password = $_POST["password"];

    // 插入用户信息到数据库的 user 表中
    $sql = "INSERT INTO user (id,username, password) VALUES (1,'$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        // 注册成功，跳转到原来的 1.php 页面
        header("Location: 1.php");
        exit();
    } else {
        // 注册失败，显示错误信息
        $message = "注册失败，请重试。";
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return']))
{
    header("Location: 1.php");
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
</head>
<body>
<h1>注册</h1>
<p><?php echo isset($message) ? $message : ""; ?></p>

<form method="post" action="">
    <label for="user">账户：</label>
    <input type="text" name="user" id="user" required><br><br>

    <label for="password">密码：</label>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" name="register" value="确定">
    <input type="submit" name="return" value="返回">
</form>
</body>
</html>
