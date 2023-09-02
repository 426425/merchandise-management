<?php
require_once 'database.php';
connectDatabase();
global $conn;

// 获取品牌列表
$sqlBrand = "SELECT name FROM brand";
$resultBrand = $conn->query($sqlBrand);
$brandOptions = "";
while ($rowBrand = $resultBrand->fetch_assoc()) {
    $brandName = $rowBrand['name'];
    $brandOptions .= "<option value='$brandName'>$brandName</option>";
}

// 获取参数列表
$sqlParameter = "SELECT name FROM parameters";
$resultParameter = $conn->query($sqlParameter);
$parameterOptions = "";
while ($rowParameter = $resultParameter->fetch_assoc()) {
    $parameterName = $rowParameter['name'];
    $parameterOptions .= "<option value='$parameterName'>$parameterName</option>";
}

// 获取规格列表
$sqlSpecs = "SELECT name FROM specs";
$resultSpecs = $conn->query($sqlSpecs);
$specsOptions = "";
while ($rowSpecs = $resultSpecs->fetch_assoc()) {
    $specsName = $rowSpecs['name'];
    $specsOptions .= "<option value='$specsName'>$specsName</option>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $className = $_POST["className"];
    $simpleName = $_POST["simpleName"];
    $imageData = $conn->real_escape_string(file_get_contents($_FILES['picture']['tmp_name']));    // 处理图片数据
    $otherName = $_POST["otherName"];
    $brand = $_POST["brand"];
    $parameter=$_POST["parameter"];
    $specs = $_POST["specs"];
    $sqlMaxId = "SELECT MAX(id) AS max_id FROM class";
    $resultMaxId = $conn->query($sqlMaxId);
    $row = $resultMaxId->fetch_assoc();
    $maxId = $row['max_id'];
    $id = $maxId + 1;

    $sql = "INSERT INTO class (id, name, simplename, picture, othername, brand, parameter, specs)
            VALUES ('$id', '$className', '$simpleName', '$imageData', '$otherName', '$brand','$parameter','$specs')";

    if ($conn->query($sql) === true) {
        header("Location: 2_2.php"); // 添加完成后重定向回2_2.php页面
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

        <label>其他名称:</label><br>
        <input type="text" name="otherName" required><br><br>

        <label>商品品牌:</label><br>
        <select name="brand" required>
            <?php echo $brandOptions; ?>
        </select><br><br>

        <label>商品参数:</label><br>
        <select name="parameter" required>
            <?php echo $parameterOptions; ?>
        </select><br><br>

        <label>规格:</label><br>
        <select name="specs" required>
            <?php echo $specsOptions; ?>
        </select><br><br>

        <input type="submit" value="添加">
        <button type="button" class="cancel-btn" onclick="history.back()">取消</button>
    </form>
</div>

</body>
</html>
