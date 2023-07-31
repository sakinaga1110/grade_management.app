<?php
// 共通ヘッダーを読み込む
include '../components/header.php';
if ($role === 'general') {

}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <title>成績管理アプリ</title>
    <link rel="stylesheet" href="">
    <script src="" defer></script>
</head>
<h1 class="text-light">生徒登録</h1>
    <div class="d-flex bg-white justify-content-evenly flex-wrap rounded">

        <form action="s_save.php" method="post">
            <br/>
            所属学年
            <?php if ($role === 'general' || $role === 'chief'): ?>
                <input type="hidden" name="year" value="<?php echo $teacher_year; ?>"><?php echo $teacher_year, '年'; ?>
            <?php elseif ($role === 'principal'): ?>
                <select name="year" required>
                    <option value="1">1年</option>
                    <option value="2">2年</option>
                    <option value="3">3年</option>
                </select>
            <?php endif; ?>
            所属クラス
            <?php if ($role === 'general'): ?>
                <input type="hidden" name="class" value="<?php echo $teacher_class_id; ?>"><?php echo $teacher_class_id, '組'; ?>
            <?php elseif ($role !== 'general'): ?>
                <select name="class" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <!-- <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option> -->
                </select>
            <?php elseif ($role === 'general'): ?>
                <select name="class" required>
                    <option value="<?php echo $teacher_class_id; ?>"><?php echo $teacher_class_id; ?></option>
                </select>
            <?php endif; ?>
            学籍番号　※数字のみ
            <input type="number" name="number" required>
            <br />
            氏名
            <input type="text" name="name" required>
            <br />
            <br />
            <input type="submit" class="btn btn-primary" value="登録">
        </form>
        <br />
    </div>
    <br />
    <a href="s_index.php" class="btn btn-primary">戻る</a>
    <br /> <br />
