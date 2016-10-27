<?php
$name = $_POST["name"];

//データベース接続
require('Edit/connect.php');

//その施設の情報をデータベースから探す
$recordSet = mysqli_query($db, "SELECT * FROM shelter_goods WHERE name='$name'") or die(mysqli_error($db));

$user = array();

//データが登録されていた場合
if ($table = mysqli_fetch_assoc($recordSet)) {
    $user = array();
    //データをオブジェクト型にして返す
    $user = array(
        'YesNo' => "yes"   //あった場合、ここがyes
    , 'id' => $table['id']
    , '施設名' => $table['name']
    , '食料' => $table['food']
    , '生活品' => $table['life']
    , 'その他' => $table['other']
    , '更新日' => $table['date']
    );
} //まだデータが登録されていなかった場合
else {
    $user = array(
        'YesNo' => "no"   //無かった場合、ここがno
    );
}

//最後にエンコードして返す
header('Content-type: application/json');
$result = json_encode($user);
echo $result;
?>