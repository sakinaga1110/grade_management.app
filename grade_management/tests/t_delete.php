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
    <script src="" defer></script>
</head>

<body><body>
<?php
    if (isset($selectedTests)) {
        foreach ($selectedTests as $selectedTest) {
            // チェックボックスの値（ID、対象学年、テスト名）の解析
            $testData = explode(',', $selectedTest);
            $id = $testData[0];
            $year = $testData[1];
            $test_name = $testData[2];
    ?>
            <form action="t_destroy.php" method="post">
                <input type="hidden" name="id[]" value="<?php echo $id ?>">
                <p><?php echo $test_name ?> を本当に削除しますか？</p>
                <p>対象学年　<?php echo $year?></p>
                <p>テスト名　<?php echo $test_name ?>
                </p>
            <?php
        }
        ?>
        <input type="submit" value="削除">
        </form>
        <a href="t_index.php">戻る</a>
        <?php } else { ?>
        <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
        <a href="t_index.php">戻る</a>
        <?php
    }
    ?>
</body>

</body>

</html>