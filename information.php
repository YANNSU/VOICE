<?php
require('Edit/connect.php');

//絞り込み検索を行った場合
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql;
    $sql1;
    //何が分類
    if (isset($_POST["class"])) {
        $class = $_POST["class"];
        if ($class == "担当課") {
            $sql1 = "SELECT * FROM information ORDER BY department ASC, date DESC";
        } else if ($class == "日付") {
            $sql1 = "SELECT * FROM information ORDER BY date DESC, department ASC";
        } else {
            $sql1 = "SELECT * FROM information WHERE class='$class' ORDER BY department ASC, date DESC";
        }
    } else {
        $sql1 = "SELECT * FROM information ORDER BY department ASC, date DESC";
    }
    if (isset($_POST["word"]) && $_POST["word"] != "") {
        $word = $_POST["word"];
        $sql2;
        $sql2 = "SELECT * FROM information WHERE contents LIKE '%$word%'";
        $sql = "SELECT * FROM (" . $sql1 . ") AS t1 JOIN (" . $sql2 . ") AS t2 ON t1.id = t2.id";
    } else {
        $sql = $sql1;
    }

    $recordSet = mysqli_query($db, $sql) or
    die(mysqli_error($db));

} else {
//データベースからデータを取ってくる
    $recordSet = mysqli_query($db, "SELECT * FROM information ORDER BY department ASC, date DESC") or
    die(mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>自治体からのお知らせ</title>
    <link rel="stylesheet" type="text/css" href="Input_form.css">
</head>
<body>
<div class="main">
    <div class="contents">
        <div class="form">
            <div class="form_header">
                <p>お知らせ</p>
            </div>
            <div class="form_contents">
                <div class="left">
                    <p>市役所からのお知らせをまとめています</p>
                </div>
                <div class="right">
                    <form method="POST" action="information.php">
                        <p>　ワード検索
                            <input type="text" name="word" size="10">
                        </p>
                        <p>分類絞り込み
                            <select name="class">
                                <option value="担当課">担当課</option>
                                <option value="日付">日付が新しい順</option>
                                <option value="公共交通">交通のお知らせ</option>
                                <option value="手続き">手続きのお知らせ</option>
                                <option value="その他">その他のお知らせ</option>
                            </select>
                            <input type="submit" value="絞り込み">
                        </p>
                    </form>
                </div>

                <table class="info">
                    <tr>
                        <th class="info" width="120px" height="50">発信課</th>
                        <th class="info" width="120px">分類</th>
                        <th class="info">内容</th>
                        <th class="info" width="120px">日付</th>
                    </tr>
                    <?php
                    while ($table = mysqli_fetch_assoc($recordSet)) {
                        ?>
                        <tbody>
                        <tr>
                            <td class="info"><?php print(htmlspecialchars($table['department'])); ?></td>
                            <td class="info"><?php print(htmlspecialchars($table['class'])); ?></td>
                            <td class="info"><?php print(nl2br($table['contents'])); ?></td>
                            <td class="info"><?php print(nl2br($table['date'])); ?></td>
                        </tr>
                        </tbody>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="button">
            <a href="emergency.html">
                <div class="link left">元の画面に戻る</div>
            </a>
            <a href="Edit/login.php">
                <div class="link right">自治体の方はこちら</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>

