<?php
session_start();
include 'component/header.php';
// var_dump($_POST);

// データベースへの接続設定
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

// POSTデータの取得とエスケープ処理
if (isset($_POST['t_name']) && isset($_POST['password'])) {
    $t_name = htmlspecialchars($_POST['t_name'], ENT_QUOTES, 'UTF-8');
    $login_password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // データベース内の教師情報を検索するクエリを準備
        $stmt = $dbh->prepare('SELECT * FROM teachers WHERE t_name=?');
        $stmt->bindParam(1, $t_name, PDO::PARAM_STR);
        $stmt->execute();

        // ユーザー情報を取得
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // パスワードの認証
        if ($user && password_verify($login_password, $user['password'])) {
            // パスワードが一致した場合はログイン成功
            $_SESSION['t_name'] = $t_name;
            header('Location: index.php');
            exit;
        } else {
            // ログイン失敗時にエラーメッセージを表示
            echo 'ログインに失敗しました。';
        }
    } catch (PDOException $e) {
        // エラーハンドリング
        echo 'エラーが発生しました：' . $e->getMessage();
    }
} else {
    // ユーザ名とパスワードが送信されていない場合はエラーメッセージを表示
    echo 'ユーザ名とパスワードを入力してください。';
}
?>
