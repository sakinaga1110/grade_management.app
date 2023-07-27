<?php 
require_once('../components/header.php');
$post=sanitize($_POST);

$year = $post['year'];
$test_name = $post['test_name'];

// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'INSERT INTO tests (year, test_name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, $year, PDO::PARAM_INT);
    $stmt->bindValue(2, $test_name, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '登録が完了しました。対象学年' . $year . 'テスト名' . $test_name;

} catch (PDOException $e) {
    // エラーハンドリング
    echo 'エラーが発生しました：' . $e->getMessage();
}

/*
//postされたデータを受け取りサニタイズ
//データベース接続　学年(year)とテスト名(test_name)をフォームから受け取り登録　id, year, test_name, created_at, updated_at
//成功したら'登録が完了しました。対象学年<?php echo $year;?>テスト名<?php echo $test_name?>'
*/
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
    <br/>
<a class="" href="t_index.php"><h3>テスト一覧に戻る</h3></a>
<br/>
</body>
</html>