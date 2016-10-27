/**
 *緊急防災項目のデータを実際にとってきている部分
 **/


//================================================//
//臨時避難所を可視化するための関数
//================================================//
function visionTemporaryShelter(){
    var url="https://glocalvision.net/Share/TemporaryShelter.json";
    connectJsonURL(url, function(json){
        console.log(json.json);
        VisionJson(json.json);
    });
}

//================================================//
//給水場所、物資拠点を可視化するための関数
//================================================//
function visionGoodsSupply(){
    var url="json/GoodsSupply.json";
    connectJsonURL(url, function(json){
        VisionJson(json);
    });
}

//================================================//
//ライフライン情報を可視化するための関数
//================================================//
function visionLifeWater(){
    var url="json/LifeWater.json";
    connectJsonURL(url, function(json){
        ColorJson(json.LifeLine);
    });
    
}

//================================================//
//ライフライン情報を可視化するための関数
//================================================//
function visionLifeElect(){
    var url="json/LifeElect.json";
    connectJsonURL(url, function(json){
        ColorJson(json);
    });
}

//================================================//
//遺体安置所の情報を可視化するための関数
//================================================//
function visionMorgue(){
    var url="json/Morgue.json";
    connectJsonURL(url, function(json){
        VisionJson(json);
    });
}

//================================================//
//通行止め情報を可視化するための関数
//================================================//
function visionRoadClosed(){
    var url="json/RoadClosed.json";
    connectJsonURL(url, function(json){
        VisionJson(json);
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
function VisionJson(json) {
    var len = json.length;
    var point = new Array();
    for (var i = 0; i < len; i++) {
        // 点の座標を決める
        point[i] = new OpenLayers.Geometry.Point(json[i].lon, json[i].lat);
        // 座標を変換 ※この処理が新たに必要だった
        point[i].transform(
            new OpenLayers.Projection("EPSG:4326"),
            new OpenLayers.Projection("EPSG:900913")
        );
        // 点を生成
        var pointFeature = new OpenLayers.Feature.Vector(point[i], json[i]);
        //点のスタイルの設定
        var defaultStyle = new OpenLayers.Style({
            strokeColor: "#000000",
            fillColor: "#FF6347",
            fillOpacity: 1,    // 内側の透明度
            strokeWidth: 1, // 外周の太さ
            pointRadius: 3  // 半径

        });
        //選択された時に画像を大きくする
        var selectStyle = new OpenLayers.Style({
            strokeColor: "#000000",
            fillColor: "#FF6347",
            fillOpacity: 1,    // 内側の透明度
            strokeWidth: 1, // 外周の太さ
            pointRadius: 10  // 半径
        });
        var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});
        //レイヤーに追加
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
function ColorJson(json){
    //一旦水戸市のデータを削除する
    vector.removeAllFeatures();
    //色分け表示画面を表示しておく
    $("#LL").show();
        //水戸市のデータを取り込む
        $.getJSON("city/mito/mito.geojson", function (data) {
            var features = parser.read(data);
            var flug;
            //読み取ったデータを一つ一つ判定していく
            for (var i = 0; i < features.length; i++) {
                flug = 0;
                //緯度経度変換
                features[i].geometry =
                    features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                // 人口色付け
                for (var j = 0; j < json.length; j++) {
                    //ライフラインが届いている地域は青で
                    if (features[i].attributes.MOJI == json[j].name) {
                        console.log("hoi");
                        flug = 1;
                        features[i].style = {
                            'strokeColor': '#000000',
                            'fillColor': '#00ffff',
                            'fillOpacity': 0.8
                        };
                        console.log(flug);
                    }
                }//ライフラインがまだの地域は赤で表示
                if(flug == 0){
                    features[i].style = {
                        'strokeColor': '#000000',
                        'fillColor': '#ff3300',
                        'fillOpacity': 0.8
                    };
                }
            }
            vector.addFeatures(features);
        });
}