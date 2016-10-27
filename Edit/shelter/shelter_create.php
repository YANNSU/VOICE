<?php
require('../login_check.php');
$success = 0;

$name = $_SESSION['name'];

//項目が入力された後の処理
if (!empty($_POST)) {
    //記入欄が空欄でないかどうかを判定する
    if ($_POST['food'] != '' && $_POST['life'] != '' && $_POST['other'] != '') {
        $food = $_POST["food"];
        $life = $_POST["life"];
        $other = $_POST["other"];

        require('../connect.php');

        $recordSet = mysqli_query($db, "INSERT INTO shelter_goods (name, food, life, other) VALUE('$name','$food','$life','$other')") or
        die(mysqli_error($db));

        $success = 1;

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
    <title>情報入力画面</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
    <?php if ($success == 1) : ?>
        <script type="text/javascript">
            window.onload = function () {
                alert("避難所情報を追加しました！");
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
                <p>避難所情報追加</p>
            </div>
            <div class="form_contents">
                <p>住民の方に伝達したい情報を入力してください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <dt>
                        <p>
                            施設名：<?php print(htmlspecialchars($name)); ?>
                        </p>
                        </dt>
                        <dt>
                        <p>食料品の状況</p>
                        <p class="input_text"><textarea name="food" cols="40" rows="10"
                                     style="width: 100%"><?php if (isset($errors) && isset($_POST['food'])) {
                                    print(htmlspecialchars($_POST['food']));
                                } ?></textarea></p>
                        </dt>
                        <dt>
                        <p>生活用品の状況</p>
                        <p class="input_text"><textarea name="life" cols="40" rows="10"
                                     style="width: 100%"><?php if (isset($errors) && isset($_POST['life'])) {
                                    print($_POST['life']);
                                } ?></textarea></p>
                        </dt>
                        <dt>
                        <p>その他の連絡</p>
                        <p class="input_text"><textarea name="other" cols="40" rows="10"
                                     style="width: 100%"><?php if (isset($errors) && isset($_POST['other'])) {
                                    print($_POST['other']);
                                } ?></textarea></p>
                        <?php if (isset($error) && $error['login'] == 'blank'): ?>
                            <p class="error">*どこかの空欄を埋めてください</p>
                        <?php endif; ?>
                        </dt>
                    </dl>
                    <center><input class="submit" type="submit" value="この内容で送信する"></center>
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


