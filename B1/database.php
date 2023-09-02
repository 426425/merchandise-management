<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "mygoods";
// 创建数据库连接
$conn = null;
function connectDatabase() //连接数据库
{
    global $conn, $servername, $username, $password, $dbname;
    if ($conn === null)   // 创建数据库连接
    {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error)   // 检查数据库连接是否成功
        {
            die("数据库连接失败: " . $conn->connect_error);
        }
    }
}
function closeDatabase() {
    global $conn;
    if ($conn !== null) {
        $conn->close();
        $conn = null;
    }
}
function checkDatabaseAndTables()
{
    global $servername, $username, $password, $dbname;
    $dbConnection = new mysqli($servername, $username, $password); // 创建数据库连接 因为$conn已经有定义，$dbConnection中没有dbname如果仍旧使用$conn会覆盖其他文件对$conn的使用所以换一个命名

    if ($dbConnection->connect_error) {
        die("数据库连接失败!");
    }

    $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";     // 检查数据库是否存在
    $result = $dbConnection->query($sql);

    if ($result->num_rows == 0) // 不存在就创建数据库
    {
        $createDbSql = "CREATE DATABASE $dbname";
        if ($dbConnection->query($createDbSql) !== TRUE) {
            $dbConnection->close();
            die("数据库创建失败!");
        }
    }

    $dbConnection->select_db($dbname); // 选择数据库

    $tables = array(
        'user'  => 'id INT(10), username VARCHAR(20), password VARCHAR(20)',
        'goods' => 'id INT(11) PRIMARY KEY, name VARCHAR(100),
                    classid INT(11), introduce TEXT, 
                    price INT(10), promote VARCHAR(10), 
                    brand VARCHAR(10), picture LONGBLOB, 
                    Scribed_price INT(10), quantity INT(10), gravity INT(10), 
                    booking ENUM("是", "否") COLLATE utf8_unicode_ci NOT NULL, 
                    reduce ENUM("是", "否") COLLATE utf8_unicode_ci NOT NULL, 
                    state ENUM("上架销售", "放入仓库") COLLATE utf8_unicode_ci NOT NULL',

        'class' => 'id INT(11) NOT NULL, name VARCHAR(11) COLLATE utf8_unicode_ci DEFAULT NULL, 
                    simplename VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL, 
                    picture LONGBLOB NOT NULL, 
                    othername VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL, 
                    brand VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL, 
                    parameter VARBINARY(10) NOT NULL, 
                    specs VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL',

        'brand' => 'id INT(10), name VARCHAR(20)',
        'specs' => 'id INT(10), name VARCHAR(20)',
        'parameters' => 'id INT(10), name VARCHAR(20)'
    );

    foreach ($tables as $tableName => $tableColumns) {
        // 检查表是否存在
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $dbConnection->query($checkTableSql);

        if ($result->num_rows == 0) {
            // 创建表
            $createTableSql = "CREATE TABLE $tableName ($tableColumns)";
            if ($dbConnection->query($createTableSql) !== TRUE) {
                echo "创建表 '$tableName' 失败!";
            }
        }

        // 检查id字段是否为主键
        $checkPrimaryKeySql = "SHOW KEYS FROM $tableName WHERE Column_name = 'id' AND Key_name = 'PRIMARY'";
        $result = $dbConnection->query($checkPrimaryKeySql);

        if ($result->num_rows == 0) {
            // 将id字段设置为主键
            $alterTableSql = "ALTER TABLE $tableName MODIFY COLUMN id INT(11) PRIMARY KEY";
            if ($dbConnection->query($alterTableSql) !== TRUE) {
                echo "设置表 '$tableName' 的 id 字段为主键时发生错误!";
            }
        }
    }
    $dbConnection->close(); // 关闭数据库连接
    echo "初始化完毕！";
}

function addClass($tableName, $name) {
    global $conn;
    connectDatabase();

    // 查询当前最大的 id 值
    $query = "SELECT MAX(id) FROM $tableName";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $maxId = $row['MAX(id)'];

    // 新增类别，并自动生成递增的 id
    $sql = "INSERT INTO $tableName (id, name) VALUES (" . ($maxId + 1) . ", '$name')";
    if ($conn->query($sql) === TRUE) {
        echo "类别添加成功！";
        echo '<script>document.getElementById("new-category-title").innerHTML = "' . $name . '";</script>';
    } else {
        echo "类别添加失败: " . $conn->error;
    }

    closeDatabase(); // 添加类后关闭数据库连接
}

function showTable($table) {
    global $conn;
    connectDatabase();
    // 从表中读取所有类别
    $query = "SELECT * FROM $table";
    $result = $conn->query($query);
    $categories = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    closeDatabase();
    return $categories;
}


// 删除商品
function deleteProduct($product_id) {
    global $conn;

    connectDatabase();

    $sql = "DELETE FROM products WHERE id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        echo "商品删除成功！";
    } else {
        echo "商品删除失败: " . $conn->error;
    }
}


?>
