<?php
$id = $_POST['id'];

// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 削除したテスト情報の保存用変数
  


    // SQL文の準備
    $sql = 'DELETE FROM exams WHERE id = ?';
    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, htmlspecialchars($id, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);

    // SQL文の実行
    $stmt->execute();



    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '削除が完了しました。<br/>';

} catch (PDOException $e) {
    // エラーハンドリング
    echo 'エラーが発生しました：' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="">
    <script src="" defer></script>
</head>

<body>

    <br />
    <a class="" href="e_index.php">
        <h3>成績一覧に戻る</h3>
    </a>
    <br />
</body>

</html>