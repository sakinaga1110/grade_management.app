<?php
require_once('../components/header.php');

// フォームから送信されたデータの受け取り
if ($_SERVER['REQUEST_METHOD'] === 'POST')
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
    // トランザクションの開始
    $dbh->beginTransaction();
    // 役職に応じて条件を追加してSQL文を作成
    $sql1 = 'INSERT INTO classes (year, name) VALUES (:year,:name)';
    // SQL文の準備
    $stmt1 = $dbh->prepare($sql1);

    // パラメータのバインド
    $stmt1->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt1->bindParam(':name', $name, PDO::PARAM_STR);
    // SQL文の実行
    $stmt1->execute();

    $class_id = $dbh->lastInsertId(); // 直前のINSERT文で挿入されたIDを取得
    // 役職に応じて条件を追加してSQL文を作成
    $sql2 = 'INSERT INTO students (class_id, class, number , created_at , updated_at) VALUES (:class_id, :class, :number , now(), now())';

    // SQL文の準備
    $stmt2 = $dbh->prepare($sql2);

    // パラメータのバインド
    $stmt2->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt2->bindParam(':class', $class, PDO::PARAM_INT);
    $stmt2->bindParam(':number', $number, PDO::PARAM_INT);
    // SQL文の実行
    $stmt2->execute();
    // 成功した場合はコミット
    $dbh->commit();

    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '登録が完了しました。　対象学年　' . $year . '　クラス　' . $class . '　学籍番号　' . $number . '　氏名　' . $name;

} catch (PDOException $e) {
    // エラーハンドリング
    // エラーが発生した場合はロールバック（変更を全て取り消し）
    $dbh->rollBack();
    echo 'エラーが発生しました：' . $e->getMessage();
}

?>


<body>
    <br />
    <a class="" href="s_index.php">
        <h3>生徒一覧に戻る</h3>
    </a>
    <br />
</body>