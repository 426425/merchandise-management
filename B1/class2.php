<?php
require_once 'database.php';
connectDatabase();
global $conn;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST["className"];
    $simpleName = $_POST["simpleName"];
    $imageData = $conn->real_escape_string(file_get_contents($_FILES['picture']['tmp_name']));    // 处理图片数据

    $sqlMaxId = "SELECT MAX(id) AS max_id FROM class";
    $resultMaxId = $conn->query($sqlMaxId);
    $row = $resultMaxId->fetch_assoc();
    $maxId = $row['max_id'];
    $id = $maxId + 1;

    $sql = "INSERT INTO class (id, name, simplename, picture)
            VALUES ('$id', '$className', '$simpleName', '$imageData')";

    if ($conn->query($sql) === true) {
        header("Location: class.php"); // 添加完成后重定向回class.php页面
        exit();
    } else {
        echo ("插入失败: " . $conn->error);
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加类别</title>
    <style>
        body { background-color: #f1f1f1; font-family: Arial, sans-serif; }
        .container { margin: 20px; }
        .header { background-color: #337ab7; color: #fff; padding: 10px; margin-bottom: 20px;}
        .header h2 { margin: 0; }
        form { background-color: #fff; padding: 20px; }
        label { display: inline-block; width: 100px; }
        input[type="text"], input[type="file"] { margin-bottom: 10px; }
        input[type="submit"] { background-color: #428bca; color: #fff; padding: 8px 16px; border: none; cursor: pointer; }
        .cancel-btn { background-color: #ccc; color: #fff; padding: 8px 16px; border: none; cursor: pointer; }
        select { font-size: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>添加类别</h2>
    </div>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <label>类别名称:</label><br>
        <input type="text" name="className" required><br><br>

        <label>简称:</label><br>
        <input type="text" name="simpleName" required><br><br>

        <label>图片:</label><br>
        <input type="file" name="picture" required><br><br>

        <input type="submit" value="添加">
        <button type="button" class="cancel-btn" onclick="history.back()">取消</button>
    </form>
</div>

</body>
</html>
