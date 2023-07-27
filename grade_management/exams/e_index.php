<?php
// データベースへの接続処理（例）
$dsn = 'mysql:dbname=grade_management;host=localhost;charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $searchName = isset($_POST['name']) ? $_POST['name'] : '';
    $searchNumber = isset($_POST['number']) ? $_POST['number'] : '';

    // 検索クエリの作成
    $sql = 'SELECT exams.id as exam_id, exams.japanese, exams.math, exams.english, exams.science, exams.society, exams.total, students.number, tests.id as test_id, tests.test_name, students.id as student_id, classes.name,classes.year
        FROM exams
        INNER JOIN students ON exams.student_id = students.id
        INNER JOIN tests ON exams.test_id = tests.id
        INNER JOIN classes ON classes.class_id = students.class_id';

    // WHERE句の条件を組み立てる
    $conditions = array();
    if (!empty($searchName)) {
        $conditions[] = 'classes.name LIKE :name';
    }
    if (!empty($searchNumber)) {
        $conditions[] = 'students.number LIKE :number';
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = $dbh->prepare($sql);

    // プレースホルダに値をバインド
    if (!empty($searchName)) {
        $stmt->bindValue(':name', '%' . $searchName . '%', PDO::PARAM_STR);
    }
    if (!empty($searchNumber)) {
        $stmt->bindValue(':number', '%' . $searchNumber . '%', PDO::PARAM_STR);
    }

    $stmt->execute();

    // 検索結果を取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // 検索結果の数を取得
    $numResults = count($results);
} catch (PDOException $e) {
    echo 'エラーが発生しました：' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function calculateTotal(examId) {
            const japaneseScore = parseFloat(document.getElementsByName('japanese_' + examId)[0].value) || 0;
            const mathScore = parseFloat(document.getElementsByName('math_' + examId)[0].value) || 0;
            const englishScore = parseFloat(document.getElementsByName('english_' + examId)[0].value) || 0;
            const scienceScore = parseFloat(document.getElementsByName('science_' + examId)[0].value) || 0;
            const societyScore = parseFloat(document.getElementsByName('society_' + examId)[0].value) || 0;

            const totalScore = japaneseScore + mathScore + englishScore + scienceScore + societyScore;
            document.getElementById('total_' + examId).value = totalScore;
            // console.log(totalScore);
            // console.log(examId);
        }
        // 変数としてスロットリングの間隔（ミリ秒単位）を設定
        const throttlingInterval = 500; // 0.5秒

        // 最後にリクエストを送信した時刻を記録する変数を初期化
        let lastRequestTime = 0;

        function sendAjaxRequest(examId, action, method = 'POST') {
            const currentTime = Date.now();

            // スロットリングの間隔に満たない場合は処理を中断
            if (currentTime - lastRequestTime < throttlingInterval) {
                return;
            }
            const japaneseScore = parseFloat(document.getElementsByName('japanese_' + examId)[0].value) || 0;
            const mathScore = parseFloat(document.getElementsByName('math_' + examId)[0].value) || 0;
            const englishScore = parseFloat(document.getElementsByName('english_' + examId)[0].value) || 0;
            const scienceScore = parseFloat(document.getElementsByName('science_' + examId)[0].value) || 0;
            const societyScore = parseFloat(document.getElementsByName('society_' + examId)[0].value) || 0;
            const totalScore = japaneseScore + mathScore + englishScore + scienceScore + societyScore;

            const postData = new URLSearchParams();
            postData.append('id', examId);
            postData.append('japanese_' + examId, japaneseScore);
            postData.append('math_' + examId, mathScore);
            postData.append('english_' + examId, englishScore);
            postData.append('science_' + examId, scienceScore);
            postData.append('society_' + examId, societyScore);
            postData.append('total_' + examId, totalScore);

            fetch(action, {
                method: method,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: postData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                    const modalElement = document.getElementById('e_edit_' + examId);
                    const bootstrapModal = new bootstrap.Modal(modalElement);
                    bootstrapModal.hide();
                    alert('テストの結果が更新されました。');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('通信エラーが発生しました。もう一度試してください。');
                })
                .finally(() => {
                    // リクエスト送信時刻を更新
                    lastRequestTime = currentTime;
                });
        }

        // テスト削除の非同期POSTリクエストを送信する関数
        function deleteExam(examId) {
            const action = 'e_destroy.php';
            sendAjaxRequest(examId, action);
        }

        // 編集保存の非同期POSTリクエストを送信する関数
        function saveExam(examId) {
            const action = 'e_update.php';
            sendAjaxRequest(examId, action);
        }
        document.addEventListener('DOMContentLoaded', () => {
            <?php foreach ($results as $result) { ?>
                const saveButton<?php echo $result['exam_id']; ?> = document.getElementById('save_button_<?php echo $result['exam_id']; ?>');
                if (saveButton<?php echo $result['exam_id']; ?>) {
                    saveButton<?php echo $result['exam_id']; ?>.onclick = function () {
                        saveExam(<?php echo $result['exam_id']; ?>);
                    };
                }
            <?php } ?>
        });
    </script>
</head>


<body>
    <a href="e_create.php">
        <h3>成績の登録</h3>
    </a> <br /> <br />
    <form id="student_search_form" method="post" action="e_index.php">
        <label for="search_name">生徒名:</label>
        <input type="text" id="search_name" name="name" placeholder="生徒名を入力">

        <label for="search_number">学籍番号:</label>
        <input type="text" id="search_number" name="number" placeholder="学籍番号を入力">

        <input type="submit" value="検索">
    </form>

    <br /> <br />


    <?php if ($numResults > 0) { ?>
        <?php
        foreach ($results as $result) {
            ?>
            <br />
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#e_edit_<?php echo $result['exam_id']; ?>">
                <?php echo $result['year'] . '年　' . $result['test_name'] . '学籍番号　' . $result['number'] . '　' . $result['name'] . '　国語　' . $result['japanese'] . '　数学　' . $result['math'] . '　英語　' . $result['english'] . '　理科　' . $result['science'] . '　社会　' . $result['society'] . '　合計　' . $result['total']; ?>
            </button>
            <br />
            <div class="modal fade" id="e_edit_<?php echo $result['exam_id']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <?php echo $result['year'] . '年' . $result['test_name'] . '<br/>　学籍番号　' . $result['number'] . '　' . $result['name'] . 'さんの成績を編集中'; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="exam_form_<?php echo $result['exam_id']; ?>" method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $result['exam_id']; ?>">
                                国語
                                <input type="number" name="japanese_<?php echo $result['exam_id']; ?>" min="0" max="100"
                                    value="<?php echo $result['japanese']; ?>"
                                    onchange="calculateTotal(<?php echo $result['exam_id']; ?>)">
                                数学
                                <input type="number" name="math_<?php echo $result['exam_id']; ?>" min="0" max="100" value="<?php echo $result['math'];
                                   ; ?>" onchange="calculateTotal(<?php echo $result['exam_id']; ?>)">
                                英語
                                <input type="number" name="english_<?php echo $result['exam_id']; ?>" min="0" max="100"
                                    value="<?php echo $result['english']; ?>"
                                    onchange="calculateTotal(<?php echo $result['exam_id']; ?>)">
                                理科
                                <input type="number" name="science_<?php echo $result['exam_id']; ?>" min="0" max="100"
                                    value="<?php echo $result['science']; ?>"
                                    onchange="calculateTotal(<?php echo $result['exam_id']; ?>)">
                                <br />
                                社会
                                <input type="number" name="society_<?php echo $result['exam_id']; ?>" min="0" max="100"
                                    value="<?php echo $result['society']; ?>"
                                    onchange="calculateTotal(<?php echo $result['exam_id']; ?>)">
                                合計
                                <input type="number" name="total_<?php echo $result['exam_id']; ?>" min="0" max="500"
                                    value="<?php echo $result['total']; ?>" id="total_<?php echo $result['exam_id']; ?>"
                                    readonly>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" name="delete_submit"
                                        onclick="deleteExam(<?php echo $result['exam_id']; ?>)">テストを削除</button>
                                    <button class="btn btn-danger" name="edit_submit"
                                        onclick="saveExam(<?php echo $result['exam_id']; ?>)">編集を保存</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <?php
    } else { ?>
        <h3>該当するデータが見つかりませんでした。</h3><br />
        <a href="e_index.php">
            <h3>もう一度検索する</h3>
        </a>
    <?php } ?>
    <br />
    <a href="../index.php">トップページへ</a>
</body>

</html>