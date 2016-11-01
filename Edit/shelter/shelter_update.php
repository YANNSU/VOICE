<?php
require('../login_check.php');
$success = 0;

$name = $_SESSION['name'];

require('../connect.php');

//入力した後の処理
if (!empty($_POST)) {
    if ($_POST['food'] != '' && $_POST['life'] != '' && $_POST['other'] != '') {
        $food = $_POST["food"];
        $life = $_POST["life"];
        $other = $_POST["other"];

        $recordSet = mysqli_query($db, "UPDATE shelter_goods SET name = '$name', food = '$food', life='$life', other='$other' WHERE name='$name'") or
        die(mysqli_error($db));

        $success = 1;

    } else {
        $error['login'] = 'blank';
    }
} //このページに来た時の処理
else {
//データベースからデータを取ってくる
    $recordSet = mysqli_query($db, "SELECT * FROM shelter_goods WHERE name='$name'") or
    die(mysqli_error($db));
    $table = mysqli_fetch_assoc($recordSet);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>情報更新画面</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
    <?php if ($success == 1) : ?>
        <script type="text/javascript">
            window.onload = function () {
                alert("避難所情報を更新しました！");
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
                <p>避難所情報更新</p>
            </div>
            <div class="form_contents">
                <p>情報を更新したら、更新ボタンを押してください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <dt>
                        <p>
                            施設名：<?php print(htmlspecialchars($name)); ?>
                        </p>
                        </dt>
                        <dt>
                        <p>食料品の状況</p>
                        <p class="input_text"><textarea name="food" cols="40" rows="10" style="width: 100%">
                            <?php if (isset($table['food'])) { ?>
                                <?php print(htmlspecialchars($table['food'])); ?>
                            <?php } ?>
                        </textarea></p>
                        </dt>
                        <dt>
                        <p>生活用品の状況</p>
                        <p class="input_text"><textarea name="life" cols="40" rows="10" style="width: 100%">
                            <?php if (isset($table['life'])) { ?>
                                <?php print(htmlspecialchars($table['life'])); ?>
                            <?php } ?>
                        </textarea></p>
                        </dt>
                        <dt>
                        <p>その他の連絡</p>
                        <p class="input_text"><textarea name="other" cols="40" rows="10" style="width: 100%">
                            <?php if (isset($table['other'])) { ?>
                                <?php print(htmlspecialchars($table['other'])); ?>
                            <?php } ?>
                        </textarea></p>
                        </dt>
                    </dl>
                    <center><input class="submit" type="submit" value="この内容で更新する"></center>
                </form>
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