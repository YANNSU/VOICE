//============================================================//
//端末の現在地を取得する関数
//============================================================//
function geoLocation() {
    //最初に、geolocationが使えるかどうかを判定する
    if (navigator.geolocation) {
        // 現在位置を取得できる場合の処理
        navigator.geolocation.getCurrentPosition(successFunc, errorFunc, optionObj);
    }

    // Geolocation APIに対応していない
    else {
        // 現在位置を取得できない場合の処理
        alert("あなたの端末では、現在位置を取得できません。");
    }
}

// 成功した時の関数
function successFunc(position) {

    var defaultStyle = new OpenLayers.Style({
        strokeColor: "#eeeeee",
        fillColor: "#0077ff",
        fillOpacity: 1,    // 内側の透明度
        strokeWidth: 2, // 外周の太さ
        pointRadius: 10  // 半径
    });
    //選択された時に画像を大きくする
    var selectStyle = new OpenLayers.Style({
        strokeColor: "#eeeeee",
        fillColor: "#0077ff",
        fillOpacity: 1,    // 内側の透明度
        strokeWidth: 2, // 外周の太さ
        pointRadius: 10  // 半径
    });

    var style = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    // 緯度経度を返り値とする
    var point;
    // 点の座標を決める
    point = new OpenLayers.Geometry.Point(position.coords.longitude, position.coords.latitude);
    
    herelon = position.coords.longitude;
    herelat = position.coords.latitude;
    
    //座標の変換
    point.transform(
        new OpenLayers.Projection("EPSG:4326"),
        new OpenLayers.Projection("EPSG:900913")
    );
    // 点を生成
    var pointFeature = new OpenLayers.Feature.Vector(point,null);
    hereVector.styleMap = style;

    //前の情報を消してからレイヤーに追加
    hereVector.removeAllFeatures();
    hereVector.addFeatures([pointFeature]);
}

//失敗した時の関数
function errorFunc(error) {
    // エラーコードのメッセージを定義
    var errorMessage = {
        0: "原因不明のエラーが発生しました…。",
        1: "位置情報の取得が許可されませんでした…。",
        2: "電波状況などで位置情報が取得できませんでした…。",
        3: "位置情報の取得に時間がかかり過ぎてタイムアウトしました…。",
    };

    // エラーコードに合わせたエラー内容をアラート表示
    alert(errorMessage[error.code]);
}

// オプション・オブジェクト
var optionObj = {
    "enableHighAccuracy": false,
    "timeout": 8000,
    "maximumAge": 5000,
};