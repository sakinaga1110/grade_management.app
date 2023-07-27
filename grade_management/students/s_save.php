<?php
require_once('../components/header.php');
$post = sanitize($_POST);

$year = $post['year'];
$class = $post['class'];
$number = $post['number'];
$name = $post['name'];



// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'INSERT INTO students (year, class, number, name, created_at, updated_at) VALUES (?, ?,?,?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, $year, PDO::PARAM_INT);
    $stmt->bindValue(2, $class, PDO::PARAM_INT);
    $stmt->bindValue(3, $number, PDO::PARAM_INT);
    $stmt->bindValue(4, $name, PDO::PARAM_STR);


    // SQL文の実行
    $stmt->execute();

    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '登録が完了しました。　対象学年　' . $year . '　クラス　' . $class . '　学籍番号　' . $number . '　氏名　' . $name;

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
    <br />
    <a class="" href="s_index.php">
        <h3>生徒一覧に戻る</h3>
    </a>
    <br />
</body>

</html>