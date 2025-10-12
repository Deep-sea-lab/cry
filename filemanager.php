<?php
$directory = '/';

// 创建上传目录
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// 处理文件上传
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $target_file = $directory . basename($file['name']);
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        echo "<div class='alert success'>文件上传成功: " . htmlspecialchars($file['name']) . "</div>";
    } else {
        echo "<div class='alert error'>文件上传失败!</div>";
    }
}

// 处理文件删除
if (isset($_GET['delete'])) {
    $file_to_delete = $directory . basename($_GET['delete']);
    if (file_exists($file_to_delete) && unlink($file_to_delete)) {
        echo "<div class='alert success'>文件删除成功: " . htmlspecialchars($_GET['delete']) . "</div>";
    } else {
        echo "<div class='alert error'>文件删除失败!</div>";
    }
}

// 获取目录中的文件
$files = array_diff(scandir($directory), array('.', '..'));
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件管理器</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="file"] {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>文件管理器</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <input type="submit" value="上传文件">
    </form>

    <h2>文件列表</h2>
    <table>
        <thead>
            <tr>
                <th>文件名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($files)): ?>
                <tr>
                    <td colspan="2">没有文件</td>
                </tr>
            <?php else: ?>
                <?php foreach ($files as $file): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file); ?></td>
                        <td>
                            <a href="?delete=<?php echo urlencode($file); ?>" onclick="return confirm('确定要删除这个文件吗？');">删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
