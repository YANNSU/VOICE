<?php
require('../login_check.php');
$success = 0;

$department = $_SESSION['department'];

if (!empty($_POST)) {
    //記入欄が空欄でないかどうかを判定する
    if ($_POST['contents'] != '') {
        $class = $_POST["class"];
        $contents = $_POST["contents"];

        require('../connect.php');

        $recordSet = mysqli_query($db, "INSERT INTO information (department, class, contents) VALUE('$department','$class','$contents')") or
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
    <title>お知らせ情報の更新</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
    <?php if ($success == 1) : ?>
        <script type="text/javascript">
            window.onload = function () {
                alert("お知らせを追加しました！");
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
                <p>おしらせ情報の更新</p>
            </div>
            <div class="form_contents">
                <p>市民の方に伝達したい情報を入力してください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <dt>
                        <p>
                            担当課：<?php print(htmlspecialchars($department)); ?>
                        </p>
                        </dt>
                        <dt>
                        <p>
                            情報分類：<select name="class">
                                <option value="公共交通">公共交通機関情報</option>
                                <option value="手続き">手続き情報</option>
                                <option selected value="その他">その他</option>
                            </select>
                        </p>
                        </dt>
                        <dt>
                        <p>内容</p>
                        <p class="input_text"><textarea name="contents" cols="40" rows="10" style="width: 100%"></textarea></p>
                        <?php if (isset($error) && $error['login'] == 'blank'): ?>
                            <p class="error">*文章を入力してください</p>
                        <?php endif; ?>
                        </dt>
                    </dl>
                    <center><input class="submit" type="submit" value="この内容で送信する"></center>
                </form>
            </div>
        </div>
        <div class="button">
            <a href="user.php">
                <div class="link center">機能選択画面へ</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>


