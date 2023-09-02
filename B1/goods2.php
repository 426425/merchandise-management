<!DOCTYPE html>
<html>
<head>
    <title>添加商品</title>
</head>
<body>
<?php
require_once 'database.php'; // 调用database
global $conn;

connectDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取用户输入的商品信息
    $classId = $_GET['classId'];
    $name = $_POST['name'];
    $imageData = $conn->real_escape_string(file_get_contents($_FILES['picture']['tmp_name'])); // 读取并转义处理图片数据

    // 获取当前最大id并加一
    $sqlMaxId = "SELECT MAX(id) AS max_id FROM goods";
    $resultMaxId = $conn->query($sqlMaxId);
    $row = $resultMaxId->fetch_assoc();
    $maxId = $row['max_id'];
    $id = $maxId + 1;

    // 将商品信息插入数据库
    $sql = "INSERT INTO goods (id, classid, name,picture) VALUES ('$id', '$classId', '$name', '$imageData')";

    if ($conn->query($sql) === TRUE) {
        echo "商品添加成功！";
    } else {
        echo "商品添加失败：" . $conn->error;
    }
}

?>
<form method="post" enctype="multipart/form-data">
    <div>
        <label for="name">商品名称:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="picture">商品图片:</label>
        <input type="file" id="picture" name="picture" accept="image/*" required>
    </div>

    <button type="submit">确定</button>
</form>

</body>
</html>
