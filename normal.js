//================================================//
//端末によって違う処理
//================================================//
$(document).ready(function () {
    //スマホ端末時のみ発動するスクリプト
    if ($(window).width() < 800) {
        //メニューボタンを押したときの処理
        $('div.menu_button').click(function () {
            //出てたらしまう
            if ($('div.normal_menu').hasClass('on')) {
                $('div.normal_menu').animate({'marginLeft': '0px'}, 500);
                $('div.normal_menu').removeClass('on');
            }
            //出てなかったら出す
            else {
                $('div.normal_menu').animate({'marginLeft': '70%'}, 500);
                $('div.normal_menu').addClass('on');
            }
            //これをやっておかないと次の全画面クリック判定に引っかかる
            event.stopPropagation();
        });
        //メニューボタン以外がクリックされたときの処理
        $(document).click(function () {
            //メニューをしまう
            $('div.normal_menu').animate({'marginLeft': '0px'}, 500);
            $('div.normal_menu').removeClass('on');
        });
        //メニューバーを触っても格納しないようにする
        $('div.normal_menu').click(function () {
            event.stopPropagation();
        });
        $(".mapname").click(function () {
            $('div.normal_menu').animate({'marginLeft': '0px'}, 500);
            $('div.normal_menu').removeClass('on');
        });
    }
});

//=====================================================================//
//ここはグローバル変数
//=====================================================================//
var map;
var flug; //今マップ上の点が選択されているか判断するためのフラグ
var mapFlug; //基礎情報の項目が選択されているかを判定する関数
var mapvalue;
var herelat;
var herelon;

