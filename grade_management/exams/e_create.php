<?php
// データベースへの接続処理（テスト名を取得）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'SELECT * FROM tests';
    $stmt = $dbh->prepare($sql);

    // SQL文の実行
    $stmt->execute();

    // 結果の取得
    $test_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // データベース接続の解放
    $dbh = null;

} catch (PDOException $e) {
    // エラーハンドリング
    echo 'エラーが発生しました：' . $e->getMessage();
}

// データベースへの接続処理(生徒名を取得)
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'SELECT * FROM classes INNER JOIN students ON classes.class_id = students.class_id';


    $stmt = $dbh->prepare($sql);

    // SQL文の実行
    $stmt->execute();

    // 結果の取得
    $classes_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // データベース接続の解放
    $dbh = null;

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
    <script>  function calculateTotal() {
            // 各科目の得点を取得
            const japaneseScore = parseFloat(document.getElementsByName('japanese')[0].value) || 0;
            const mathScore = parseFloat(document.getElementsByName('math')[0].value) || 0;
            const englishScore = parseFloat(document.getElementsByName('english')[0].value) || 0;
            const scienceScore = parseFloat(document.getElementsByName('science')[0].value) || 0;
            const societyScore = parseFloat(document.getElementsByName('society')[0].value) || 0;

            // 合計得点を計算して表示
            const totalScore = japaneseScore + mathScore + englishScore + scienceScore + societyScore;
            document.getElementById('total').value = totalScore;
            console.log(totalScore);
        }</script>
</head>

<body>
    <form method="post" action="e_save.php">
        <select name="test_id">
            <?php
            foreach ($test_results as $test) {
                echo '<option value="' . $test['id'] . '">' . $test['year'] . '年' . $test['test_name'] . '</option>';
            }
            ?>
        </select>
        <select name="class_id">
            <?php
            foreach ($classes_results as $class) {
                echo '<option value="' . $class['class_id'] . ','.$class['year'].' ">' . $class['number'] . ' ' . $class['name'] . '</option>';
            }
            ?>
        </select>
        
        国語
        <input type="number" min="0" max="100" name="japanese" onchange="calculateTotal()">
        数学
        <input type="number" min="0" max="100" name="math" onchange="calculateTotal()">
        英語
        <input type="number" min="0" max="100" name="english" onchange="calculateTotal()">
        理科
        <input type="number" min="0" max="100" name="science" onchange="calculateTotal()">
        社会
        <input type="number" min="0" max="100" name="society" onchange="calculateTotal()">
        合計
        <input type="number" min="0" max="500" name="total" id="total" readonly>
        <input type="submit" value="テストの結果を登録する">
    </form>
<a href="e_index.php">成績一覧に戻る</a>
</body>

</html>