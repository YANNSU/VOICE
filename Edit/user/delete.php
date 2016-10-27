<?php
require('../login_check.php');

require('../connect.php');

//このページのURLを記憶
$_SESSION['URL'] = $_SERVER['REQUEST_URI'];

if (!empty($_GET)) {
    $object = $_GET["id"];
    $department = $_SESSION["department"];

    //選択されていた項目の削除
    mysqli_query($db, "DELETE FROM information WHERE id='$object'") or
    die(mysqli_error($db));
} else {
    $department = $_SESSION["department"];
}

//データベースからデータの一覧を取ってくる
$recordSet = mysqli_query($db, "SELECT * FROM information WHERE department = '$department'") or
die(mysqli_error($db));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>情報削除画面</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form">
            <div class="form_header">
                <p>お知らせ情報の編集</p>
            </div>
            <div class="form_contents">
                <div class="left">
                    <p>情報の削除・編集ができます</p>
                </div>
                <table class="info">
                    <tr>
                        <th class="info" width="120" height="50">分類</th>
                        <th class="info">内容</th>
                        <th class="info" width="120">日付</th>
                        <th class="info" width="60">削除</th>
                        <th class="info" width="60">編集</th>
                    </tr>
                    <?php
                    while ($table = mysqli_fetch_assoc($recordSet)) {
                        ?>
                        <tr>
                            <td class="info"><?php print(htmlspecialchars($table['class'])); ?></td>
                            <td class="info"><?php print(nl2br($table['contents'])); ?></td>
                            <td class="info"><?php print(nl2br($table['date'])); ?></td>
                            <td class="command"><a
                                    href="delete.php?id=<?php echo $table['id']; ?>&department=<?php echo $department; ?>"
                                    onclick="return confirm('本当に削除しますか？');">
                                    <div class="delete">削除</div>
                                </a></td>
                            <td class="command"><a
                                    href="update.php?id=<?php echo $table['id']; ?>&department=<?php echo $department; ?>">
                                    <div class="update">編集</div>
                                </a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
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
