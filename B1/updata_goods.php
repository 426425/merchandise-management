<?php
require_once 'database.php'; // 调用database
global $conn;

// 连接数据库
connectDatabase();

// 获取商品 ID 和新的商品名称
$goodsId = $_POST['goodsId'];
$newName = $_POST['newName'];

// 更新商品名称
$updateSql = "UPDATE goods SET name = '$newName' WHERE id = '$goodsId'";
$result = $conn->query($updateSql);

if ($result) {
    echo "success"; // 返回成功信息
} else {
    echo "error"; // 返回错误信息
}

$conn->close(); // 关闭数据库连接
