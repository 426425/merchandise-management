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
    $introduce = $_POST['introduce'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
    $imageData = $conn->real_escape_string(file_get_contents($_FILES['picture']['tmp_name'])); // 读取并转义处理图片数据
    $promote = $_POST['promote'];
    $Scribed_price = $_POST['Scribed_price'];
    $quantity = $_POST['quantity'];
    $gravity = $_POST['gravity'];
    $booking = isset($_POST['booking']) ? 1 : 0;
    $reduce = isset($_POST['reduce']) ? 1 : 0;
    $state = $_POST['state'];

    // 获取当前最大id并加一
    $sqlMaxId = "SELECT MAX(id) AS max_id FROM goods";
    $resultMaxId = $conn->query($sqlMaxId);
    $row = $resultMaxId->fetch_assoc();
    $maxId = $row['max_id'];
    $id = $maxId + 1;

    // 将商品信息插入数据库
    $sql = "INSERT INTO goods (id, classid, name,promote, brand,picture, introduce,   Scribed_price, price,quantity, gravity, booking, reduce, state) VALUES ('$id', '$classId', '$name',  '$promote',  '$brand','$imageData', '$introduce','$Scribed_price', '$price', '$quantity', '$gravity', '$booking', '$reduce', '$state')";

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
        <label for="promote">促销信息:</label>
        <input type="text" id="promote" name="promote" required>
    </div>
    <div>
        <label for="brand">商品品牌:</label>
        <input type="text" id="brand" name="brand" required>
    </div>
    <div>
        <label for="picture">商品图片:</label>
        <input type="file" id="picture" name="picture" accept="image/*" required>
    </div>
    <div>
        <label for="introduce">商品介绍:</label>
        <textarea id="introduce" name="introduce" required></textarea>
    </div>
    <div>
        <label for="Scribed_price">划线价格:</label>
        <input type="number" id="Scribed_price" name="Scribed_price" required>
    </div>
    <div>
        <label for="price">价格:</label>
        <input type="number" id="price" name="price" required>
    </div>
    <div>
        <label for="quantity">数量:</label>
        <input type="number" id="quantity" name="quantity" required>
    </div>
    <div>
        <label for="gravity">质量:</label>
        <input type="number" id="gravity" name="gravity" required>
    </div>
    <div>
        <label for="booking">预售:</label>
        <input type="checkbox" id="booking" name="booking">
    </div>
    <div>
        <label for="reduce">参与满减:</label>
        <input type="checkbox" id="reduce" name="reduce">
    </div>
    <div>
        <label for="state">状态:</label>
        <select id="state" name="state" required>
            <option value="1">上架</option>
            <option value="0">下架</option>
        </select>
    </div>
    <button type="submit">确定</button>
</form>

</body>
</html>
