<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $number = isset($_POST['number']) ? $_POST['number'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';
    // データベースへの接続処理
    $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL文の準備
        $sql = 'SELECT * FROM classes INNER JOIN students ON classes.class_id = students.class_id WHERE 1';

        // 名前での検索
        if (!empty($name)) {
            $sql .= ' AND classes.name LIKE :name';
        }

        // 学籍番号での検索
        if (!empty($number)) {
            $sql .= ' AND students.number = :number';
        }

        // 学年・クラスでの検索
        if (!empty($year) && !empty($class)) {
            $sql .= ' AND classes.year = :year AND students.class = :class';
        }
        $stmt = $dbh->prepare($sql);

        // バインド変数の設定
        if (!empty($name)) {
            $stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
        }
        if (!empty($number)) {
            $stmt->bindValue(':number', $number, PDO::PARAM_INT);
        }

        if (!empty($year) && !empty($class)) {
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->bindValue(':class', $class, PDO::PARAM_INT);
        }

        // SQL文の実行
        $stmt->execute();

        // 結果の取得
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($results);
        // データベース接続の解放
        $dbh = null;

    } catch (PDOException $e) {
        // エラーハンドリング
        echo 'エラーが発生しました：' . $e->getMessage();
    }
}
?>

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
                    const modalElement = form.closest('.modal');
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

        document.addEventListener('DOMContentLoaded', () => {
            <?php foreach ($results as $row) { ?>
                // Attach onclick event handlers to the "削除" and "編集を保存" buttons
                const deleteButton<?php echo $row['id']; ?> = document.getElementById('delete_button_<?php echo $row['id']; ?>');
                deleteButton<?php echo $row['id']; ?>.onclick = function () {
                    sendAjaxRequest('students_form_<?php echo $row['id']; ?>', 's_destroy.php');
                };

                const editButton<?php echo $row['id']; ?> = document.getElementById('edit_button_<?php echo $row['id']; ?>');
                editButton<?php echo $row['id']; ?>.onclick = function () {
                    sendAjaxRequest('students_form_<?php echo $row['id']; ?>', 's_update.php');
                };
            <?php } ?>
        });
    </script>

</head>

<body>


    <?php
    // 取得したデータを表示
    if (!empty($results)) {
        foreach ($results as $row) {

            ?>

            <br />
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#s_edit_<?php echo $row['id']; ?>"><?php echo $row['year'] . ' 年' . $row['class'] . '組　学籍番号' . $row['number'] . '　名前　' . $row['name'] . ''; ?></button>
            <br />
            <div class="modal fade" id="s_edit_<?php echo $row['id']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <?php echo $row['number'] . '　' . $row['name'] . 'を編集中'; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="students_form_<?php echo $row['id']; ?>" method="post" action="s_score.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <p>対象学年
                                    <select name="year" required>
                                        <option value="1" <?php echo $row['year'] == '1' ? ' selected' : ''; ?>>1年</option>
                                        <option value="2" <?php echo $row['year'] == '2' ? ' selected' : ''; ?>>2年</option>
                                        <option value="3" <?php echo $row['year'] == '3' ? ' selected' : ''; ?>>3年</option>
                                    </select>
                                </p>
                                <p>
                                    組
                                    <select name="class" required>
                                        <option value="1" <?php echo $row['class'] == '1' ? ' selected' : ''; ?>>1組</option>
                                        <option value="2" <?php echo $row['class'] == '2' ? ' selected' : ''; ?>>2組</option>
                                        <option value="3" <?php echo $row['class'] == '3' ? ' selected' : ''; ?>>3組</option>
                                        <option value="4" <?php echo $row['class'] == '4' ? ' selected' : ''; ?>>4組</option>
                                        <option value="5" <?php echo $row['class'] == '5' ? ' selected' : ''; ?>>5組</option>

                                    </select>
                                </p>
                                <p>学籍番号
                                    <input type="number" name="number" value=<?php echo $row['number']; ?> required>
                                </p>
                                <P>名前
                                    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>

                                </P>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" name="score_submit" type="submit">成績を表示</button>

                                    <!-- Add an id attribute for "削除" and "編集を保存" buttons -->
                                    <button id="delete_button_<?php echo $row['id']; ?>" class="btn btn-primary"
                                        name="delete_submit" type="button" data-id="<?php echo $row['id']; ?>">テストを削除</button>
                                    <button id="edit_button_<?php echo $row['id']; ?>" class="btn btn-danger" name="edit_submit"
                                        type="button" data-id="<?php echo $row['id']; ?>">編集を保存</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '該当する生徒情報はありません。';
    }
    ?>

    <a href="s_index.php">クラス一覧へ戻る</a>
    </form>
</body>

</html>