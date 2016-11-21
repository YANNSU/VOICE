/**
 * Created by root on 2016/07/06.
 */

//===============================================//
//観光情報を可視化する関数
//===============================================//
function visionSightseeing(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/star.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/star.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("sightseeing", styleMap);
}

//===============================================//
//避難所を可視化する関数
//===============================================//
function visionShelter(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("shelter", styleMap);
}

//===============================================//
//福祉避難所を可視化する関数
//===============================================//
function visionWelfare(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("welfare_shelter", styleMap);
}

//===============================================//
//洪水避難所を可視化する関数
//===============================================//
function visionFlood(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("flood_shelter", styleMap);
}

//===============================================//
//火災避難所を表示する関数
//===============================================//
function visionFire(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("fire_shelter", styleMap);
}

function visionTunami(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/shelter.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision("tsunami_shelter", styleMap);
}

//===============================================//
//AED情報の可視化
//===============================================//
function visionAED(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/AED.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/AED.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    Vision('aed', styleMap);
}

//===============================================//
//井戸の場所を可視化する関数
//===============================================//
function visionWell(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/well.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/well.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});
    Vision("well", styleMap);
}

//===============================================//
//給水所を可視化する関数
//===============================================//
function visionWater(){
    //通常時の画像
    var defaultStyle = new OpenLayers.Style({
        'externalGraphic': "image/water.png",
        'graphicHeight': 16,
        'graphicWidth': 16
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        'externalGraphic': "image/water.png",
        'graphicHeight': 30,
        'graphicWidth': 30
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});
    Vision("water_supply", styleMap);
}

//========================================================//
//点のデータを可視化する関数
//第一引数…可視化するデータのキーワード
//第二引数…可視化するデータのスタイルオブジェクト
//========================================================//
function Vision(mapname, style) {

    connectDatabase(mapname, function(result){
        //resultにはjsonデータが入っている
        var len = result.length;
        var point = new Array();
        for (var i = 0; i < len; i++) {
            // 点の座標を決める
            point[i] = new OpenLayers.Geometry.Point(result[i].lon, result[i].lat);
            // 座標を変換 ※この処理が新たに必要だった
            point[i].transform(
                new OpenLayers.Projection("EPSG:4326"),
                new OpenLayers.Projection("EPSG:900913")
            );
            // 点を生成
            var pointFeature = new OpenLayers.Feature.Vector(point[i], result[i]);
            //レイヤーに追加
            addVector.styleMap = style;
            addVector.addFeatures([pointFeature]);
        }
        //地図上に表示
        map.addLayer(addVector);
    })
}
//========================================================//
//phpを呼び出しデータベースからjsonデータをとってくる関数
//第一引数…呼び出すデータのキーワード
//第二引数…コールバック関数
//========================================================//
function connectDatabase(mapdata, callback){
    $.ajax({
        url: "database.php",
        type: "POST",
        dataType: "json",
        data: {mapdata : mapdata},
        //成功したらjsonデータをコールバックして返す
        success: function(json){
            callback(json);
        },
        //失敗したらエラーメッセージの表示
        error: function(){
          alert("error");
        }
    });
}

//========================================================//
//選択した施設の情報を表示する関数
//========================================================//
function SelectMapFeatures(evt) {
    //選択された施設の情報を取得する
    feature = evt.feature;
    console.log(feature);
    //フラグを立てる
    flug = 1;
    table = '<table>';
    //必要な情報をテーブルに格納する
    for(var key = "施設名" in feature.attributes) {
        if(typeof(feature.attributes['説明']) == "undefined") {
            if (key != "id" && key != "lat" && key != "lon") {
                //文字列に選択された町域の情報を格納
                table += '<tr class="parameter"><td>' + key + '</td><td>' + feature.attributes[key] + '</td></tr>';
            }
        }
        else{
            if (key == "施設名" || key == "住所" || key == "電話") {
                //文字列に選択された町域の情報を格納
                table += '<tr class="parameter"><td>' + key + '</td><td>' + feature.attributes[key] + '</td></tr>';
            }
        }
    }
    table += '</table>';
    //文字列を指定の場所に表示
    $("div[id=info]").append(table);
    
    //詳細情報を表示する必要のある時は詳細情報ボタンも表示
    if(detailFlug == 1) {
        $("#detail").show();
    }
}

//========================================================//
//施設の選択が外れた時に情報を表示する関数
//========================================================//
function UnselectMapFeatures(evt) {
    //町域の情報を取得
    feature = evt.feature;
    //前にどこか選択されていたら、その情報を消す
    if (flug) {
        $("div[id=info]").empty();
        if(detailFlug == 1) {
            $("#detail").hide();
        }
        flug = 0;
    }
}