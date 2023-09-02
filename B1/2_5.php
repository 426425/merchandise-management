<?php
require_once 'database.php';
connectDatabase();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST["parametersName"];
    if (!empty($className)) {
        addClass('parameters', $className); // 传入表和参数
        header("Location: ".$_SERVER['PHP_SELF']); // 重定向到当前页面，避免刷新时重复添加
        exit();
    }
}

$categories = showTable('parameters'); //获取表brand信息
usort($categories, function($a, $b) {
    return $a['id'] - $b['id'];
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加品牌</title>
    <style>
        body { background-color: #f1f1f1; font-family: Arial, sans-serif; }
        .container { margin: 20px; }
        .header { background-color: #337ab7; color: #fff; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;}
        .header h2 { margin: 0; }
        .add-class-btn { background-color: #428bca; color: #fff; padding: 8px 16px; border: none; cursor: pointer; }
        .update-cache-btn { background-color: #f0ad4e; color: #fff; margin-left: 10px; padding: 8px 16px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        table tr:nth-child(even) { background-color: #e9e9e9; }
        table th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .add-class-form { display: none; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <button class="add-class-btn" onclick="showAddCategoryForm()">添加参数</button>
            <button class="update-cache-btn">更新缓存</button>
        </div>
    </div>

    <div class="add-class-form" id="add-category-form">
        <h2 id="new-category-title">添加参数</h2>
        <form method="post" action="2_5.php">  <!--定位对应的界面-->
            参数名称: <input type="text" name="parametersName">
            <input type="submit" value="添加">
            <button type="button" onclick="hideAddCategoryForm()">取消</button>
        </form>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>参数名称</th>
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
