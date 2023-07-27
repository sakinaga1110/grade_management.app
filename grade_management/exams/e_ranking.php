<?php
$subject=$_POST['subject'];
if (isset($_POST['test_id'])) {
    $test_id = $_POST['test_id'];
    //var_dump($test_id);
    // 各科目の並び替え順を取得

    $order_by_number = $_POST['order_by_number'];
    $order_by_japanese = $_POST['order_by_japanese'];
    $order_by_math = $_POST['order_by_math'];
    $order_by_english = $_POST['order_by_english'];
    $order_by_science = $_POST['order_by_science'];
    $order_by_society = $_POST['order_by_society'];
    $order_by_total = $_POST['order_by_total'];

    $order_by = ''; // ここで $order_by 変数を空に初期化する
    // 各科目に対応する並び替え順を選択
    if ($order_by_number !== '') {
        $order_by = 'students. ' . $order_by_number;
    } elseif ($order_by_japanese !== '') {
        $order_by = 'exams. ' . $order_by_japanese;
    } elseif ($order_by_math !== '') {
        $order_by = 'exams. ' . $order_by_math;
    } elseif ($order_by_english !== '') {
        $order_by = 'exams. ' . $order_by_english;
    } elseif ($order_by_science !== '') {
        $order_by = 'exams. ' . $order_by_science;
    } elseif ($order_by_society !== '') {
        $order_by = 'exams. ' . $order_by_society;
    } elseif ($order_by_total !== '') {
        $order_by = 'exams. ' . $order_by_total;
    } else {
        $order_by = 'students.number ASC';
    }
    // var_dump($order_by);
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

        // ランキング表示の場合のみ成績を5個までに制限
        if (!empty($_POST['subject'])) {
            // 成績を5個までに制限
            $results = array_slice($results, 0, 5);

            // 上位3人の名前を大きくするためのカウンター
            $topThreeCounter = 0;
            // ランキング表示の場合のスタイルを設定
            $icon_classes = [
                1 => 'fa-beat color-red',
                // 1位は"fa-beat" (赤色のアイコン)
                2 => 'fa-beat color-blue',
                // 2位は"fa-beat" (青色のアイコン)
                3 => 'fa-beat color-green',
                // 3位は"fa-beat" (緑色のアイコン)
                4 => 'color-orange',
                // 4位は"fa-beat" (黄色のアイコン)
                5 => 'color-purple', // 5位は"fa-beat" (紫色のアイコン)
            ];


        } else {
            // 通常の表示の場合のスタイルを設定
            $icon_classes = [];
           
        }
        // データベース接続の解放
        $dbh = null;
    } catch (PDOException $e) {
        // エラーハンドリング
        echo 'エラーが発生しました：' . $e->getMessage();
    }

}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>テスト成績一覧</title>
    <link rel="stylesheet" href="">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Center the icons */
        td i {
            display: block;
            margin: 0 auto;
        }

        .color-red {
            color: red;
        }

        .color-blue {
            color: blue;
        }

        .color-green {
            color: green;
        }

        .color-orange {
            color: orange;
        }

        .color-purple {
            color: purple;
        }
    </style>
    <script src="https://kit.fontawesome.com/d303258a45.js" crossorigin="anonymous"></script>

<body>
    <?php if (isset($results) && count($results) > 0) { ?>
        <h2>テスト成績一覧</h2><br />
        <h3>
            <?php echo $subject; ?>
        </h3>
        <form method="post" action="e_download.php">
            <input type="hidden" name="subject" value="<?php echo$subject; ?>">
            <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
           
            <input type="hidden" name="order_by" value="<?php echo$order_by; ?>">
            <table>
                <tr>
                    <th>順番</th>
                    <th>学籍番号
                    </th>
                    <th>名前
                    </th>
                    <th>国語
                    </th>
                    <th>数学
                    </th>
                    <th>英語
                    </th>
                    <th>理科
                    </th>
                    <th>社会
                    </th>
                    <th>合計
                    </th>
                </tr>
                <?php foreach ($results as $index => $row) {
                    // Determine the class based on index
                    $icon_class = isset($icon_classes[$index + 1]) ? $icon_classes[$index + 1] : '';
                    echo '<tr>';
                    echo '<td><i class="fa-solid fa-' . ($index + 1) . ' ' . $icon_class . '"></i></td>';
                    echo '<td>' . $row['number'] . '</td>';
                    echo '<td>';

                    // 上位3人の名前を大きく太字にして下線を追加する
                    if (!empty($_POST['subject']) && $index < 3) {
                        echo '<strong style="font-size: 30px; text-decoration: underline;">' . strtoupper($row['name']) . '</strong>';
                    } else {
                        echo $row['name'];
                    }

                    echo '</td>';
                    echo '<td>' . $row['japanese'] . '</td>';
                    echo '<td>' . $row['math'] . '</td>';
                    echo '<td>' . $row['english'] . '</td>';
                    echo '<td>' . $row['science'] . '</td>';
                    echo '<td>' . $row['society'] . '</td>';
                    echo '<td>' . $row['total'] . '</td>';
                    echo '</tr>';
                } ?>

            </table>
   
            <input type="submit" value="csvダウンロード">
        </form>
        <br />
    <?php } else {
        echo '該当する成績はありません。';
    } ?>
    <br />
    <br />
    <a href="e_index.php">戻る</a>
</body>

</html>