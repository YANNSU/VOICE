<?php
//ログインしているのが管理者かどうかの判定
session_start();

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}


require('../connect.php');

// リクエストメソッドがPOSTかどうかの判定
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // POSTの中身が空かどうかを判定
    if (!empty($_POST)) {
        //エラー項目の確認
        if ($_POST['code'] == '') {
            $errors['code'] = '1';
        }
        if (ctype_digit($_POST['code']) == false) {
            $errors['not_number'] = '1';
        }
        if (strlen($_POST['password']) < 4) {
            $errors['password_length'] = '1';
        }
        if ($_POST['password'] == '') {
            $errors['password'] = '1';
        }
        if ($_POST['password_confirmation'] != $_POST['password']) {
            $errors['password_confirmation'] = '1';
        }

        //エラーが無かったら最終確認画面へ
        if (empty($errors)) {
            $_SESSION['join'] = $_POST;
            header('location: edit_check.php');
            exit();
        }
    }
} else {
    if (isset($_GET["action"]) && $_GET["action"] == "rewrite") {
        $_POST = $_SESSION['join'];
        $errors['rewrite'] = true;
    } else {
        $error['first'] = 1;
        $_POST['code'] = $_SESSION['code'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>部署情報の更新</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>管理者情報の更新</p>
            </div>
            <div class="form_contents">
                <p>新しいコードとパスワードを記入してください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <p>
                            <dt>部署コード</dt>
                        <dd class="input">
                            <input type="text" name="code" size="35" maxlength="255"
                                <?php if (!empty($errors) && isset($_POST['code'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?></dd>
                        <dd>
                            <?php if (!empty($errors) && isset($errors['code'])): ?>
                                <p class="error">＊部署コードを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dd>
                            <?php if (!empty($errors) && isset($errors['not_number'])): ?>
                                <p class="error">＊半角英数字で入力してください</p>
                            <?php endif; ?>
                        </dd>
                        </p>
                        <p>
                            <dt>パスワード</dt>
                        <dd class="input">
                            <input type="password" name="password" size="10" maxlength="20"
                                <?php if (!empty($errors) && isset($_POST['password'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?></dd>
                        <dd>
                            <?php if (!empty($errors) && isset($errors['password'])): ?>
                                <p class="error">＊パスワードを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dd>
                            <?php if (!empty($errors) && isset($errors['password_length'])): ?>
                                <p class="error">＊パスワードは4文字以上でおねがいします</p>
                            <?php endif; ?>
                        </dd>
                        </p>
                        <p>
                            <dt>パスワード(確認用)</dt>
                        <dd class="input">
                            <input type="password" name="password_confirmation" size="10" maxlength="20"
                                <?php if (!empty($errors) && isset($_POST['password'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?>
                        </dd>
                        <dd>
                            <?php if (!empty($errors) && isset($errors['password_confirmation'])): ?>
                                <p class="error">＊確認用のパスワードが一致しません</p>
                            <?php endif; ?>
                        </dd>
                        </p>
                    </dl>
                    <dl>
                        <center><input type="submit" class="submit" value="確認画面へ"></center>
                    </dl>

                </form>
            </div>
        </div>
        <div class="button">
            <a href="manager.php">
                <div class="link center">管理者画面へ</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
