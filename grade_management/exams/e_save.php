<?php
require_once('../components/header.php');



$test_id = $post['test_id'];
$class_info = explode(',', $post['class_id']);
$class_id = $class_info[0];
$year = $class_info[1];
$japanese = $post['japanese'];
$math = $post['math'];
$english = $post['english'];
$science = $post['science'];
$society = $post['society'];
$total = $post['total'];


// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'INSERT INTO exams (test_id, student_id, japanese,math,english,science,society,total, created_at, updated_at) VALUES (?, ?,?,?,?,?,?,?, NOW(), NOW())';
    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, $test_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $class_id, PDO::PARAM_STR);
    $stmt->bindValue(3, $japanese, PDO::PARAM_INT);
    $stmt->bindValue(4, $math, PDO::PARAM_INT);
    $stmt->bindValue(5, $english, PDO::PARAM_INT);
    $stmt->bindValue(6, $science, PDO::PARAM_INT);
    $stmt->bindValue(7, $society, PDO::PARAM_INT);
    $stmt->bindValue(8, $total, PDO::PARAM_INT);


    // SQL文の実行
    $stmt->execute();

    // データベース接続の解放
    $dbh = null;
    // 成功時のメッセージ表示
    echo '成績データがデータベースに保存されました。<a href="e_index.php">成績一覧に戻る</a>';

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
