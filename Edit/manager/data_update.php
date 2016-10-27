<?php
//ログインしているのが管理者かどうかの判定
session_start();

if ($_SESSION['manager'] != true) {
    header('Location: ../login.php');
    exit();
}

$department = $_SESSION['department'];

//最初は各フラグが0
$success = 0;
$error = 0;

//ファイル読み込み後の処理
if (!empty($_POST)) {
    //==========================================//
    //ファイルが入力されていた場合の処理
    //==========================================//
    if (is_uploaded_file($_FILES['CSV_file']['tmp_name'])) {
        //なおかつcsvファイルであった場合
        if (pathinfo($_FILES['CSV_file']['name'], PATHINFO_EXTENSION) == 'csv') {
            //========================================//
            //ファイルを開いて配列にいれておく
            //========================================//
            $tmp = fopen($_FILES['CSV_file']['tmp_name'], "r");
            while ($data = fgetcsv($tmp, "1024", ",")) {
                $csv[] = $data;
            }
            //========================================//
            //対応するデータベースにデータを入れる処理
            //========================================//
            $name = $_POST["name"];
            $kind = $_POST["kind"];
            $csv_length = count($csv);
            //データベース接続
            require('../connect.php');

            //全部更新が選ばれていた場合は対応するデータを全削除しておく
            if ($kind == "all") {
                //一旦文字列に入れる
                $sql = sprintf('DELETE FROM %s', mysqli_real_escape_string($db, $name));
                //SQL文実行
                mysqli_query($db, $sql) or
                die(mysqli_error($db));
            }
            //==========================================//
            //対応するデータベースにデータを入れる処理
            //==========================================//

            //AEDを選択していた場合の処理
            if ($name == "aed") {
                for ($i = 0; $i < $csv_length; $i++) {
                    $json = $csv[$i];
                    mysqli_query($db, "INSERT INTO aed (name, address, place, lat, lon) VALUES ('$json[0]','$json[1]','$json[2]','$json[3]','$json[4]')") or
                    die(mysqli_error($db));
                }
                //成功フラグを立てておく
                $success = 1;
            } //井戸を選択していた場合の処理
            else if ($name == "well") {
                for ($i = 0; $i < $csv_length; $i++) {
                    $json = $csv[$i];
                    mysqli_query($db, "INSERT INTO well (name, address, pump, generater, lat, lon) VALUES ('$json[0]','$json[1]','$json[2]','$json[3]','$json[4]','$json[5]')") or
                    die(mysqli_error($db));
                }
                //成功フラグを立てておく
                $success = 1;
            } //その他の項目の場合の処理
            else {
                for ($i = 0; $i < $csv_length; $i++) {
                    $json = $csv[$i];
                    //一旦文字列に入れる
                    $sql = sprintf("INSERT INTO %s (name, address, lat, lon) VALUES ('$json[0]','$json[1]','$json[2]','$json[3]')",
                        mysqli_real_escape_string($db, $name));
                    //SQL文実行
                    mysqli_query($db, $sql) or
                    die(mysqli_error($db));
                }
                //成功フラグを立てておく
                $success = 1;
            }
        } //csvファイルではなかった場合
        else {
            $error = 2;
        }
    }
    //=========================================//
    //ファイルが選択されていなかった場合
    //=========================================//
    else {
        //エラーフラグを立てておく
        $error = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>データ更新</title>
    <link rel="stylesheet" type="text/css" href="../../Input_form.css">
    <script type="text/javascript" src="../../../js/jquery-2.2.3.min.js"></script>
    <?php if ($success == 1) : ?>
        <script type="text/javascript">
            function load() {
                alert("更新しました！");
            }
        </script>
    <?php endif; ?>
</head>
<body<?php if ($success == 1) : ?> onload="load();" <?php endif; ?>>
<div class="main">
    <div class="contents">
        <div class="form" id="small">
            <div class="form_header">
                <p>オープンデータの更新</p>
            </div>
            <div class="form_contents">
                <p>更新する項目と CSVファイルを選択してください</p>
                <form enctype="multipart/form-data" method="post">
                    <dl class="input">
                        <dt>
                        <p>
                            更新する項目：<select name="name">
                                <option value="aed">AED</option>
                                <option value="well">井戸</option>
                                <option value="water_supply">給水地点</option>
                                <option value="shelter">避難所</option>
                                <option value="fire_shelter">火災避難所</option>
                                <option value="flood_shelter">洪水避難所</option>
                                <option value="tsunami_shelter">津波避難所</option>
                                <option value="welfare_shelter">福祉避難所</option>
                            </select>
                        </p>
                        </dt>
                        <dt>
                        <p>
                            更新オプション：<select name="kind">
                                <option value="part">DB一部更新</option>
                                <option value="all">DB全体更新</option>
                            </select>
                        </p>
                        </dt>
                        <dt>
                        <p>
                            CSVファイル<input type="file" name="CSV_file"><br>
                        </p>
                        <?php if ($error == 1) : ?>
                            <p class="error">*ファイルを選択してください</p>
                        <?php endif; ?>
                        <?php if ($error == 2) : ?>
                            <p class="error">*csvファイルを選択してください</p>
                        <?php endif; ?>
                        </dt>
                    </dl>
                    <center><input class="submit" type="submit" value="データを送信する"/></center>
                </form>
            </div>
        </div>
        <div class="button">
            <a href="manager.php">
                <div class="link center">管理者画面へ</div>
            </a>
        </div>
    </div>
</div>
</div>
</body>
</html>

