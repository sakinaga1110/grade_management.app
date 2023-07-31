<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>成績管理アプリ</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="grade_management.js" defer>
    </script>
</head>

<body>
<div class="container-fluid bg-primary  min-vh-100 d-flex align-items-center">
    <div class="container mt-5 bg-info rounded border">
        <br/>
        <h2 class="text-center mb-4 text-light">成績管理アプリ ログイン</h2>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form method="post" action="login_check.php">
                    <div class="form-group">
                        <label for="t_name">ユーザ名:</label>
                        <input type="text" class="form-control" name="t_name" id="t_name">
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード:</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="ログイン">
                    </div>
                </form>
                <br/>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
