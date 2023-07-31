<?php
// 共通ヘッダーを読み込む
include 'components/header.php';

?>

<body>

    <?php
    // データベースへの接続処理（例）
    $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 役職によって条件を追加
        $sql = 'SELECT * FROM tests ';
        if ($role === 'chief' || $role === 'general') {
            // 教師または一般教員の場合の条件（年のみ指定）
            $sql .= 'WHERE year = :year';
        }
        // SQL文の準備
        $stmt = $dbh->prepare($sql);

        // パラメータのバインド
        if ($role === 'chief' || $role === 'general') {
            $stmt->bindParam(':year', $teacher_year, PDO::PARAM_INT);
            // $roleが'general'の場合、さらに'class_id'もバインドする
        }

        // SQL文の準備
        $stmt = $dbh->prepare($sql);

        // パラメータのバインド
        if ($role === 'chief' || $role === 'general') {
            $stmt->bindParam(':year', $teacher_year, PDO::PARAM_INT);
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
    <div class="d-flex justify-content-evenly bg-white rounded">
        <?php
        // 取得したデータを繰り返し処理で表示
        $currentYear = null;
        foreach ($results as $row) {
            if ($currentYear !== $row['year']) {
                if ($currentYear !== null) {
                    echo '</ul>';
                }
                echo '<ul class="">';
                echo '<h3>' . $row['year'] . '年</h3>';

                $currentYear = $row['year'];
            }
            echo '<div class="h3"><li><a href="exams/e_result.php?test_id=' . $row['id'] . '">' . $row['test_name'] . '</a></li></div><br/>';
        }
        if ($currentYear !== null) {
            echo '</ul>';
        }
        ?>
    </div>
    <br />
    <div class="d-flex justify-content-evenly">
        <br />
        <a href="tests/t_index.php" button class="btn btn-primary"><h3>テスト一覧</h3></a><br />

        <a href="students/s_index.php" button class="btn btn-primary"><h3>生徒一覧</h3></a><br />

        <a href="exams/e_index.php" button class="btn btn-primary"><h3>成績一覧</h3></a><br />
    </div>
    <br />
    <br/>
