<?php
//ログインしているかどうかの判定
session_start();

//このページのURLを記憶
$_SESSION['URL'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print(htmlspecialchars($_SESSION['department'])); ?></title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">

        <div class="form" id="small">
            <div class="form_header">
                <p><?php print(htmlspecialchars($_SESSION['department'])); ?></p>
            </div>
            <div class="form_contents">
                <p>機能を選択してください</p>
                <dl class="input">
                    <dt>
                        <p>
                        <center><a href="create.php"><input class="function" type="submit" value="お知らせ情報の登録"></a>
                        </center>
                        </p>
                    </dt>
                    <dt>
                        <p>
                        <center><a href="delete.php"><input class="function" type="submit" value="お知らせ情報の編集"></a>
                        </center>
                        </p>
                    </dt>
                    <dt>
                        <p>
                        <center><a href="user_edit.php"><input class="function" type="submit" value="部署情報の編集"></a>
                        </center>
                        </p>
                    </dt>
                </dl>
            </div>
        </div>
        <div class="button">
            <a href="../logout.php">
                <div class="link center">ログアウト</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>