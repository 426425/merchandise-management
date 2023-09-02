<?php
require_once 'database.php';
connectDatabase();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST["brandName"];
    if (!empty($className)) {
        addClass('brand', $className); // 传入表和参数
        header("Location: ".$_SERVER['PHP_SELF']); // 重定向到当前页面，避免刷新时重复添加
        exit();
    }
}

$categories = showTable('brand'); //获取表brand信息
usort($categories, function($a, $b) {
    return $a['id'] - $b['id'];
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加品牌</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <button class="add-class-btn" onclick="showAddCategoryForm()">添加品牌</button>

        </div>
    </div>

    <div class="add-class-form" id="add-category-form">
        <h2 id="new-category-title">添加品牌</h2>
        <form method="post" action="brand.php">  <!--定位对应的界面-->
            品牌名称: <input type="text" name="brandName">
            <input type="submit" value="添加">
            <button type="button" onclick="hideAddCategoryForm()">取消</button>
        </form>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>品牌名称</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($categories as $category) {
            echo '<tr>';
            echo '<td>' . $category['id'] . '</td>';
            echo '<td>' . $category['name'] . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    function showAddCategoryForm() { document.getElementById("add-category-form").style.display = "block"; }
    function hideAddCategoryForm() { document.getElementById("add-category-form").style.display = "none"; }
</script>

</body>
</html>
