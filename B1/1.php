<?php
require_once 'database.php'; // 调用 database.php 文件
global $conn;

checkDatabaseAndTables();
connectDatabase();

// 检查是否提交了登录表单
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST["user"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        header("Location: 2.html"); // 登录成功，跳转到 2.html 页面
        exit();
    } else {
        $message = "输入错误，请重新输入账号和密码。"; // 登录失败，显示错误信息
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>数据库检查</title>
</head>
<body>
<h1>数据库检查</h1>
<p><?php echo isset($message) ? $message : ""; ?></p>

<form method="POST" action="">
    <label for="user">账户：</label>
    <input type="text" name="user" id="user" required><br><br>

    <label for="password">密码：</label>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" name="login" value="登录">
</form>

<form method="GET" action="1_1.php">
    <input type="submit" name="register" value="注册">
</form>

</body>
</html>
