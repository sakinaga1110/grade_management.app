<?php
    // 共通ヘッダーを読み込む
    include 'components/header.php';
    ?>

<body>
   
    <a href="#" onclick="confirmLogout()">ログアウト</a><br/><br/>
    <?php
    // データベースへの接続処理（例）
    $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL文の準備
        $sql = 'SELECT * FROM tests';
        $stmt = $dbh->prepare($sql);

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
    
     <?php
    // 取得したデータを繰り返し処理で表示
    $currentYear = null;
    foreach ($results as $row) {
        if ($currentYear !== $row['year']) {
            if ($currentYear !== null) {
                echo '</ul>';
            }
            echo '<h3>' . $row['year'] . '年</h3>';
            echo '<ul>';
            $currentYear = $row['year'];
        }
        echo '<li><a href="exams/e_result.php?test_id=' . $row['id'] . '">' . $row['test_name'] . '</a></li>';
    }
    if ($currentYear !== null) {
        echo '</ul>';
    }
    ?>
    <br />
    <a href="tests/t_index.php">テスト一覧</a><br />

    <a href="students/s_index.php">生徒一覧</a><br />

    <a href="exams/e_index.php">成績一覧</a><br />
</body>

</html>