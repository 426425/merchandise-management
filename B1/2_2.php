<?php
require_once 'database.php';
connectDatabase();

$categories = showTable('class');
usort($categories, function($a, $b) {
    return $a['id'] - $b['id'];
});

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: 2_2_1.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>添加类别</title>
    <style>
        body { background-color: #f1f1f1; font-family: Arial, sans-serif; }
        .container { margin: 0px;width: 90% }
        .header { background-color: #337ab7; color: #fff; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;}
        .header h2 { margin: 0; }
        .add-class-btn { background-color: blue; color: white; padding: 8px 16px; border: none; cursor: pointer; }
        .update-cache-btn { background-color: #f0ad4e; color: #fff; margin-left: 10px; padding: 8px 16px; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        table tr:nth-child(even) { background-color: #e9e9e9; }
        table th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>类别列表</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" class="add-class-btn" value="添加分类">
        </form>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>类别名称</th>
            <th>简称</th>
            <th>图片</th>
            <th>其他名称</th>
            <th>商品品牌</th>
            <th>规格</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($categories as $category) {
            echo '<tr>';
            echo '<td>' . $category['id'] . '</td>';
            echo '<td>' . $category['name'] . '</td>';
            echo '<td>' . $category['simplename'] . '</td>';
            echo '<td>' . '<img src="data:image/jpeg;base64,'.base64_encode($category['picture']).'" width="100" height="100" />' . '</td>';
            echo '<td>' . $category['othername'] . '</td>';
            echo '<td>' . $category['brand'] . '</td>';
            echo '<td>' . $category['specs'] . '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
