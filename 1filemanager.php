<?php
// 文件管理器
$dir = isset($_GET['dir']) ? $_GET['dir'] : '.';
$dir = rtrim($dir, '/') . '/';

if (!is_dir($dir)) {
    die('目录不存在');
}

// 处理文件上传
if (isset($_FILES['upload'])) {
    $uploadFile = $dir . basename($_FILES['upload']['name']);
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadFile)) {
        echo "文件上传成功: " . htmlspecialchars(basename($_FILES['upload']['name']));
    } else {
        echo "文件上传失败";
    }
}

// 处理文件删除
if (isset($_GET['delete'])) {
    $fileToDelete = $dir . $_GET['delete'];
    if (unlink($fileToDelete)) {
        echo "文件已删除: " . htmlspecialchars($_GET['delete']);
    } else {
        echo "文件删除失败";
    }
}

// 获取目录内容
$files = scandir($dir);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>文件管理器</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; }
    </style>
</head>
<body>
    <h1>文件管理器</h1>
    <h2>当前目录: <?= htmlspecialchars($dir) ?></h2>

    <h3>上传文件</h3>
    <form enctype="multipart/form-data" method="post">
        <input type="file" name="upload" required>
        <input type="submit" value="上传">
    </form>

    <h3>文件列表</h3>
    <table>
        <tr>
            <th>文件名</th>
            <th>操作</th>
        </tr>
        <?php foreach ($files as $file): ?>
            <?php if ($file != '.' && $file != '..'): ?>
                <tr>
                    <td><?= htmlspecialchars($file) ?></td>
                    <td>
                        <a href="?delete=<?= urlencode($file) ?>&dir=<?= urlencode($dir) ?>" onclick="return confirm('确定删除吗?');">删除</a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</body>
</html>
