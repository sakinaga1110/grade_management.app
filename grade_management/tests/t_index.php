<?php
// 共通ヘッダーを読み込む
include '../components/header.php';
?>
<?php

// アクセス権限がない場合はエラーメッセージを表示して戻るボタンを表示
if ($role !== 'principal') {
    echo '権限がありません。<br />';
    echo '<a href="javascript:history.back()">戻る</a>';
    exit;
}
?>
<?php
// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文の準備
    $sql = 'SELECT * FROM tests ORDER BY year , id';
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

<head>
    <script>
        function sendAjaxRequest(formId, action) {
            const form = document.getElementById(formId);
            const formData = new FormData(form);

            fetch(action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    // 成功時の処理
                    console.log(data); // レスポンスデータをコンソールに表示
                    const modalElement = document.getElementById(formId).closest('.modal');
                    const bootstrapModal = new bootstrap.Modal(modalElement);
                    bootstrapModal.hide(); // モーダルを閉じる
                    alert('操作が完了しました。'); // アラートを表示
                    location.reload(); // ページをリロード
                })
                .catch(error => {
                    // エラー時の処理
                    console.error('Error:', error);
                    alert('通信エラーが発生しました。もう一度試してください。'); // アラートを表示
                });
        }


    </script>
</head>

<body>
    <div class="d-flex justify-content-evenly bg-white rounded">
        <a href="t_create.php">
            <h3>新規作成</h3>
        </a>
    </div>

    <br />
    <div class="d-flex bg-white justify-content-evenly">
        <?php
        $grouped_results = array();

        // 取得したデータを学年ごとにグループ化
        foreach ($results as $row) {
            $year = $row['year'];
            if (!isset($grouped_results[$year])) {
                $grouped_results[$year] = array();
            }
            $grouped_results[$year][] = $row;
        }
        // グループごとに学年を横に表示
        foreach ($grouped_results as $year => $tests) {
            echo '<div class="d-flex flex-column mx-2">';
            echo '<h2>' . $year . '年</h2>';
            foreach ($tests as $row) {
                echo '<button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#t_edit_' . $row['id'] . '">' . $row['test_name'] . '</button>';
                // モーダルの内容を表示
                echo '<div class="modal fade" id="t_edit_' . $row['id'] . '">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo $row['test_name'] . 'を編集中';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<form id="test_form_' . $row['id'] . '" method="post" action="">';
                echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                echo '<p>対象学年';
                echo '<select name="year" required>';
                echo '<option value="1" ' . ($row['year'] == '1' ? ' selected' : '') . '>1年</option>';
                echo '<option value="2" ' . ($row['year'] == '2' ? ' selected' : '') . '>2年</option>';
                echo '<option value="3" ' . ($row['year'] == '3' ? ' selected' : '') . '>3年</option>';
                echo '</select>';
                echo '</p>';
                echo '<p>テスト名';
                echo '<input type="text" name="test_name" value="' . $row['test_name'] . '" required>';
                echo '</p>';
                echo '<div class="modal-footer">';
                echo '<!-- ボタンのonclickイベントを変更 -->';
                echo '<button class="btn btn-primary" name="delete_submit" type="button" data-id="' . $row['id'] . '" onclick="sendAjaxRequest(\'test_form_' . $row['id'] . '\', \'t_destroy.php\')">テストを削除</button>';
                echo '<button class="btn btn-danger" name="edit_submit" type="button" data-id="' . $row['id'] . '" onclick="sendAjaxRequest(\'test_form_' . $row['id'] . '\', \'t_update.php\')">編集を保存</button>';
                echo '</div>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <br />
    <a href="../index.php" button class="btn btn-primary">トップページへ</a>
    <br />
    <br/>