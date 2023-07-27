<?php
// 選択されたテストの処理を行う
if (isset($_POST['selected_exams'])) {
    // チェックボックスの値が配列として $selected_exams に格納されるようにする
    $selected_exams = $_POST['selected_exams'];
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
    <script defer>
        function goBack() {
            window.history.back();
        }

        function calculateTotal() {
            // 各科目の得点を取得
            const japaneseScore = parseFloat(document.getElementsByName('japanese[]')[0].value) || 0;
            const mathScore = parseFloat(document.getElementsByName('math[]')[0].value) || 0;
            const englishScore = parseFloat(document.getElementsByName('english[]')[0].value) || 0;
            const scienceScore = parseFloat(document.getElementsByName('science[]')[0].value) || 0;
            const societyScore = parseFloat(document.getElementsByName('society[]')[0].value) || 0;

            // 合計得点を計算して表示
            const totalScore = japaneseScore + mathScore + englishScore + scienceScore + societyScore;
            document.getElementById('total').value = totalScore;
            console.log(totalScore);
        }
    </script>
</head>

<body>
    <?php
    if (isset($selected_exams)) {
        foreach ($selected_exams as $selected_exam) {
            // チェックボックスの値（ID、対象学年、テスト名）の解析
            $examData = explode(',', $selected_exam);
            $id = $examData[0];
            $japanese = $examData[1];
            $math = $examData[2];
            $english = $examData[3];
            $science = $examData[4];
            $society = $examData[5];

            // 合計を計算
            $total = $japanese + $math + $english + $science + $society;

            echo '<form method="post" action="e_update.php">';
            echo 'ID: ' . $id . '<br>';
            echo '<input type="hidden" name="test_ids[]" value="' . $id . '">';
            echo '国語: <input type="number" min="0" max="100" value="' . $japanese . '" name="japanese[]" onchange="calculateTotal()"><br>';
            echo '数学: <input type="number" min="0" max="100" value="' . $math . '" name="math[]" onchange="calculateTotal()"><br>';
            echo '英語: <input type="number" min="0" max="100" value="' . $english . '" name="english[]" onchange="calculateTotal()"><br>';
            echo '理科: <input type="number" min="0" max="100" value="' . $science . '" name="science[]" onchange="calculateTotal()"><br>';
            echo '社会: <input type="number" min="0" max="100" value="' . $society . '" name="society[]" onchange="calculateTotal()"><br>';
            echo '合計: <input type="number" min="0" max="500" value="' . $total . '" name="total[]" id="total" readonly><br><br>';
            echo '<input type="submit" value="テストの結果を更新する">';
            echo '</form>';
            echo '<br>';
        }

        ?>
        <?php
    } else { ?>
        <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
        <button onclick="goBack()">戻る</button>
        <?php
    }
    ?>

</body>

</html>