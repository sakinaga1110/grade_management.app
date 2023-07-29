<?php
require_once('../components/header.php');
// 各教科の点数を保持する配列
$japaneseScores = [];
$mathScores = [];
$englishScores = [];
$scienceScores = [];
$societyScores = [];
// 各教科のテスト名を保持する配列
$testNames = [];
$number = $_POST['number'];
$name=$_POST['name'];
// var_dump($_POST);
// var_dump($number);
// 
// データベースへの接続処理
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    if (isset($number)) {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // var_dump($number);
        // SQL文の準備
        $sql =
            'SELECT exams.id as exam_id, exams.japanese, exams.math, exams.english, exams.science, exams.society, exams.total, students.number, tests.id as test_id, tests.test_name, students.id as student_id, classes.name
                FROM exams
                INNER JOIN students ON exams.student_id = students.id
                INNER JOIN tests ON exams.test_id = tests.id
                INNER JOIN classes ON classes.class_id = students.class_id WHERE students.number = ?';
        $stmt = $dbh->prepare($sql);

        // 生徒の情報を取得するための値をバインド
        $stmt->bindValue(1, $number, PDO::PARAM_INT);

        // SQL文の実行
        $stmt->execute();

        // クエリが結果を返したかどうかをチェック
        if ($stmt->rowCount() > 0) {
            // 結果の取得
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $japaneseScores[] = $row['japanese'];
                $mathScores[] = $row['math'];
                $englishScores[] = $row['english'];
                $scienceScores[] = $row['science'];
                $societyScores[] = $row['society'];
                // テスト名を配列に追加
                $testNames[] = $row['test_name'];
            }
            // データベース接続の解放
            $dbh = null;
        } else {
            // 結果が見つからない場合の処理
            // 例えば、エラーメッセージを表示したり、別のページにリダイレクトしたりします。
            echo "指定した学籍番号に対する結果が見つかりませんでした。";
        }
    }


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
    <style>
    </style>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script defer>
        // DOMが完全に読み込まれた後に処理を実行する
        window.onload = function () {
            // Canvas要素が存在するか確認
            const canvas = document.getElementById('myChart');
            if (!canvas) {
                console.error('Canvas要素が見つかりません。');
                return;
            }

            // チャートの処理を記述する
            const ctx = canvas.getContext('2d');
            const testNames = <?php echo json_encode($testNames); ?>; // テスト名を配列に設定
            const japaneseScores = <?php echo json_encode($japaneseScores); ?>;
            const mathScores = <?php echo json_encode($mathScores); ?>;
            const englishScores = <?php echo json_encode($englishScores); ?>;
            const scienceScores = <?php echo json_encode($scienceScores); ?>;
            const societyScores = <?php echo json_encode($societyScores); ?>;

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: testNames, // テスト名をX軸のラベルに設定
                    datasets: [
                        {
                            label: '国語', // データセットのラベル
                            data: japaneseScores, // 国語の成績データを設定
                            backgroundColor: 'rgba(255, 99, 132, 0.7)', // 棒グラフの色
                        },
                        {
                            label: '数学', // データセットのラベル
                            data: mathScores, // 数学の成績データを設定
                            backgroundColor: 'rgba(54, 162, 235, 0.7)', // 棒グラフの色
                        },
                        {
                            label: '英語', // データセットのラベル
                            data: englishScores, // 英語の成績データを設定
                            backgroundColor: 'rgba(255, 206, 86, 0.7)', // 棒グラフの色
                        },
                        {
                            label: '理科', // データセットのラベル
                            data: scienceScores, // 理科の成績データを設定
                            backgroundColor: 'rgba(75, 192, 192, 0.7)', // 棒グラフの色
                        },
                        {
                            label: '社会', // データセットのラベル
                            data: societyScores, // 社会の成績データを設定
                            backgroundColor: 'rgba(153, 102, 255, 0.7)', // 棒グラフの色
                        },
                    ]
                },
                options: {
                    // オプションの設定
                    scales: {
                        x: {
                            stacked: true, // X軸を積み上げ
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            suggestedMax: 500, // Y軸の最大値を500に設定
                        },
                    }
                }
            });

            // 折れ線グラフの処理を記述する
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: testNames, // テスト名をX軸のラベルに設定
                    datasets: [
                        {
                            label: '国語', // データセットのラベル
                            data: japaneseScores, // 国語の成績データを設定
                            borderColor: 'rgba(255, 99, 132, 1)', // 折れ線グラフの色
                            fill: false, // 線を塗りつぶさない
                        },
                        {
                            label: '数学', // データセットのラベル
                            data: mathScores, // 数学の成績データを設定
                            borderColor: 'rgba(54, 162, 235, 1)', // 折れ線グラフの色
                            fill: false, // 線を塗りつぶさない
                        },
                        {
                            label: '英語', // データセットのラベル
                            data: englishScores, // 英語の成績データを設定
                            borderColor: 'rgba(255, 206, 86, 1)', // 折れ線グラフの色
                            fill: false, // 線を塗りつぶさない
                        },
                        {
                            label: '理科', // データセットのラベル
                            data: scienceScores, // 理科の成績データを設定
                            borderColor: 'rgba(75, 192, 192, 1)', // 折れ線グラフの色
                            fill: false, // 線を塗りつぶさない
                        },
                        {
                            label: '社会', // データセットのラベル
                            data: societyScores, // 社会の成績データを設定
                            borderColor: 'rgba(153, 102, 255, 1)', // 折れ線グラフの色
                            fill: false, // 線を塗りつぶさない
                        },
                    ]
                },
                options: {
                    // オプションの設定
                    scales: {
                        x: {
                            beginAtZero: true, // X軸を0から始める
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: 100, // Y軸の最大値を100に設定
                        }
                    }
                }
            });
        };

        function goBack() {
            window.history.back();
        }


    </script>
</head>

<body>
    <style>
        #myChart,
        #lineChart {
            max-width: 600px;
            max-height: 400px;
            width: 100%;
            height: auto;
        }
    </style>
    <canvas id="myChart"></canvas>
    <canvas id="lineChart"></canvas>
    <table>
        <tr>
            <th>テスト名</th>
            <th>学籍番号　</th>
            <th>受験者名</th>
            <th>国語　</th>
            <th>数学　</th>
            <th>英語　</th>
            <th>理科　</th>
            <th>社会　</th>
            <th>合計　</th>
        </tr>

        <?php
        foreach ($results as $row) {
            ?>
            <tr>
                <td>
                    <?php echo $row['test_name']; ?>
                </td>
                <td>
                    <?php echo $number; ?>
                </td>
                <td>
                    <?php echo $name; ?>
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
            <?php
        }
        ?>
    </table>

    <a href="s_index.php">戻る</a>

</body>

</html>