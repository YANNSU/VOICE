//================================================//
//端末によって違う処理
//================================================//
$(document).ready(function () {
    //スマホ端末時のみ発動するスクリプト
    if ($(window).width() < 800) {
        //メニューボタンを押したときの処理
        $('div.menu_button').click(function () {
            //出てたらしまう
            if ($('div.emergency_menu').hasClass('on')) {
                $('div.emergency_menu').animate({'marginLeft': '0px'}, 500);
                $('div.emergency_menu').removeClass('on');
            }
            //出てなかったら出す
            else {
                $('div.emergency_menu').animate({'marginLeft': '70%'}, 500);
                $('div.emergency_menu').addClass('on');
            }
            //これをやっておかないと次の全画面クリック判定に引っかかる
            event.stopPropagation();
        });
        //メニューボタン以外がクリックされたときの処理
        $(document).click(function () {
            //メニューをしまう
            $('div.emergency_menu').animate({'marginLeft': '0px'}, 500);
            $('div.emergency_menu').removeClass('on');
        });
        //メニューバーを触っても格納しないようにする
        $('div.emergency_menu').click(function () {
            event.stopPropagation();
        });
        $(".mapname").click(function () {
            $('div.emergency_menu').animate({'marginLeft': '0px'}, 1000);
            $('div.emergency_menu').removeClass('on');
        });
    }
});


//===================================================================//
//ここがローディング画面のコード
//===================================================================//
$(window).load(function () {
    //読み込みが終わったら隠す　つまり出しっぱなし
    $('#now-loading').hide();
});

