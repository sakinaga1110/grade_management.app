<?php
// 共通ヘッダーを読み込む
include '../components/header.php';

?>
<?php
// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';


try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 役職によって条件を追加
    $sql = 'SELECT * FROM classes INNER JOIN students ON classes.class_id = students.class_id ';
    if ($role === 'chief') {
        // 学年主任の場合の条件（年のみ指定）
        $sql .= ' WHERE year = :year ORDER BY class ASC';
    }
elseif($role==='general'){
    $sql .= ' WHERE year = :year AND class =  :class';
}
elseif($role==='principal'){
    $sql.='ORDER BY year,class ASC';
}
    // SQL文の準備
    $stmt = $dbh->prepare($sql);

    // パラメータのバインド
    if ($role === 'chief') {
        $stmt->bindParam(':year', $teacher_year, PDO::PARAM_INT);
    }
    elseif($role==='general'){ $stmt->bindParam(':year', $teacher_year, PDO::PARAM_INT);
        $stmt->bindParam(':class', $teacher_class_id, PDO::PARAM_INT);
    }

    // SQL文の実行
    $stmt->execute();

   

    // 結果の取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <script>
    // src="" defer>
    </script>
    <style>
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Hover effect */
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>クラス一覧</h1>
    <a href="s_created.php">
        <h3>生徒を登録する</h3>
    </a>
    <form id="student_search_form" method="post" action="class_list.php">
        <label for="search_name">生徒名:</label>
        <input type="text" id="search_name" name="name" placeholder="生徒名を入力">

        <label for="search_number">学籍番号:</label>
        <input type="text" id="search_number" name="number" placeholder="学籍番号を入力">

        <input type="submit" value="検索">
    </form>
    <br />
    <br />
    <?php
    echo 'クラス名簿一覧';
    $uniqueResults = array();

    // 取得したデータを繰り返し処理で表示
    foreach ($results as $row) {
        $key = $row['year'] . '-' . $row['class'];
        if (!isset($uniqueResults[$key])) {
            $uniqueResults[$key] = $row;
            echo '<label>';
            echo '<form method="post" name="class_form" action="class_list.php">';
            echo '<input type="hidden" name="year" value="' . $row['year'] . '">';
            echo '<input type="hidden" name="class" value="' . $row['class'] . '">';
            echo '<input type="submit" value="' . $row['year'] . ' - ' . $row['class'] . '"><br>';
            echo '</form>';
            echo '</label>';
            echo '<br/>';
        }
    }
    ?>


    <a href="../index.php">トップページへ</a>

</body>

</html>