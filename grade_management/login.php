<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>成績管理アプリ　</title>
    <script src="grade_management.js" defer>
    </script>
</head>
<body>
    <h2>成績管理アプリ　ログイン</h2>
    <form method="post" action="login_check.php">
        <label>ユーザ名: <input type="text" name="t_name"></label><br>
        <label>パスワード: <input type="password" name="password"></label><br>
        <input type="submit" value="ログイン">
    </form>
</body>
</html>