//=====================================================================//
//ここからメイン関数
//=====================================================================//
function init() {

    //geojsonを読み取るための変数
    parser = new OpenLayers.Format.GeoJSON();

    //====================================================================//
    //ここから、map、レイヤーの設定
    //====================================================================//
    // マップのオプション設定
    var options = {
        allOverlays: true,
        controls: [
            new OpenLayers.Control.Zoom(),        //ズーム
            new OpenLayers.Control.Navigation(),  //カーソル移動
            new OpenLayers.Control.Attribution(),  //なんかいろいろ
        ],
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:900913"),
        maxExtent: new OpenLayers.Bounds(139, 36, 141, 37).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),
        restrictedExtent: new OpenLayers.Bounds(139, 36, 141, 37).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"))


        //表示する領域の設定

    };

    // マップの初期化(オプション付与
    map = new OpenLayers.Map("map", options);

    map.isValidZoomLevel = function (zoomLevel) {
        return ( (zoomLevel != null) && (zoomLevel >= 10) );
    };

    //geojsonを読み取るための変数
    parser = new OpenLayers.Format.GeoJSON();

    //OpenStreetMapレイヤーの作成
    mapnik = new OpenLayers.Layer.OSM();

    mapnik.displayOutsideMaxExtent = false;

    //水戸市の境界線を描画するレイヤーを作成
    vector = new OpenLayers.Layer.Vector("水戸市");

    //現在地を表示するレイヤーの作成
    hereVector = new OpenLayers.Layer.Vector("現在地");

    //追加要素を表示するレイヤーを作成
    addVector = new OpenLayers.Layer.Vector("AED");

    //=================================================//
    //それぞれのマップ用のレイヤーを作成する
    //=================================================//
    normalMap(2);

    //水戸市の町域一つ一つを選択できるようにする
    selectControl = new OpenLayers.Control.SelectFeature(vector);
    map.addControl(selectControl);
    selectControl.activate();

    //イベントが起こった時の処理
    vector.events.on({
        //町域が選択されたときはこの関数を呼ぶ
        'featureselected': onFeatureSelect,
        //選択が外れた時はこの関数を呼ぶ
        'featureunselected': onFeatureUnselect
    });

    selectControl2 = new OpenLayers.Control.SelectFeature(addVector);
    map.addControl(selectControl2);
    selectControl2.activate();

    addVector.events.on({
        //場所が選択されたときはこの関数を呼ぶ
        'featureselected': SelectMapFeatures,
        //選択が外れた時はこの関数を呼ぶ
        'featureunselected': UnselectMapFeatures
    });

    //作成した3つのレイヤーをmapに追加する
    map.addLayers([mapnik, vector, hereVector]);

    // 地図の中央座標を指定
    var lonLat = new OpenLayers.LonLat(140.450997, 36.379503)
        .transform(
            new OpenLayers.Projection("EPSG:4326"),
            new OpenLayers.Projection("EPSG:900913")
        );

    // 中央とズーム値を指定
    map.setCenter(lonLat, 12);

    //=======================================================================//
    //各種地図が選択されたときの処理
    //=======================================================================//
    $(".mapname").click(function () {
        //押された項目の名前を取得する
        mapvalue = $(this).attr("value");
        //情報表示スペースを空にする
        $("div[id=info]").empty();
        //事前に項目が選択されていた場合、その項目の点を消しておく
        if (addVector.removeAllFeatures()) {
            map.removeMap(addVector);
        }
        //基礎情報項目が選択されていた場合、水戸市の地図を作りなおす
        /*if (mapFlug == 1 && mapvalue != "population" && mapvalue != "population_density" && mapvalue != "normal") {
            vector.removeAllFeatures();
            normalMap(2);
            //フラグを元に戻しておく
            mapFlug = 0;
        }*/
        //=======================================================================//
        //ajax通信で各種地図の関数を呼び出している
        //=======================================================================//
        $.ajax({
            url: "normal.html",
            success: function () {
                //人口マップが選択されていた場合
                if (mapvalue == "population") {
                    //色分け表示画面を消しておく
                    $("div.break_down").hide();
                    populationMap();
                    mapFlug = 1;
                }
                //人口密度マップが選択されていた場合
                else if (mapvalue == "population_density") {
                    //色分け表示画面を消しておく
                    $("div.break_down").hide();
                    population_densityMap();
                    mapFlug = 1;
                }
                //基礎地図が選択されていた場合
                else if (mapvalue == "normal") {
                    //色分け表示画面を消しておく
                    $("div.break_down").hide();
                    normalMap(2);
                    mapFlug = 1;
                }
                else if (mapvalue == "shelter") {
                    visionShelter();
                }
                else if (mapvalue == "Welfare_shelter") {
                    visionWelfare();
                }
                else if (mapvalue == "Flood_shelter") {
                    visionFlood();
                }
                else if (mapvalue == "Tsunami_shelter") {
                    visionTunami();
                }
                else if (mapvalue == "Fire_shelter") {
                    visionFire();
                }
                else if (mapvalue == "AED") {
                    visionAED();
                }
                else if (mapvalue == "well") {
                    visionWell();
                }
                else if (mapvalue == "water_supply") {
                    visionWater();
                }

            },
            error: function () {
                alert("hoiho");
            }
        });
    });

    //現在地表示ボタンが押された場合、現在値を表示する
    $("div[id=herePlace]").click(function () {
        geoLocation();
    })

    $("div[id=showRoute]").click(function (){
        console.log(herelat);
        console.log(herelon);

        $url = "http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&flat=36.5720&flon=140.6432&tlat=" + feature.attributes['lat'] + "&tlon=" + feature.attributes['lon'] + "&v=bicycle&fast=1&layer=mapnik";
        console.log($url);

        /*$.getJSON($url, function(geojson){
           console.log(geojson);
        });*/

        $.ajax({
            url: "ajax.php?url=http://www.yournavigation.org/api/1.0/gosmore.php?format=geojson&flat=36.5720&flon=140.6432&tlat=36.3582232&tlon=140.4773157&v=bicycle&fast=1&layer=mapnik",
            type: "GET",
            dataType: "json",
            //成功したらjsonデータをコールバックして返す
            success: function(geojson){
                alert("success");
                console.log(geojson);
                console.log(geojson.crs);
                console.log(geojson.properties);

                var json = parser.read(geojson);
                console.log(json);


                /*var points = new Array();
                for(var i = 0; i < geojson.length ; i++){
                    points[i] = new OpenLayers.Geometry.Point(geojson.coordinates[i][1], geojson.coordinates[i][2]);
                    points[i].transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
                }*/

                var line_style = {
                    'strokeColor': '#f39800',
                    'strokeWidth': 3.0
                };

                //console.log(points);

                //var route = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(points), null, line_style);

                hereVector.addFeatures(geojson);
                hereVector.style = line_style;
            },
            //失敗したらエラーメッセージの表示
            error: function(){
                alert("error");
            }
        });
    })
}

