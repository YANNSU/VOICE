/**
 *緊急防災項目のデータを実際にとってきている部分
 **/


//================================================//
//臨時避難所を可視化するための関数
//================================================//
function visionTemporaryShelter(){

    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 20,
        'graphicWidth': 20
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    var url="json/evacuation.json";
    connectJsonURL(url, function(data){
        VisionJson(data.json, styleMap);
    });
}

//================================================//
//給水場所、物資拠点を可視化するための関数
//================================================//
function visionGoodsSupply(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/water.png",
        'graphicHeight': 20,
        'graphicWidth': 20
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/water.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    var url="json/water_supply.json";
    connectJsonURL(url, function(data){
        VisionJson(data.json, styleMap);
    });

    var url="json/goods_supply.json";
    connectJsonURL(url, function(data){
        VisionJson(data.json, styleMap);
    });
}

//================================================//
//ライフライン情報を可視化するための関数
//================================================//
function visionLifeWater(){
    var url="json/lifeline_water.json";
    connectJsonURL(url, function(data){
        ColorJson(data.json, "water");
    });
    
}

//================================================//
//ライフライン情報を可視化するための関数
//================================================//
function visionLifeElect(){
    var url="json/lifeline_electric.json";
    connectJsonURL(url, function(data){
        ColorJson(data.json, "elect");
    });
}

//================================================//
//遺体安置所の情報を可視化するための関数
//================================================//
function visionMorgue(){
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 20,
        'graphicWidth': 20
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 20,
        'graphicWidth': 20
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    var url="json/Morgue.json";
    connectJsonURL(url, function(data){
        VisionJson(data.json, styleMap);
    });
}

//================================================//
//通行止め情報を可視化するための関数
//================================================//
function visionRoadClosed(){
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/stop.png",
        'graphicHeight': 20,
        'graphicWidth': 20
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/stop.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    var url="json/RoadClosed.json";
    connectJsonURL(url, function(data){
        VisionJson(data.json, styleMap);
    });
}

//================================================//
//Jsonデータを取ってくる処理をする関数
//第一引数…接続するurlの文字列が入っている
//================================================//
function connectJsonURL(url, callback){
    $.ajax({
        url: url,
        dataType: "json",
        //成功したらjsonデータをコールバックして返す
        success: function (json) {
            console.log(json);
            callback(json);
        },
        //失敗したらエラーメッセージの表示
        error: function () {
            alert("まだデータが公開されていません");
        }
    });
}

//================================================//
//Jsonデータを点にして表示する関数
//第一引数…表示するjsonデータ
//================================================//
function VisionJson(json, styleMap) {
    var len = json.length;
    var point = new Array();
    for (var i = 0; i < len; i++) {
        // 点の座標を決める
        point[i] = new OpenLayers.Geometry.Point(json[i].lon, json[i].lat);

        point[i].transform(
            new OpenLayers.Projection("EPSG:4326"),
            new OpenLayers.Projection("EPSG:900913")
        );
        // 点を生成
        var pointFeature = new OpenLayers.Feature.Vector(point[i], json[i]);



        addVector.styleMap = styleMap;
        addVector.addFeatures([pointFeature]);
    }

//地図上に表示
    map.addLayer(addVector);
}


//================================================//
//Jsonデータを地区ごとに色分け表示する関数
//第一引数…反映するjsonデータ
//================================================//
function ColorJson(json, kind){
    //一旦水戸市のデータを削除する
    vector.removeAllFeatures();
    //色分け表示画面を表示しておく
        //水戸市の学区ごとのデータを読み込む
        $.getJSON("city/mito/mito_school.geojson", function (data) {
            var features = parser.read(data);
            var flug;

            //ここは水道の場合の処理
            if (kind == "water") {
                $("#W_LL").show();
                //読み取ったデータを一つ一つ判定していく
                for (var i = 0; i < features.length; i++) {
                    var str = features[i].attributes.A27_007;
                    var area_name = str.substr(0, str.length - 3) + "地区";
                    console.log(area_name);
                    flug = 0;
                    //緯度経度変換
                    features[i].geometry =
                        features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                    // ライフライン色付け
                    for (var j = 0; j < json.length && flug != 1; j++) {
                        //ライフラインが届いている地域は青で
                        if (area_name == json[j]['地区名']) {
                            flug = 1;
                            switch (json[j]['供給状況']) {
                                case 0:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#000055',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                                case 1:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#0055aa',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                                case 2:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#00aaff',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                                case 3:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#00ffff',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                            }
                        }
                    }
                }
            }//ここから電気の場合の処理
            else{
                $("#E_LL").show();
                //読み取ったデータを一つ一つ判定していく
                for (var i = 0; i < features.length; i++) {
                    var str = features[i].attributes.A27_007;
                    var area_name = str.substr(0, str.length - 3) + "地区";
                    console.log(area_name);
                    flug = 0;
                    //緯度経度変換
                    features[i].geometry =
                        features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                    // ライフライン色付け
                    for (var j = 0; j < json.length && flug != 1; j++) {
                        //ライフラインが届いている地域は青で
                        if (area_name == json[j]['地区名']) {
                            flug = 1;
                            switch (json[j]['供給状況']) {
                                case 0:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#ffff00',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                                case 1:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#aaaa00',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                                case 2:
                                    features[i].style = {
                                        'strokeColor': '#000000',
                                        'fillColor': '#555500',
                                        'fillOpacity': 0.8
                                    };
                                    break;
                            }
                        }
                    }
                }
            }
                vector.addFeatures(features);
            }

        );
}