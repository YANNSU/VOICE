<?php
//ログインしているかどうかの判定
session_start();
$success = 0;

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}

//直リンクを防ぐ
if (!isset($_SESSION['join'])) {
    header('Location: other_edit.php');
    exit();
}

//登録処理
if (!empty($_POST)) {
    require('../connect.php');

    $sql = sprintf('UPDATE members SET department="%s", code="%s", password="%s" WHERE id="%s"',
        mysqli_real_escape_string($db, $_SESSION['join']['department']),
        mysqli_real_escape_string($db, $_SESSION['join']['code']),
        mysqli_real_escape_string($db, sha1($_SESSION['join']['password'])),
        mysqli_real_escape_string($db, $_SESSION['userID'])
    );
    mysqli_query($db, $sql) or die(mysqli_error($db));
    unset($_SESSION['join']);

    $success = 1;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>部署情報の更新</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
    <?php if ($success == 1) : ?>
        <script type="text/javascript">
            window.onload = function () {
                alert("部署情報を追加しました！");
                window.location.href = '<?php print($_SESSION['URL'])?>';
            }
        </script>
    <?php endif; ?>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>部署情報の更新</p>
            </div>
            <div class="form_contents">
                <p>この内容で登録してもよろしいですか？</p>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="submit"/>
                    <dl class="input">
                        <dt>部署名</dt>
                        <dd>
                            <?php echo htmlspecialchars($_SESSION['join']['department'], ENT_QUOTES, 'UTF-8') ?>
                        </dd>
                        <dt>部署コード</dt>
                        <dd>
                            <?php echo htmlspecialchars($_SESSION['join']['code'], ENT_QUOTES, 'UTF-8') ?>
                        </dd>
                        <dt>パスワード</dt>
                        <dd>
                            【パスワードは表示されません】
                        </dd>
                    </dl>
                    <dl>
                        <center><input type="submit" class="submit" value="この内容で登録"></center>
                    </dl>
                </form>
                <dl>
                    <center><a href="other_edit.php?action=rewrite"><input type="submit" class="submit"
                                                                           value="登録し直す"></a>
                    </center>
                </dl>
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

