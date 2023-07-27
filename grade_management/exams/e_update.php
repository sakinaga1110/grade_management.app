<?php
    // POSTデータからテストIDの配列を取得
    $id = $_POST["id"]; // exam_idの代わりにidを取得する
    $japanese = $_POST["japanese_" . $id];
    var_dump($japanese);
    $math = $_POST["math_" . $id];
    $english = $_POST["english_" . $id];
    $science = $_POST["science_" . $id];
    $society = $_POST["society_" . $id];
    $total = $_POST["total_" . $id];
    
// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // SQL文の準備
    $sql = 'UPDATE exams SET japanese = ?, math = ?, english = ?, science = ?, society = ?, total = ? ,updated_at = NOW() WHERE id = ?';

    $stmt = $dbh->prepare($sql);

    // 値のバインド
    $stmt->bindValue(1, $japanese, PDO::PARAM_INT);
    $stmt->bindValue(2, $math, PDO::PARAM_INT);
    $stmt->bindValue(3, $english, PDO::PARAM_INT);
    $stmt->bindValue(4, $science, PDO::PARAM_INT);
    $stmt->bindValue(5, $society, PDO::PARAM_INT);
    $stmt->bindValue(6, $total, PDO::PARAM_INT);
    $stmt->bindValue(7, $id, PDO::PARAM_INT);

    // SQL文の実行
    $stmt->execute();
    // データベース接続の解放
    $dbh = null;
    // 成功時のメッセージ表示
    echo 'テストの結果が更新されました。<a class="" href="e_index.php"><h3>成績一覧に戻る</h3></a>';
} catch (PDOException $e) {
    // エラーハンドリング
    echo 'エラーが発生しました：' . $e->getMessage();
}

?>