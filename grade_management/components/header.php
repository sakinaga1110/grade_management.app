<?php
session_start();

// セッションにユーザ名が保存されていない場合はログイン画面にリダイレクト
if (!isset($_SESSION['t_name'])) {
    header('Location: login.php');
    exit;
}

// ログインしたユーザー名とログイン日時を取得
$login_user = $_SESSION['t_name'];
$login_time = date('Y-m-d H:i:s');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>成績管理アプリ　</title>
    <script src="components/grade_management.js" defer>
    </script>
</head>
<h5>ログインユーザー名:
        <?php echo $login_user; ?>
    </h5>
    <p>ログイン日時:
        <?php echo $login_time; ?>
    </p>