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
        .edit-input {width: 100%;}
    </style>
</head>

<body>
<?php
require_once 'database.php'; // 调用database
global $conn;

connectDatabase();

$classSql = "SELECT * FROM class"; // 从class表读取所有数据
$classResult = $conn->query($classSql);

$selectedClassId = isset($_GET['classId']) ? $_GET['classId'] : null; //$selectedClassId用来存储选中的类别ID，通过检查URL参数$_GET['classId']是否存在来判断是否有类别被选中。

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goodsId = isset($_POST['goodsId']) ? $_POST['goodsId'] : null;
    $newName = isset($_POST['newName']) ? $_POST['newName'] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $goodsId = isset($_POST['goodsId']) ? $_POST['goodsId'] : null;
        $newName = isset($_POST['newName']) ? $_POST['newName'] : null;

        if ($goodsId !== null && $newName !== null) {
            // 更新商品名称
            $updateSql = "UPDATE goods SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("si", $newName, $goodsId);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "failed";
            }
            exit(); // 终止脚本执行
        }
    }
}
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
<!--如果$classResult中存在数据，则输出一个带有类别项的无序列表。-->
<!--使用$selectedClassId和当前循环到的类别ID比较，如果相等则给类别项添加active类名，以表示当前选中的类别。-->
<!--点击类别项会调用selectClass()函数，并将对应的类别ID作为参数传递。-->


<div class="content">
    <?php if ($selectedClassId !== null) : ?>
        <!-- 添加商品按钮 -->
        <div>
            <button class="add-button" onclick="location.href='goods2.php?classId=<?php echo $selectedClassId; ?>'">添加商品</button>
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
                  <th>操作</th>
                </tr>";
            while ($goodsRow = $goodsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $goodsRow['id'] . "</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($goodsRow['picture']) . "' width='100' height='100'></td>";
                echo "<td id='goodsName" . $goodsRow['id'] . "'>" . $goodsRow['name'] . "</td>";
                echo "<td><button onclick='editGoods(" . $goodsRow['id'] . ")'>修改</button></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "没有商品";
        }
        ?>
    <?php endif; ?>
</div>
<!--如果$selectedClassId不为空，则显示右侧的内容。-->
<!--显示一个"添加商品"按钮，点击按钮会跳转到goods2.php页面，并将选中的类别ID作为参数传递。-->
<!--查询数据库，获取属于选中类别ID的商品列表，并将结果赋给$goodsResult。-->
<!--检查是否有商品，如果有则以表格形式展示商品的ID、图片、名称和操作按钮。-->
<!--点击"修改"按钮会调用editGoods()函数，并将对应商品的ID作为参数传递-->




<script>
    // 点击类别项时触发

    function selectClass(classId)  //selectClass()函数用来跳转到指定类别ID的页面。
    {
        window.location.href = "?classId=" + classId;
    }


    function editGoods(goodsId) {  //editGoods()函数用来编辑商品名称。
        var tdElement = document.getElementById("goodsName" + goodsId);
        var oldValue = tdElement.innerText.trim();

        tdElement.innerHTML = '<input type="text" id="input' + goodsId + '" value="' + oldValue + '">'; //将单元格的内容替换为一个输入框，并将原始值作为输入框的默认值。
        var inputElement = document.getElementById("input" + goodsId);
        inputElement.focus();

        function saveChanges() {  //用于保存修改后的商品名称到数据库。
            var newValue = inputElement.value;
            tdElement.innerHTML = newValue;
            var xhr = new XMLHttpRequest(); // 发送AJAX请求将修改后的商品名称保存到数据库 创建XMLHttpRequest对象并发送异步请求，将商品ID和新的商品名称作为参数发送。
            xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText); // 输出AJAX请求的响应结果，可以根据情况进行处理
                    } else {
                        console.error('AJAX请求失败');
                    }
                }
            };
            xhr.send('goodsId=' + encodeURIComponent(goodsId) + '&newName=' + encodeURIComponent(newValue));
            document.removeEventListener("keydown", handleKeyDown);
        }
        function handleKeyDown(event) {  //监听键盘事件，如果按下的是Enter键，则调用saveChanges()函数保存修改并移除键盘事件监听器
            if (event.key === "Enter") {
                event.preventDefault();
                saveChanges();
            }
        }
        document.addEventListener("keydown", handleKeyDown);
    }
</script>

</body>
</html>
