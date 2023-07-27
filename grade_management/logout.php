<?php
session_start();
// セッションを破棄してログアウト処理
session_unset();
session_destroy();
// ログアウト後にログイン画面にリダイレクト
header('Location: login.php');
exit;
?>
