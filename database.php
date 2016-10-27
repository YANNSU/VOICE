<?php
$mapdata = $_POST["mapdata"];

require('Edit/connect.php');
//データベースからデータを取ってくる


//井戸情報をデータベースから持ってくる場合
if ($mapdata == "well") {
    $recordSet = mysqli_query($db, "SELECT * FROM well") or
    die(mysqli_error($db));

    //ここでjson形式に変換している
    $user = array();
    while ($row = mysqli_fetch_object($recordSet)) {
        $user[] = array(
            'id' => $row->id
        , '井戸コード' => $row->name
        , '場所' => $row->address
        , 'ポンプ形式' => $row->pump
        , '発電機' => $row->generater
        , 'lat' => $row->lat
        , 'lon' => $row->lon
        );
    }
} //給水所の情報をデータベースからもってくる
else if ($mapdata == "water_supply") {
    $recordSet = mysqli_query($db, "SELECT * FROM water_supply") or
    die(mysqli_error($db));

    $user = array();
    while ($row = mysqli_fetch_object($recordSet)) {
        $user[] = array(
            'id' => $row->id
        , '施設名' => $row->name
        , '住所' => $row->address
        , 'lat' => $row->lat
        , 'lon' => $row->lon
        );
    }
} //その他の場合 
else {
    //避難所の情報をデータベースから持ってくる
    if ($mapdata == "shelter") {
        $recordSet = mysqli_query($db, "SELECT * FROM shelter") or
        die(mysqli_error($db));
    } //福祉避難所の情報をデータベースからとってくる
    else if ($mapdata == "welfare_shelter") {
        $recordSet = mysqli_query($db, "SELECT * FROM welfare_shelter") or
        die(mysqli_error($db));
    } //火災避難所の情報をデータベースからとってくる
    else if ($mapdata == "fire_shelter") {
        $recordSet = mysqli_query($db, "SELECT * FROM fire_shelter") or
        die(mysqli_error($db));
    } //洪水避難所の情報をデータベースからとってくる
    else if ($mapdata == "flood_shelter") {
        $recordSet = mysqli_query($db, "SELECT * FROM flood_shelter") or
        die(mysqli_error($db));
    } //津波避難所の情報をデータベースからとってくる
    else if ($mapdata == "tsunami_shelter") {
        $recordSet = mysqli_query($db, "SELECT * FROM tsunami_shelter") or
        die(mysqli_error($db));
    } //AEDの情報をデータベースから持ってくる場合
    else if ($mapdata == "aed") {
        $recordSet = mysqli_query($db, "SELECT * FROM aed") or
        die(mysqli_error($db));
    }
    
    $user = array();
    while ($row = mysqli_fetch_object($recordSet)) {
        $user[] = array(
            'id' => $row->id
        , '施設名' => $row->name
        , '住所' => $row->address
        , 'lat' => $row->lat
        , 'lon' => $row->lon
        );
    }
}

header('Content-type: application/json');
$result = json_encode($user);
echo $result;
?>