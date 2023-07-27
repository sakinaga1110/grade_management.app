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
$year = $post['year'];

$test_name = $post['test_name'];
$id = $post['id'];
// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'UPDATE tests SET year = ?, test_name = ?, updated_at = NOW() WHERE id = ?';
    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, htmlspecialchars($year, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);
    $stmt->bindValue(2, htmlspecialchars($test_name, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
    $stmt->bindValue(3, htmlspecialchars($id, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);

    // SQL文の実行
    $stmt->execute();

    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '更新が完了しました。';

    // 更新したテスト情報の表示
    echo 'ID: ' . $id . ',　対象学年: ' . $year . ',　 テスト名: ' . $test_name . '';
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
    <a class="" href="t_index.php">
        <h3>テスト一覧に戻る</h3>
    </a>
    <br />
</body>

</html>