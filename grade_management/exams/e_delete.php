<?php
// 選択されたテストの処理を行う
if (isset($_POST['selected_exams'])) {
    $selected_exams = $_POST['selected_exams'];
}
//var_dump($selected_exams);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="">
    <style>
    </style>
    <script src="" defer></script>
</head>

<body>
    <?php
    if (isset($selected_exams)) {
        // データベースへの接続処理（例）
        $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';

        try {
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT exams.id as exam_id, exams.japanese, exams.math, exams.english, exams.science, exams.society, exams.total, tests.id as test_id, tests.test_name, students.id as student_id, students.name
            FROM exams
            INNER JOIN tests ON exams.test_id = tests.id
            INNER JOIN students ON exams.student_id = students.id WHERE exams.id=?';

            // 選択されたテストごとに繰り返す
            foreach ($selected_exams as $selected_exam) {
                $examData = explode(',', $selected_exam);
                $id = $examData[0];

                // データベースから全ての情報を取得
                $stmt_all = $dbh->prepare($sql);
                $stmt_all->bindParam(1, $id, PDO::PARAM_INT);
                $stmt_all->execute();
                $results_all = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

                // チェックボックスの値をループして処理
                foreach ($results_all as $result) {
                    $test_name = $result['test_name'];
                    $name = $result['name'];
                    // ここで、各選択されたテストの$resultデータを処理できます
                }
            }
            // データベース接続の解放
            $dbh = null;
        } catch (PDOException $e) {
            // エラーハンドリング
            echo 'エラーが発生しました：' . $e->getMessage();
        }
        ?>

        <form action="e_destroy.php" method="post">
            <?php foreach ($selected_exams as $selected_exam) { ?>
                <?php // チェックボックスの値（ID、対象学年、テスト名）の解析
                        $examData = explode(',', $selected_exam);
                        $id = $examData[0]; ?>
                <input type="hidden" name="id[]" value="<?php echo $id ?>">
                <p>
                    <?php echo $id ?>
                    <?php echo $test_name ?>
                    <?php echo $name ?>を本当に削除しますか？
                </p>
            <?php } ?>
            <input type="submit" value="削除">
        </form>
        <a href="e_index.php">戻る</a>
    <?php } else { ?>
        <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
        <a href="e_index.php">戻る</a>
    <?php } ?>
</body>

</html>