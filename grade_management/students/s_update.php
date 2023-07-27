<?php
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    } else {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

$post = $_POST;
$post = sanitize($post);
$year = $post['year'];
$name = $post['name'];
$id = $post['id'];
$class = $post['class'];
$number = $post['number'];

// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクションを開始
    $dbh->beginTransaction();
    // SQL文の準備（studentsテーブルに対してのUPDATE処理）
    $sql1 = 'UPDATE students SET class = ?, number = ?, updated_at = NOW() WHERE id = ?';
    $stmt1 = $dbh->prepare($sql1);

    // 値のバインド
    $stmt1->bindValue(1, htmlspecialchars($class, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);
    $stmt1->bindValue(2, htmlspecialchars($number, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);
    $stmt1->bindValue(3, $id, PDO::PARAM_INT);
    // SQL文の実行
    $stmt1->execute();

    // SQL文の準備（classesテーブルに対してのUPDATE処理）
    $sql2 = 'UPDATE classes SET year = ?, name = ? WHERE class_id = ?';
    $stmt2 = $dbh->prepare($sql2);

    // 値のバインド
    $stmt2->bindValue(1, htmlspecialchars($year, ENT_QUOTES, 'UTF-8'), PDO::PARAM_INT);
    $stmt2->bindValue(2, htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
    $stmt2->bindValue(3, $id, PDO::PARAM_INT);
    // SQL文の実行
    $stmt2->execute();
    // すべての更新が成功した場合はコミット
    $dbh->commit();

    // データベース接続の解放
    $dbh = null;

    // 成功時のメッセージ表示
    echo '更新が完了しました。';

    // 更新した学生情報の表示
    echo 'ID: ' . $id . ',　学年: ' . $year . ',　クラス: ' . $class . ',　学籍番号: ' . $number . ',　 氏名: ' . $name . '';

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
        <h3>生徒一覧に戻る</h3>
    </a>
    <br />
</body>

</html>