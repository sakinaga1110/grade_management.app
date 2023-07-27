<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="">
    <script src="" defer></script>
</head>

<body>
    <h1>テスト作成</h1>
    <form action="t_save.php" method="post">
        対象学年
        <select name="year" required>
            <option value="1" checked>1年　</option>
            <option value="2">2年　</option>
            <option value="3">3年　</option>
        </select>
        <br />
        テスト名
        <input type="text" name="test_name" required>
        <input type="submit" value="作成">
    </form>
</body>

</html>