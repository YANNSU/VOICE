<?php
header('Access-Control-Allow-Origin: *');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>試作</title>
    <link rel="stylesheet" type="text/css" href="emergency.css"/>
    <script type="text/javascript" src="../js/ol/OpenLayers.js"></script>
    <script type="text/javascript" src="../js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="emergency.js"></script>
    <script type="text/javascript" src="map/map_functions.js"></script>
    <script type="text/javascript" src="map/disaster_prevention.js"></script>
    <script type="text/javascript" src="emergency_information.js"></script>
    <script type="text/javascript" src="map/geolocation.js"></script>
</head>
<body onload="init();">
<div class="main">
    <div id="now-loading">
        <img src="image/load.gif">
    </div>
    <div id="modal-main">
        <div class="modal-name">詳細情報</div>
        <table class="modal-table" id="modal-table">
        </table>
    </div>
    <div class="contents">
        <div class="header">
            <div class="header_top">
                <img class="system-logo" src="image/logo.png">
                <a href="information.php">
                    <div class="info_button">i</div>
                </a>
                <div class="menu_button">≡</div>
            </div>
            <div class="header_bottom">
                <a href="normal.html">
                    <div class="mode2">平常</div>
                </a>
                <div class="mode1">緊急</div>
            </div>
        </div>
        <div class="map">

            <div class="top">
                <div id="map"></div>
                <div class="break_down" id="LL">
                    <p>
                        <span style="color: #00ffff">■&nbsp;<span style="font-weight: 600">供給済み</span></span>
                    </p>
                    <p>
                        <span style="color: #ff3300">■&nbsp;<span style="font-weight: 600">未供給</span></span>
                    </p>
                </div>
            </div>
            <div class="bottom">
                <div id="info"></div>
                <div id="herePlace">現在地</div>
                <div id="detail">詳細情報</div>
            </div>
        </div>
        <div class="emergency_menu">
            <ul id="menu">
                <li><span class="list-header">緊急防災</span></li>
                <li class="mapname" value="temporary_shelter"><a>臨時避難所</a></li>
                <li class="mapname" value="goods_supply"><a>給水・物資拠点</a></li>
                <li class="mapname" value="life_line_water"><a>ライフライン（水道）</a></li>
                <li class="mapname" value="life_line_elect"><a>ライフライン（電気）</a></li>
                <li class="mapname" value="morgue"><a>遺体安置所情報</a></li>
                <li class="mapname" value="road_closed"><a>通行止め情報</a></li>
                <li><span class="list-header">防災</span></li>
                <li class="mapname" value="shelter"><a>避難所</a></li>
                <li class="mapname" value="Welfare_shelter"><a>福祉避難所</a></li>
                <li class="mapname" value="Flood_shelter"><a>洪水避難所</a></li>
                <li class="mapname" value="Tsunami_shelter"><a>津波避難所</a></li>
                <li class="mapname" value="Fire_shelter"><a>火災避難所</a></li>
            </ul>
        </div>
        <a href="information.php">
            <div class="link_information">自治体からのおしらせ</div>
        </a>
    </div>
</div>
</body>
</html>
