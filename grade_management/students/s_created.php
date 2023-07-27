<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="">
    <script src="" defer></script>
</head>

<body>
    <h1>生徒登録</h1>
    <form action="s_save.php" method="post">
        所属学年
        <select name="year" required>
            <option value="1" checked>1年　</option>
            <option value="2">2年　</option>
            <option value="3">3年　</option>
        </select>
        所属クラス
        <select name="class" required>
            <option value="1" checked>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
        </select>
        学籍番号　※数字のみ
        <input type="number" name="number" required>
        <br />
        氏名
        <input type="text" name="name" required>
        <br />
        <input type="submit" value="登録">
    </form>
</body>

</html>