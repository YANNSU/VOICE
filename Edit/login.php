<?php
require('connect.php');

session_start();

if(!empty($_GET)){
    $_SESSION['shelterName'] = $_GET['name'];
}

if (!empty($_POST)) {
    //ログインの処理
    if ($_POST['code'] != '' && $_POST['password'] != '') {
        $sql = sprintf('SELECT * FROM members WHERE code="%s" AND password="%s"',
            mysqli_real_escape_string($db, $_POST['code']),
            mysqli_real_escape_string($db, sha1($_POST['password'])));

        $record = mysqli_query($db, $sql) or die(mysqli_error($db));
        if ($table = mysqli_fetch_assoc($record)) {
            //ログイン成功
            $_SESSION['code'] = $table['code'];
            $_SESSION['id'] = $table['id'];
            $_SESSION['time'] = time();

            $_SESSION['department'] = $table['department'];

            if ($table['department'] == "避難所" || $table['department'] == "火災避難所" || $table['department'] == "洪水避難所" || $table['department'] == "津波避難所" || $table['department'] == "福祉避難所") {
                header("Location: shelter/shelter_select.php");
                exit();
            } else if ($table['department'] == "管理者") {
                $_SESSION['manager'] = true;
                header("Location: manager/manager.php");
                exit();
            } else {
                header("Location: user/user.php");
                exit();
            }
        } else {
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログイン画面</title>
    <link rel="stylesheet" type="text/css" href="../Input_form.css">
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>職員ログイン</p>
            </div>
            <div class="form_contents">
                <p>部署コードとパスワードを記入してログインしてください</p>
                <form action="" method="post">
                    <dl class="input">
                        <dt>
                        <p>部署コード</p></dt>
                        <dd class="input">
                            <input type="text" name="code" size="35" maxlength="255"
                                <?php if (isset($_POST['code'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?>
                            <?php if (!empty($error) && ($error['login'] == 'blank')): ?>
                                <p class="error">*部署コードとパスワードをご記入ください</p>
                            <?php endif; ?>
                            <?php if (!empty($error) && ($error['login'] == 'failed')): ?>
                                <p class="error">*ログイン情報が間違っているようです</p>
                            <?php endif; ?>
                        </dd>
                        <dt>
                        <p>パスワード</p></dt>
                        <dd class="input">
                            <input type="password" name="password" size="35" maxlength="255" 
                                <?php if (isset($_POST['password'])): ?>
                                   value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') ?>"/>
                            <?php endif; ?>
                        </dd>
                    </dl>
                    <center><input class="submit" type="submit" value="ログインする"/></center>
            </div>
            </form>
        </div>
        <div class="button">
            <a href="../information.php">
                <div class="link center">一覧に戻る</div>
            </a>
        </div>
    </div>
</div>
</div>
</body>
</html>

