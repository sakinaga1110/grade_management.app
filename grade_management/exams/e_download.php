<?php
$subject = $_POST['subject'];
if (isset($_POST['test_id'])) {
    $test_id = $_POST['test_id'];
    //var_dump($test_id);
    // 各科目の並び替え順を取得
    $order_by = $_POST['order_by'];
    //var_dump($order_by);
    // var_dump($_POST);
    // データベースへの接続処理（例）
    $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT exams.id as exam_id, exams.japanese, exams.math, exams.english, exams.science, exams.society, exams.total, students.number, tests.id as test_id, tests.test_name, students.id as student_id, classes.name
        FROM exams
        INNER JOIN students ON exams.student_id = students.id
        INNER JOIN tests ON exams.test_id = tests.id
        INNER JOIN classes ON classes.class_id = students.class_id
        WHERE test_id = ?';
        //var_dump($sql);
        $sql .= ' ORDER BY ' . $order_by;

        //var_dump($sql);
        // データベースから指定テストIDに対応する成績を取得
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $test_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // データベース接続の解放
        $dbh = null;

        // CSVファイルを生成
        $csv_filename = 'test_results.csv';
        $csv_handler = fopen($csv_filename, 'w');

        // BOM付きUTF-8でファイルを開く
        fputs($csv_handler, "\xEF\xBB\xBF"); // BOM (Byte Order Mark)

        $csv_title = $subject;
        // CSVファイルにタイトル行を追加
        $csv_header = array('学籍番号', '受験者名', '国語', '数学', '英語', '理科', '社会', '合計');
        fputcsv($csv_handler, array($csv_title));
        fputcsv($csv_handler, $csv_header);

        foreach ($results as $row) {
            $csv_row = array($row['number'], $row['name'], $row['japanese'], $row['math'], $row['english'], $row['science'], $row['society'], $row['total']);
            fputcsv($csv_handler, $csv_row);
        }


        fclose($csv_handler);

        // ダウンロードさせる
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $csv_filename);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($csv_filename));
        readfile($csv_filename);

        // CSVファイルを削除（必要な場合は残してもよい）
        unlink($csv_filename);
    } catch (PDOException $e) {
        // エラーハンドリング
        echo 'エラーが発生しました：' . $e->getMessage();
    }
} else {
    die('テストIDが取得できませんでした。');
}
?>