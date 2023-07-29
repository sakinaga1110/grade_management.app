<?php
require_once('../components/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $number = isset($_POST['number']) ? $_POST['number'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';
    // var_dump($_POST);
    // var_dump($role);

    // データベースへの接続処理
    $dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQLクエリのベース部分
        $sql = 'SELECT * FROM classes INNER JOIN students ON classes.class_id = students.class_id WHERE 1';

        // プレースホルダと値の配列を用意
        $bindValues = array();

        // 役職に応じた検索条件を追加
        if ($role === 'chief') {
            if (!empty($name) && !empty($number)) {
                $sql .= ' AND classes.year = :year AND classes.name LIKE :name AND students.number = :number';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':name'] = '%' . $name . '%';
                $bindValues[':number'] = $number;
                $sql .= ' ORDER BY class,number ASC';
            } elseif (!empty($name)) {
                $sql .= ' AND classes.year = :year AND classes.name LIKE :name';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':name'] = '%' . $name . '%';
                $sql .= ' ORDER BY class,number ASC';
            } elseif (!empty($number)) {
                $sql .= ' AND classes.year = :year AND students.number = :number';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':number'] = $number;
                $sql .= ' ORDER BY class,number ASC';
            } elseif (!empty($class)) {
                $sql .= ' AND classes.year = :year AND students.class = :class ';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $class;
                $sql .= ' ORDER BY class,number ASC';
            } else {
                // 学年主任の場合は同じ学年の生徒のみを検索
                $sql .= ' AND classes.year = :year';
                $bindValues[':year'] = $teacher_year;
                $sql .= ' ORDER BY class,number ASC';
            }
        } elseif ($role === 'general') {
            if (!empty($name) && !empty($number)) {
                $sql .= ' AND classes.year = :year AND students.class = :class AND classes.name LIKE :name AND students.number = :number';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $teacher_class_id;
                $bindValues[':name'] = '%' . $name . '%';
                $bindValues[':number'] = $number;
                $sql .= ' ORDER BY number ASC';
            } elseif (!empty($name)) {
                $sql .= ' AND classes.year = :year AND students.class = :class AND classes.name LIKE :name';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $teacher_class_id;
                $bindValues[':name'] = '%' . $name . '%';
                $sql .= ' ORDER BY number ASC';
            } elseif (!empty($number)) {
                $sql .= ' AND classes.year = :year AND students.class = :class AND students.number = :number';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $teacher_class_id;
                $bindValues[':number'] = $number;
                $sql .= ' ORDER BY number ASC';
            } elseif (!empty($class)) {
                $sql .= ' AND classes.year = :year AND students.class = :class ';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $class;
                $sql .= ' ORDER BY number ASC';
            } else {
                // 一般教員の場合は同じ学年かつ同じクラスの生徒のみを検索
                $sql .= ' AND classes.year = :year AND students.class = :class';
                $bindValues[':year'] = $teacher_year;
                $bindValues[':class'] = $teacher_class_id;
                $sql .= ' ORDER BY number ASC';
            }
        } elseif ($role === 'principal') {
            if (!empty($name) && !empty($number)) {
                $sql .= ' AND classes.name LIKE :name AND students.number = :number';
                $bindValues[':name'] = '%' . $name . '%';
                $bindValues[':number'] = $number;

            } elseif (!empty($name)) {
                $sql .= ' AND classes.name LIKE :name';
                $bindValues[':name'] = '%' . $name . '%';

            } elseif (!empty($number)) {
                $sql .= ' AND students.number = :number';
                $bindValues[':number'] = $number;

            } elseif (!empty($class) && (!empty($year))) {
                $sql .= ' AND classes.year = :year AND students.class = :class ';
                $bindValues[':year'] = $year;
                $bindValues[':class'] = $class;

            } // Principalは学年やクラスの指定がない場合は全ての生徒を表示するので、特別な処理は行いません。
            $sql .= ' ORDER BY year,class,number ASC';
        }


        // SQLクエリの実行
        $stmt = $dbh->prepare($sql);


        // バインド変数の設定
        foreach ($bindValues as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        // SQLクエリの実行
        $stmt->execute();

        // 結果の取得
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                                    <?php if ($role !== "principal") { ?> <input type="hidden" name=year
                                        value="<?php echo $row['year']; ?>"><?php echo $row['id']; ?>年
                                <?php } else { ?><select name="year" required>
                                    <option value="1" <?php echo $row['year'] == '1' ? ' selected' : ''; ?>>1年</option>
                                    <option value="2" <?php echo $row['year'] == '2' ? ' selected' : ''; ?>>2年</option>
                                    <option value="3" <?php echo $row['year'] == '3' ? ' selected' : ''; ?>>3年</option>
                                </select>
                            <?php } ?>
                                </p>
                                <p>
                                    組
                                    <?php if ($role === "general") { ?> <input type="hidden" name=class
                                        value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?>組
                                <?php } else { ?>
                                    <select name="class" required>
                                        <option value="1" <?php echo $row['class'] == '1' ? ' selected' : ''; ?>>1組</option>
                                        <option value="2" <?php echo $row['class'] == '2' ? ' selected' : ''; ?>>2組</option>
                                        <option value="3" <?php echo $row['class'] == '3' ? ' selected' : ''; ?>>3組</option>
                                        <option value="4" <?php echo $row['class'] == '4' ? ' selected' : ''; ?>>4組</option>
                                        <option value="5" <?php echo $row['class'] == '5' ? ' selected' : ''; ?>>5組</option>
                                    </select>
                                <?php } ?>
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