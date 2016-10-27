<?php
//ログインしているのが管理者かどうかの判定
session_start();

//このページのURLを記憶
$_SESSION['URL'] = $_SERVER['REQUEST_URI'];

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理者画面</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">

        <div class="form" id="small">
            <div class="form_header">
                <p>ようこそ管理者さん</p>
            </div>
            <div class="form_contents">
                <p>メニューを選んでください</p>
                <dl class="input">
                    <dt>
                        <p>
                        <center><a href="add_user.php"><input class="function" type="submit" value="アカウントの追加"></a>
                        </center>
                        </p>
                    </dt>
                    <dt>
                        <p>
                        <center><a href="user_list.php"><input class="function" type="submit" value="アカウントの編集"></a>
                        </center>
                        </p>
                    </dt>
                    <dt>
                        <p>
                        <center><a href="data_update.php"><input class="function" type="submit" value="オープンデータの更新"></a>
                        </center>
                        </p>
                    </dt>
                    <dt>
                        <p>
                        <center><a href="manager_edit.php"><input class="function" type="submit" value="管理者情報の編集"></a>
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