<!DOCTYPE html>
<html>
<head>
    <title>商品管理系统</title>
    <style>
        .sidebar {float: left;width: 10%;background-color: #f2f2f2;}
        .sidebar ul {list-style-type: none;padding: 0;}
        .class-item {cursor: pointer;padding: 15px 8px;color: black;list-style-type: none;display: block;width: 86%;}
        .class-item.active {background-color: lightgray;}
        .content {height: 100%;float: right;width: 89%;background-color: #f8f8f8;}
        .add-button {margin: 16px;padding: 12px 24px;background-color: lightblue;font-size:16px;color: white;border: none;cursor: pointer;}
        .batch-button {margin: 16px;padding: 12px 24px;background-color: goldenrod;font-size:16px;color: white;border: none;cursor: pointer;}
        .show-all-button {margin: 16px;padding: 12px 24px;background-color: pink;font-size:16px;color: white;border: none;cursor: pointer;}
        .category-button {margin: 30px;padding: 5px 10px;color: gray;border: none;cursor: pointer;}
        .category-button.active {font-weight: bold;}
        table {border-collapse: collapse;width: 100%;}
        th, td {padding: 8px;text-align: left;border-bottom: 1px solid #ddd;}
        th {background-color: #f2f2f2;}
    </style>
</head>

<body>
<?php
require_once 'database.php'; // 调用database
global $conn;

connectDatabase();

$classSql = "SELECT * FROM class"; // 从class表读取所有数据
$classResult = $conn->query($classSql);

$selectedClassId = isset($_GET['classId']) ? $_GET['classId'] : null; // 检查是否有类别被选中
?>

<div class="sidebar">
    <?php if ($classResult->num_rows > 0) : ?>
        <ul>
            <?php while ($classRow = $classResult->fetch_assoc()) : ?>
                <li class="class-item<?php echo ($selectedClassId == $classRow['id']) ? ' active' : ''; ?>" onclick="selectClass(<?php echo $classRow['id']; ?>)">
                    <?php echo $classRow['name']; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>没有类别</p>
    <?php endif; ?>
</div>

<div class="content">
    <?php if ($selectedClassId !== null) : ?>
        <!-- 添加商品按钮 -->
        <div>
            <button class="add-button" onclick="location.href='2_1_1.php?classId=<?php echo $selectedClassId; ?>'">添加商品</button>
            <button class="batch-button">批量操作</button>
            <button class="show-all-button">显示全部</button>
        </div>

        <?php
        // 获取右侧商品列表
        $goodsSql = "SELECT * FROM goods WHERE classid = '$selectedClassId'";
        $goodsResult = $conn->query($goodsSql);

        // 检查是否有商品
        if ($goodsResult->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                  <th>商品ID</th>
                  <th>商品图片</th>
                  <th>商品名称</th>
                  <th>促销信息</th>
                  <th>品牌</th>
                  <th>商品介绍</th>
                  <th>划线价格</th>
                  <th>促销价格</th>
                  <th>数量</th>
                  <th>权重</th>
                  <th>是否预定</th>
                  <th>是否减价</th>
                  <th>状态</th>
                </tr>";
            while ($goodsRow = $goodsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $goodsRow['id'] . "</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($goodsRow['picture']) . "' width='100' height='100'></td>";

                echo "<td>" . $goodsRow['name'] . "</td>";
                echo "<td>" . $goodsRow['promote'] . "</td>";
                echo "<td>" . $goodsRow['brand'] . "</td>";
                echo "<td>" . $goodsRow['introduce'] . "</td>";
                echo "<td>" . $goodsRow['Scribed_price'] . "</td>";
                echo "<td>" . $goodsRow['price'] . "</td>";
                echo "<td>" . $goodsRow['quantity'] . "</td>";
                echo "<td>" . $goodsRow['gravity'] . "</td>";
                echo "<td>" . $goodsRow['booking'] . "</td>";
                echo "<td>" . $goodsRow['reduce'] . "</td>";
                echo "<td>" . $goodsRow['state'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "没有商品";
        }
        ?>
    <?php endif; ?>
</div>

<script>
    // 点击类别项时触发
    function selectClass(classId) {
        window.location.href = "?classId=" + classId;
    }
</script>
</body>
</html>
