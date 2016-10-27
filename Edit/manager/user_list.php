<?php
//ログインしているのが管理者かどうかの判定
session_start();

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}

require('../connect.php');

//削除が実行された場合の処理
if (!empty($_GET)) {
    $id = $_GET["id"];
    $department = $_GET["department"];

    //選択されていた項目の削除
    mysqli_query($db, "DELETE FROM members WHERE id='$id'") or
    die(mysqli_error($db));
}

//ユーザの一覧を取ってくる
$recordSet = mysqli_query($db, "SELECT * FROM members WHERE department NOT IN ('管理者')") or
die(mysqli_error($db));

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>アカウントの一覧</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>アカウント情報の編集</p>
            </div>
            <div class="form_contents">
                <div class="left">
                    <p>情報の削除・編集ができます</p>
                </div>
                <table class="info">
                    <tr>
                        <th class="info" width="140" height="50">部署名</th>
                        <th class="info">部署コード</th>
                        <th class="info" width="60">削除</th>
                        <th class="info" width="60">編集</th>
                    </tr>
                    <?php
                    while ($table = mysqli_fetch_assoc($recordSet)) {
                        ?>
                        <tr>
                            <td class="info"><?php print(htmlspecialchars($table['department'])); ?></td>
                            <td class="info"><?php print(htmlspecialchars($table['code'])); ?></td>
                            <td class="command">
                                <?php if ($table['department'] != '避難所' && $table['department'] != '津波避難所' && $table['department'] != '福祉避難所' && $table['department'] != '洪水避難所' && $table['department'] != '火災避難所') : ?>
                                    <a
                                        href="user_list.php?id=<?php echo $table['id']; ?>&department=<?php echo $department; ?>"
                                        onclick="return confirm('本当に削除しますか？');">
                                        <div class="delete">削除</div>
                                    </a>
                                    <?php else: ?>
                                    <div class="delete">削除</div>
                                <?php endif; ?>
                            </td>
                            <td class="command"><a
                                    href="other_edit.php?id=<?php echo $table['id']; ?>&department=<?php echo $department; ?>">
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
            <a href="manager.php">
                <div class="link center">管理者画面へ</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>

