<?php

// 選択されたテストの処理を行う
if (isset($_POST['selected_students'])) {
    $selected_students = $_POST['selected_students'];
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
    <script src="" defer> function goBack() {
            window.history.back();
        }</script>
</head>

<body>

    <body>
        <?php
        if (isset($selected_students)) {
            foreach ($selected_students as $selected_student) {
                // チェックボックスの値（ID、対象学年、テスト名）の解析
                $studentData = explode(',', $selected_student);
                $id = $studentData[0];
                $year = $studentData[1];
                $class = $studentData[2];
                $number = $studentData[3];
                $name = $studentData[4];
                ?>
                <form action="s_update.php" method="post">
                    <input type="hidden" name="id[]" value="<?php echo $id ?>">
                    id
                    <?php echo $id ?> を編集しています。

                    所属学年
                    <select name="year[]" required>
                        <option value="1" <?php echo $year == '1' ? 'selected' : ''; ?>>1年</option>
                        <option value="2" <?php echo $year == '2' ? 'selected' : ''; ?>>2年</option>
                        <option value="3" <?php echo $year == '3' ? 'selected' : ''; ?>>3年</option>
                    </select>

                    所属クラス
                    <select name="class[]" required>
                        <option value="1" <?php echo $class == '1' ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo $class == '2' ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo $class == '3' ? 'selected' : ''; ?>>3</option>
                        <option value="4" <?php echo $class == '4' ? 'selected' : ''; ?>>4</option>
                        <option value="5" <?php echo $class == '5' ? 'selected' : ''; ?>>5</option>
                        <option value="6" <?php echo $class == '6' ? 'selected' : ''; ?>>6</option>
                        <option value="7" <?php echo $class == '7' ? 'selected' : ''; ?>>7</option>
                        <option value="8" <?php echo $class == '8' ? 'selected' : ''; ?>>8</option>
                        <option value="9" <?php echo $class == '9' ? 'selected' : ''; ?>>9</option>
                    </select>

                    学籍番号　※数字のみ
                    <input type="number" name="number[]" value="<?php echo $number ?>" required>
                    <br />
                    氏名
                    <input type="text" name="name[]" value="<?php echo $name ?>" required>
                    <br />
                    <?php
            }
            ?>
            <input type="submit" value="更新"><br />
            <button onclick="goBack()">戻る</button>
        </form>
        <?php } else { ?>
            <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
            <button onclick="goBack()">戻る</button>
            <?php
        }
        ?>
    </body>

</body>

</html>