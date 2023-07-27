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
    <script defer> 
            function goBack() {
                window.history.back();
        }
    </script>
    </script>
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
                <form action="s_destroy.php" method="post">
                    <input type="hidden" name="id[]" value="<?php echo $id ?>">
                    <p>
                        <?php echo $name ?> をリストから本当に削除しますか？
                    </p>
                    <p>対象学年　
                        <?php echo $year ?>
                    </p>
                    クラス　
                    <?php echo $class ?>
                    学籍番号　
                    <?php echo $number ?>
                    <p>氏名　
                        <?php echo $name ?>
                    </p>
                    <?php echo '　';
            }
            ?>
            <input type="submit" value="削除">
        </form>
        <br /><button onclick="goBack()">戻る</button>
        <?php } else { ?>
            <p>チェックなしでポストされました。戻るボタンをクリックしてください。</p>
            <a href="class_list.php">戻る</a>
            <?php
        }
        ?>
    </body>

</body>

</html>