//=====================================================================//
//ここはグローバル変数
//=====================================================================//
var map;
var flug; //今マップ上の点が選択されているか判断するためのフラグ
var lifeLineFlug; //現在ライフラインの項目が選択されているかどうかを判定する変数
var mapvalue;

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
        allOverlays: false,
        controls: [
            new OpenLayers.Control.Zoom(),        //ズーム
            new OpenLayers.Control.Navigation(),  //カーソル移動
            new OpenLayers.Control.Attribution(),  //なんかいろいろ
        ],
        eventListeners: {
            'move': null,
            'movestart' : null
        },
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: new OpenLayers.Bounds(139, 36, 141, 37).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),
        restrictedExtent: new OpenLayers.Bounds(139, 36, 141, 37).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"))

    };

    // マップの初期化(オプション付与
    map = new OpenLayers.Map("map", options);

    map.isValidZoomLevel = function (zoomLevel) {
        return ( (zoomLevel != null) && (zoomLevel >= 10) );
    };

    //OpenStreetMapレイヤーの作成
    mapnik = new OpenLayers.Layer.OSM();

    //水戸市の境界線を描画するレイヤーを作成
    vector = new OpenLayers.Layer.Vector("水戸市");

    selectControl = new OpenLayers.Control.SelectFeature(vector,{});

    selectControl.handlers.feature.stopDown = false;

    map.addControl(selectControl);

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

    vector.events.register('featureselected', this, onFeatureSelect);
    vector.events.register('featureunselected', this, onFeatureUnselect);

    //イベントが起こった時の処理
    vector.events.on({
        //町域が選択されたときはこの関数を呼ぶ
        'featureselected': onFeatureSelect,
        //'move': null,
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
        //詳細情報ボタンを隠す
        $("#detail").hide();
        //事前に項目が選択されていた場合、その項目の点を消しておく
        if (addVector.removeAllFeatures()) {
            map.removeMap(addVector);
        }
        //色分け表示画面を取り合えず消しておく
        $("div.break_down").hide();
        //ライフライン項目が選択されていた場合、水戸市の地図を作りなおす
        if(lifeLineFlug == 1 && mapvalue != "life_line_water" && mapvalue != "life_line_elect"){
            vector.removeAllFeatures();
            normalMap(2);
            //フラグを元に戻しておく
            lifeLineFlug = 0;
        }
        //=======================================================================//
        //ajax通信で各種地図の関数を呼び出している
        //=======================================================================//
        $.ajax({
            url: "normal.html",
            success: function () {
                //臨時避難所が選択された場合の処理
                if (mapvalue == "temporary_shelter") {
                    visionTemporaryShelter();
                }//給水・物資拠点が選択された場合の処理
                else if (mapvalue == "goods_supply") {
                    visionGoodsSupply();
                }//ライフライン(水道)が選択された場合の処理
                else if (mapvalue == "life_line_water") {
                    visionLifeWater();
                    //ライフラインフラグを立てておく
                    lifeLineFlug = 1;
                }//ライフライン(電気)が選択された場合の処理
                else if (mapvalue == "life_line_elect") {
                    visionLifeElect();
                    //ライフラインフラグを立てておく
                    lifeLineFlug = 1;
                }//遺体安置所が選択された場合の処理
                else if (mapvalue == "morgue") {
                    visionMorgue();
                }//通行止め情報が選択された場合の処理
                else if (mapvalue == "road_closed") {
                    visionRoadClosed();
                }//避難所情報が選択された場合の処理
                else if (mapvalue == "shelter") {
                    visionShelter();
                }//福祉避難所が選択された場合の処理
                else if (mapvalue == "Welfare_shelter") {
                    visionWelfare();
                }//洪水避難所が選択された場合の処理
                else if (mapvalue == "Flood_shelter") {
                    visionFlood();
                }//津波避難所が選択された場合の処理
                else if (mapvalue == "Tsunami_shelter") {
                    visionTunami();
                }//火災避難所が選択された場合の処理
                else if (mapvalue == "Fire_shelter") {
                    visionFire();
                }
            },
            error: function () {
                alert("hoiho");
            }

        });
    });

    //=============================================================//
    //現在地表示ボタンが押された場合、現在値を表示する関数を呼び出す
    //=============================================================//
    $("div[id=herePlace]").click(function () {
        geoLocation();
    })

    //==============================================================//
    //詳細情報を押すと、その施設の詳細情報を表示するwindowを開きます
    //==============================================================//
    $("#detail").click(function () {

        $("body").append('<div id="modal-bg"></div>');

        //画面中央を計算する関数を実行
        modalResize();

        //関数を呼び出してその施設の詳細情報が存在するか調べる
        connectShelterGoods(feature.attributes['施設名'], function (result) {
            //データがなかった場合
            if (result.YesNo == "no") {
                
                var createInfo = confirm("まだデータが無いようです\n作成しますか？");
                if(createInfo == true){
                    window.location = "Edit/login.php?name=" + feature.attributes['施設名'] + "";
                }
            }
            //データがあった場合は情報を表示
            else {
                var detailTable = "";
                //必要な情報をテーブルに格納する
                for (var key = "施設名" in result) {
                    if (key != "id" && key != "YesNo") {
                        //文字列に選択された町域の情報を格納
                        if (key == "施設名" || key == "更新日") {
                            detailTable += '<tr><th class="small">' + key + '</th><td>' + result[key] + '</td></tr>';
                        }
                        else {
                            detailTable += '<tr><th class="large">' + key + '</th><td>' + result[key] + '</td></tr>';
                        }
                    }
                }
                //格納した情報をモーダルウィンドウに追加する
                $("table[id=modal-table]").append(detailTable);

                //モーダルウィンドウを表示
                $("#modal-bg,#modal-main").fadeIn(400);

                //画面のどこかをクリックしたらモーダルを閉じる
                $("#modal-bg,#modal-main").click(function () {
                    $("#modal-main,#modal-bg").fadeOut("slow", function () {
                        //挿入した<div id="modal-bg"></div>を削除
                        $('#modal-bg').remove();
                        //テーブルの内容も削除
                        $("table[id=modal-table]").empty();
                    });
                });

                //画面の左上からmodal-mainの横幅・高さを引き、その値を2で割ると画面中央の位置が計算できます
                $(window).resize(modalResize);
            }
        })
    })
    //============================================================//
    //============================================================//

}


//===========================================================//
//phpを呼び出して、避難所の詳細情報を取ってくる関数
//第一引数…呼び出すデータのキーワード
//第二引数…コールバック関数
//===========================================================//
function connectShelterGoods(name, callback) {
    $.ajax({
        url: "goods_connect.php",
        type: "POST",
        dataType: "json",
        data: {name: name},
        //成功したらjsonデータをコールバックして返す
        success: function (json) {
            callback(json);
        },
        //失敗したらエラーメッセージの表示
        error: function () {
            alert("error");
        }
    });
}
//==========================================================//
//モーダルウィンドウの位置を図るための関数
//==========================================================//
function modalResize() {

    var w = $(window).width();
    var h = $(window).height();

    var cw = $("#modal-main").outerWidth();
    var ch = $("#modal-main").outerHeight();

    //取得した値をcssに追加する
    $("#modal-main").css({
        "left": ((w - cw) / 2) + "px",
        "top": ((h - ch) / 2) + "px"
    });
}
