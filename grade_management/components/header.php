<?php
session_start();

// セッションにユーザ名が保存されていない場合はログイン画面にリダイレクト
if (!isset($_SESSION['t_name'])) {
    header('Location: login.php');
    exit;
}
// var_dump($_SESSION);
// ログインしたユーザー名とログイン日時を取得

$login_user = $_SESSION['t_name'];
$login_time = date('Y-m-d H:i:s');
$role = $_SESSION['role'];
$teacher_id = $_SESSION['teacher_id'];
$teacher_year = $_SESSION['year'];
$teacher_class_id = $_SESSION['class_id'];
// var_dump($login_user);
// var_dump($role);
// var_dump($teacher_year);
// var_dump($teacher_class_id);
// サニタイズ関数
function sanitizePostArray($array)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = sanitizePostArray($value);
        } else {
            $array[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    return $array;
}

// Usage example:
$post = sanitizePostArray($_POST);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>成績管理アプリ　</title>
    <script src="components/grade_management.js" defer>
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-fluid bg-primary  min-vh-100 d-flex align-items-center">
        <div class="container bg-info rounded">
            <div class="text-center h1 text-light"><br/>成績管理アプリ</div>
            <div class="row justify-content-end">
                <div class="col-auto">
                    <br />
                    ログインユーザー名:
                    <?php echo $login_user; ?>
                    ログイン日時:
                    <?php echo $login_time; ?>
                    <a href="#" button class="btn btn-primary" onclick="confirmLogout()"><h5>ログアウト</h5></a><br /><br/>
                </div>
            </div>