<?php ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
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

    <a href="t_create.php">
        <h3>新規作成</h3>
    </a>
    <br />
    <h1>テスト一覧</h1>
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
    <?php
    // 取得したデータを繰り返し処理で表示
    foreach ($results as $row) {

        ?>

        <br />
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#t_edit_<?php echo $row['id']; ?>"><?php echo $row['year'] . '年　' . $row['test_name']; ?></button>
        <br />
        <div class="modal fade" id="t_edit_<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <?php echo $row['test_name'] . 'を編集中'; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="test_form_<?php echo $row['id']; ?>" method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <p>対象学年
                                <select name="year" required>
                                    <option value="1" <?php echo $row['year'] == '1' ? ' selected' : ''; ?>>1年</option>
                                    <option value="2" <?php echo $row['year'] == '2' ? ' selected' : ''; ?>>2年</option>
                                    <option value="3" <?php echo $row['year'] == '3' ? ' selected' : ''; ?>>3年</option>
                                </select>
                            </p>
                            <p>テスト名
                                <input type="text" name="test_name" value="<?php echo $row['test_name']; ?>" required>
                            </p>
                            <div class="modal-footer">
                                <!-- ボタンのonclickイベントを変更 -->
                                <button class="btn btn-primary" name="delete_submit" type="button"
                                    data-id="<?php echo $row['id']; ?>"
                                    onclick="sendAjaxRequest('test_form_<?php echo $row['id']; ?>', 't_destroy.php')">テストを削除</button>

                                <button class="btn btn-danger" name="edit_submit" type="button"
                                    data-id="<?php echo $row['id']; ?>"
                                    onclick="sendAjaxRequest('test_form_<?php echo $row['id']; ?>', 't_update.php')">編集を保存</button>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <br />
    <a href="../index.php">トップページへ</a>

</body>

</html>