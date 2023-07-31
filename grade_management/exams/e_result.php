<?php
// 共通ヘッダーを読み込む
include '../components/header.php';

?>
<?php
if (isset($_GET['test_id'])) {
    $test_id = $_GET['test_id'];
    //var_dump($test_id);
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
        INNER JOIN classes ON classes.class_id = students.class_id WHERE test_id=?';
        // データベースから指定テストIDに対応する成績を取得
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(1, $test_id, PDO::PARAM_INT); // ここでプレースホルダーの位置を1に設定する
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // データベース接続の解放
        $dbh = null;
    } catch (PDOException $e) {
        // エラーハンドリング
        echo 'エラーが発生しました：' . $e->getMessage();
    }
}
?>
<style>
    table,
    tr,
    th,
    td {
        border: 2px solid;
    }
</style>

<div class="bg-white rounded">
    <div class="container">
        <br />
        <?php if (isset($results) && count($results) > 0) { ?>
            <h2>テスト成績一覧</h2>
            <form method="post" action="e_ranking.php">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                <br />
                <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th>学籍番号　
                            <select name="order_by_number">
                                <option value="">選択してください</option>
                                <option value="number ASC">昇順↑</option>
                                <option value="number DESC">降順↓</option>
                            </select>
                        </th>
                        <th>受験者名</th>
                        <th>国語　
                            <select name="order_by_japanese">
                                <option value="">選択してください</option>
                                <option value="japanese ASC">下位</option>
                                <option value="japanese DESC">上位</option>
                            </select>
                        </th>
                        <th>数学　
                            <select name="order_by_math">
                                <option value="">選択してください</option>
                                <option value="math ASC">下位</option>
                                <option value="math DESC">上位</option>
                            </select>
                        </th>
                        <th>英語　
                            <select name="order_by_english">
                                <option value="">選択してください</option>
                                <option value="english ASC">下位</option>
                                <option value="english DESC">上位</option>
                            </select>
                        </th>
                        <th>理科　
                            <select name="order_by_science">
                                <option value="">選択してください</option>
                                <option value="science ASC">下位</option>
                                <option value="science DESC">上位</option>
                            </select>
                        </th>
                        <th>社会　
                            <select name="order_by_society">
                                <option value="">選択してください</option>
                                <option value="society ASC">下位</option>
                                <option value="society DESC">上位</option>
                            </select>
                        </th>
                        <th>合計　
                            <select name="order_by_total">
                                <option value="">選択してください</option>
                                <option value="total ASC">下位</option>
                                <option value="total DESC">上位</option>
                            </select>
                        </th>
                    </tr>

                    <?php foreach ($results as $row) { ?>
                        <tr>
                            <td>
                                <?php echo $row['number']; ?>
                            </td>
                            <td>
                                <?php echo $row['name']; ?>
                            </td>
                            <td>
                                <?php echo $row['japanese']; ?>
                            </td>
                            <td>
                                <?php echo $row['math']; ?>
                            </td>
                            <td>
                                <?php echo $row['english']; ?>
                            </td>
                            <td>
                                <?php echo $row['science']; ?>
                            </td>
                            <td>
                                <?php echo $row['society']; ?>
                            </td>
                            <td>
                                <?php echo $row['total']; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <input type="hidden" name="subject" value="">
                <input type="submit" value="並び替える" class="btn btn-primary">
            </form><br />
            <form method="post" action="e_ranking.php">
                <input type="hidden" name="subject" value="総合　TOP5">
                <input type="hidden" name="order_by_total" value="total DESC">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">

                <input type="hidden" name="order_by_japanese" value="">
                <input type="hidden" name="order_by_math" value="">
                <input type="hidden" name="order_by_english" value="">
                <input type="hidden" name="order_by_science" value="">
                <input type="hidden" name="order_by_society" value="">
                <input type="hidden" name="order_by_number" value="">

                <input type="submit" value="合計点ランキングTOP-5" class="btn btn-primary">
            </form><br />
            <h4>教科別TOP-5</h4><br />
            <div class="d-flex justify-content-evenly flex-wrap">
                <form method="post" action="e_ranking.php">
                    <input type="hidden" name="subject" value="国語　TOP5">
                    <input type="hidden" name="order_by_japanese" value="japanese DESC">
                    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">

                    <input type="hidden" name="order_by_total" value="">
                    <input type="hidden" name="order_by_math" value="">
                    <input type="hidden" name="order_by_english" value="">
                    <input type="hidden" name="order_by_science" value="">
                    <input type="hidden" name="order_by_society" value="">
                    <input type="hidden" name="order_by_number" value="">
                    <input type="submit" value="国語" class="btn btn-danger">
                </form><br />
                <form method="post" action="e_ranking.php">
                    <input type="hidden" name="subject" value="数学　TOP5">
                    <input type="hidden" name="order_by_math" value="math DESC">
                    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                    <input type="hidden" name="order_by_total" value="">
                    <input type="hidden" name="order_by_japanese" value="">

                    <input type="hidden" name="order_by_english" value="">
                    <input type="hidden" name="order_by_science" value="">
                    <input type="hidden" name="order_by_society" value="">
                    <input type="hidden" name="order_by_number" value="">
                    <input type="submit" value="数学" class="btn btn-primary">
                </form><br />
                <form method="post" action="e_ranking.php">
                    <input type="hidden" name="subject" value="英語　TOP5">
                    <input type="hidden" name="order_by_english" value="english DESC">
                    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                    <input type="hidden" name="order_by_total" value="">
                    <input type="hidden" name="order_by_japanese" value="">
                    <input type="hidden" name="order_by_math" value="">

                    <input type="hidden" name="order_by_science" value="">
                    <input type="hidden" name="order_by_society" value="">
                    <input type="hidden" name="order_by_number" value="">
                    <input type="submit" value="英語" class="btn btn-warning">
                </form><br />
                <form method="post" action="e_ranking.php">
                    <input type="hidden" name="subject" value="理科　TOP5">
                    <input type="hidden" name="order_by_science" value="science DESC">
                    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                    <input type="hidden" name="order_by_total" value="">
                    <input type="hidden" name="order_by_japanese" value="">
                    <input type="hidden" name="order_by_math" value="">
                    <input type="hidden" name="order_by_english" value="">

                    <input type="hidden" name="order_by_society" value="">
                    <input type="hidden" name="order_by_number" value="">
                    <input type="submit" value="理科" class="btn btn-success">
                </form><br />
                <form method="post" action="e_ranking.php">
                    <input type="hidden" name="subject" value="社会　TOP5">
                    <input type="hidden" name="order_by_society" value="society DESC">
                    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
                    <input type="hidden" name="order_by_total" value="">
                    <input type="hidden" name="order_by_japanese" value="">
                    <input type="hidden" name="order_by_math" value="">
                    <input type="hidden" name="order_by_english" value="">
                    <input type="hidden" name="order_by_science" value="">
                    <input type="hidden" name="order_by_number" value="">
                    <input type="submit" value="社会" class="btn btn-secondary">
                </form><br />
            </div>
        <?php } else { ?>
            <p>該当する成績はありません。</p>
        <?php } ?>
        <br/>
    </div>
</div>

<br />
<a href="../index.php" class="btn btn-primary">戻る</a>
<br /><br />