<?php
require('../login_check.php');

$success = 0;

require('../connect.php');

//更新するときの処理
if (!empty($_POST)) {

    $id = $_POST["id"];
    $department = $_SESSION["department"];
    $class = $_POST["class"];
    $contents = $_POST["contents"];

    require('../connect.php');

    //変更されたデータを挿入する
    mysqli_query($db, "UPDATE information SET class='$class', contents='$contents' WHERE id='$id'") or
    die(mysqli_error($db));

    $success = 1;
}

//編集するときの処理
$id = $_GET["id"];
//データベースからデータを取ってくる
$recordSet = mysqli_query($db, "SELECT * FROM information WHERE id='$id'") or
die(mysqli_error($db));
$table = mysqli_fetch_assoc($recordSet);
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
                alert("お知らせを変更しました！");
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
                <dl class="input">

                    <dt>
                    <p>情報を変更したら更新ボタンを押してください</p>
                    </dt>

                    <form method="POST" action="">

                        <dt>
                        <p>
                            情報分類：<select name="class">
                                <option selected value="<?php echo(htmlspecialchars($table['class'])); ?>">変更なし
                                </option>
                                <option value="公共交通">公共交通機関情報</option>
                                <option value="手続き">手続き情報</option>
                                <option value="その他">その他</option>
                            </select>
                        </p>
                        </dt>


                        <dt>
                        <p>内容</p>
                        <p class="input_text"><textarea name="contents" cols="40" rows="10">
                                <?php if (isset($table['contents'])) { ?>
                                    <?php print(htmlspecialchars($table['contents'])); ?>
                                <?php } ?>
                                </textarea></p>
                        </dt>


                        <dt>
                            <input type="hidden" name="id"
                                   value="<?php echo(htmlspecialchars($table['id'])); ?>">
                        <center><input type="submit" class="submit" value="お知らせ情報の更新"></center>
                    </form>
                </dl>
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

