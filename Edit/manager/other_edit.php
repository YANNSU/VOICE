<?php
//ログインしているかどうかの判定
session_start();

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}

require('../connect.php');

// 更新項目のチェック
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = 0;
// POSTの中身が空かどうかを判定
    if (!empty($_POST)) {
        //エラー項目の確認
        if ($_POST['department'] == '') {
            $errors['department'] = '1';
        }
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
            header('location: other_edit_check.php');
            exit();
        }

    } // 更新する項目のチェック
} else {
    if (isset($_GET["action"]) && $_GET["action"] == "rewrite") {
        $_POST = $_SESSION['join'];
        $errors['rewrite'] = true;
    } else {
        $first = 1;
        $id = $_GET["id"];
        $_SESSION["userID"] = $id;
        $department = $_GET["department"];

        //更新する項目の内容を持ってくる
        $recordSet = mysqli_query($db, "SELECT * FROM members WHERE id='$id'") or
        die(mysqli_error($db));
        $table = mysqli_fetch_assoc($recordSet);

        //各避難所項目が選択されていた場合
        if($table['department'] == '避難所' || $table['department'] == '津波避難所' || $table['department'] == '福祉避難所' || $table['department'] == '洪水避難所' || $table['department'] == '火災避難所'){
          $_SESSION['shelterFlug'] = 1;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>アカウント情報の更新</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>アカウント情報の更新</p>
            </div>
            <div class="form_contents">
                <p>新しいコードとパスワードを記入してください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <p>
                            <dt>部署名</dt>
                            <?php if (isset($_SESSION['shelterFlug'])): ?>
                        <dd class="input">
                            <input type="text" name="department" size="35" maxlength="255" readonly="readonly"
                                <?php if (!empty($errors) && isset($_POST['department'])): ?>
                                    value="<?php echo htmlspecialchars($_POST['department'], ENT_QUOTES, 'UTF-8') ?>"

                                <?php elseif ($first == 1): ?>
                                   value="<?php echo htmlspecialchars($table['department'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?>
                        </dd>

                        <?php else: ?>
                            <dd class="input">
                                <input type="text" name="department" size="35" maxlength="255"
                                    <?php if (!empty($errors) && isset($_POST['department'])): ?>
                                        value="<?php echo htmlspecialchars($_POST['department'], ENT_QUOTES, 'UTF-8') ?>"

                                    <?php elseif ($first == 1): ?>
                                       value="<?php echo htmlspecialchars($table['department'], ENT_QUOTES, 'UTF-8') ?>"/>
                                <?php endif; ?>
                            </dd>
                        <?php endif; ?>

                        <dd>
                            <?php if (!empty($errors) && isset($errors['name'])): ?>
                                <p class="error">＊部署名を入力してください</p>
                            <?php endif; ?>
                        </dd>
                        </p>
                        <p>
                            <dt>部署コード</dt>
                        <dd class="input">
                            <input type="text" name="code" size="35" maxlength="255"
                                <?php if (!empty($errors) && isset($_POST['code'])): ?>
                                    value="<?php echo htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8') ?>"

                                <?php elseif ($first == 1): ?>
                                   value="<?php echo htmlspecialchars($table['code'], ENT_QUOTES, 'UTF-8') ?>"/>
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
                <div class="link center">機能選択画面へ</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
