<?php
require('../login_check.php');

require('../connect.php');

$_SESSION['URL'] = $_SERVER["REQUEST_URI"];

//情報が入力されて再読み込みされたときの処理
if (!empty($_POST)) {
    if ($_POST['name'] != '') {
        $name = $_POST['name'];
        $_SESSION['name'] = $name;
        //すでにその避難所の物資情報が格納されているか調べる
        $record = mysqli_query($db, "SELECT * FROM shelter_goods WHERE name='$name'") or die(mysqli_error($db));
        //すでに書き込まれていた場合
        if ($table = mysqli_fetch_assoc($record)) {
            header("Location: shelter_update.php");
            exit();
        } //まだ書き込まれていない場合
        else {
            header("Location: shelter_create.php");
            exit();
        }
    }
} //避難所一覧から来たときの処理
else if (isset($_SESSION['shelterName'])) {
    $name = $_SESSION['shelterName'];
    $_SESSION['name'] = $name;
    //すでにその避難所の物資情報が格納されているか調べる
    $record = mysqli_query($db, "SELECT * FROM shelter_goods WHERE name='$name'") or die(mysqli_error($db));
    //すでに書き込まれていた場合
    if ($table = mysqli_fetch_assoc($record)) {
        header("Location: shelter_update.php");
        exit();
    } //まだ書き込まれていない場合
    else {
        header("Location: shelter_create.php");
        exit();
    }
} //最初にこの画面に来た時の処理
else {
    //ふつうの避難所の場合
    if ($_SESSION['code'] == "111") {
        $recordSet = mysqli_query($db, "SELECT * FROM shelter") or
        die(mysqli_error($db));
        //福祉避難所の場合
    } else if ($_SESSION['code'] == "222") {
        $recordSet = mysqli_query($db, "SELECT * FROM welfare_shelter") or
        die(mysqli_error($db));
        //洪水避難所の場合
    } else if ($_SESSION['code'] == "333") {
        $recordSet = mysqli_query($db, "SELECT * FROM flood_shelter") or
        die(mysqli_error($db));
        //津波避難所の場合
    } else if ($_SESSION['code'] == "444") {
        $recordSet = mysqli_query($db, "SELECT * FROM tunami_shelter") or
        die(mysqli_error($db));
        //火災避難所の場合
    } else {
        //データベースからデータを取ってくる
        $recordSet = mysqli_query($db, "SELECT * FROM fire_shelter") or
        die(mysqli_error($db));
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>避難所選択画面</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>避難所選択</p>
            </div>
            <div class="form_contents">
                <p>所属する避難所を選んでください</p>
                <form method="POST" action="">
                    <dl class="input">
                        <dt>
                        <p>
                            避難所：<select name="name">
                                <?php
                                while ($table = mysqli_fetch_assoc($recordSet)) {
                                    ?>
                                    <option value="<?php echo($table['name']); ?>">
                                        <?php print(htmlspecialchars($table['name'])); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </p>
                    </dl>
                    <center><input class="submit" type="submit" value="送信"></center>
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