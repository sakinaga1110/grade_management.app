<?php
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    } else {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
$post = sanitize($_POST);

$id = $post['id'];

// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // トランザクションを開始
    $dbh->beginTransaction();
    // SQL文の準備（studentsテーブルから削除）
    $sql1 = 'DELETE FROM students WHERE id = ?';
    $stmt1 = $dbh->prepare($sql1);

    // 値のバインド
    $stmt1->bindValue(1, $id, PDO::PARAM_INT);

    // SQL文の実行
    $stmt1->execute();
    // SQL文の準備（studentsテーブルから削除）
    $sql2 = 'DELETE FROM classes WHERE class_id = ?';
    $stmt2 = $dbh->prepare($sql2);

    // 値のバインド
    $stmt2->bindValue(1, $id, PDO::PARAM_INT);

    // SQL文の実行
    $stmt2->execute();

    $dbh->commit();
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
    <a class="" href="s_index.php">
        <h3>クラス一覧に戻る</h3>
    </a>
    <br />
</body>

</html>