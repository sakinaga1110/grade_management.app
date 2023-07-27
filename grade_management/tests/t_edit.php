<?php

// 選択されたテストの処理を行う
if (isset($_POST['selected_tests'])) {
    $selectedTests = $_POST['selected_tests'];
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
    <script defer>  function goBack() {
            window.history.back();
        }</script>
      
</head>

<body>
<?php
    if (isset($selectedTests)) {
        foreach ($selectedTests as $selectedTest) {
            // チェックボックスの値（ID、対象学年、テスト名）の解析
            $testData = explode(',', $selectedTest);
            $id = $testData[0];
            $year = $testData[1];
            $test_name = $testData[2];
    ?>
            <form action="t_update.php" method="post">
                <input type="hidden" name="id[]" value="<?php echo $id ?>">
                <p>id <?php echo $id ?> を編集しています。</p>
                <p>対象学年
                    <select name="year[]" required>
                        <option value="1"<?php echo $year == '1' ? ' selected' : ''; ?>>1年</option>
                        <option value="2"<?php echo $year == '2' ? ' selected' : ''; ?>>2年</option>
                        <option value="3"<?php echo $year == '3' ? ' selected' : ''; ?>>3年</option>
                    </select>
                </p>
                <p>テスト名
                    <input type="text" name="test_name[]" value="<?php echo $test_name ?>" required>
                </p>
            <?php
        }
        ?>
        <input type="submit" value="更新">
        </form>
        <button onclick="goBack()">戻る</button>
        <?php } else { ?>
        <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
        <a href="t_index.php">戻る</a>
        <?php
    }
    ?>
</body>

</html>