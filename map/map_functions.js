//=========================================================//
//通常のマップを表示する関数
//===================================================
//=========================================================//
//通常の地図を表示する関数
//引数…表示する地図の種類を指定するための引数
//=========================================================//
function normalMap(kind) {
    //一旦水戸市のデータを削除する
    vector.removeAllFeatures();
    //水戸市の境界線の情報が詰まったgeojsonデータを読み込んでいる
    $.getJSON("city/mito/mito.geojson", function (data) {
        var features = parser.read(data);
        console.log(data);
        //読み取ったデータを一つ一つ判定していく
        for (var i = 0; i < features.length; i++) {
            //緯度経度変換
            features[i].geometry =
                features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            if (kind == 2) {
                features[i].style = {
                    'strokeColor': '#f39800',
                    'fillColor': '#f39800',
                    'strokeWidth': 1.0,
                    'fillOpacity': 0.2
                };
            }
        }
        //できたデータをレイヤーに描画する
        vector.addFeatures(features);
    });
}


function riverMap() {
    //水戸市の境界線の情報が詰まったgeojsonデータを読み込んでいる
    $.getJSON("city/mito/river.geojson", function (data) {
        var features = parser.read(data);
        //読み取ったデータを一つ一つ判定していく
        for (var i = 0; i < features.length; i++) {
            //緯度経度変換
            features[i].geometry =
                features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            features[i].style = {
                'strokeColor': '#00afcc',
                'strokeWidth': 1.0,
                'strokeOpacity': 0.7
            }
        }
        //できたデータをレイヤーに描画する
        vector.addFeatures(features);
    });
}


//=========================================================//
//人口マップを表示する関数
//=========================================================//
function populationMap() {
    //一旦水戸市のデータを削除する
    vector.removeAllFeatures();

    //色分け情報表示画面を表示
    $("#P").show();
    $.getJSON("city/mito/mito.geojson", function (data) {
        var features = parser.read(data);
        //読み取ったデータを一つ一つ判定していく
        for (var i = 0; i < features.length; i++) {
            //緯度経度変換
            features[i].geometry =
                features[i].geometry.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            // 人口色付け
            setColorMap(features[i]);
        }
        vector.addFeatures(features);
    });
}

function setColorMap(population){
    //100人以下の地域
    if (population.attributes.JINKO < 100) {
        population.style = {
            'strokeColor': '#dddd00',
            'fillColor': '#ffff00',
            'fillOpacity': 0.7
        };
    }//300人以下の地域
    else if (population.attributes.JINKO < 300) {
        population.style = {
            'strokeColor': '#ddad00',
            'fillColor': '#ffcf00',
            'fillOpacity': 0.7
        };
    }//500人以下の地域
    else if (population.attributes.JINKO < 500) {
        population.style = {
            'strokeColor': '#dd7d00',
            'fillColor': '#ff9f00',
            'fillOpacity': 0.7
        };
    }//1000人以下の地域
    else if (population.attributes.JINKO < 1000) {
        population.style = {
            'strokeColor': '#dd4d00',
            'fillColor': '#ff6f00',
            'fillOpacity': 0.7
        };
    }//5000人以下の地域
    else if (population.attributes.JINKO < 5000) {
        population.style = {
            'strokeColor': '#dd1d00',
            'fillColor': '#ff3f00',
            'fillOpacity': 0.7
        };
    }//5000人以上の地域
    else {
        population.style = {
            'strokeColor': '#dd0000',
            'fillColor': '#ff0000',
            'cursor': "pointer",
            'fillOpacity': 0.7
        };
    }
    /*var selectStyle = new OpenLayers.Style({
        'strokeColor': '#ffffff'
    });
    var styleMap = new OpenLayers.StyleMap({"default" : defaultStyle, "select" : selectStyle});

    population.styleMap = styleMap;*/
}
//=========================================================//
//人口密度マップを表示する関数
//=========================================================//
function population_densityMap(){
    //一旦水戸市のデータを削除する
    vector.removeAllFeatures();
    //色分け情報表示画面を表示
    $("#PD").show();
    $.getJSON("city/mito/mito.geojson", function (data) {
        features = parser.read(data);
        //読み取ったデータを一つ一つ判定していく
        for (var i = 0; i < features.length; i++) {
            //緯度経度変換
            features[i].geometry = features[i].geometry
                .transform(new OpenLayers.Projection("EPSG:4326"),
                    new OpenLayers.Projection("EPSG:900913"));
            //人口密度を計算
            var density = features[i].attributes.JINKO / (features[i].attributes.AREA / 1000000);
            //10人以下
            setColorDensity(density, features[i]);
        }
        vector.addFeatures(features);
    });
}

function setColorDensity(density, feature){
    if (density < 10) {
        feature.style = {
            'strokeColor': '#dddd00',
            'fillColor': '#ffff00',
            'fillOpacity': 0.7
        };
    }//100人以下の地域
    else if (density < 100) {
        feature.style = {
            'strokeColor': '#ddad00',
            'fillColor': '#ffcf00',
            'fillOpacity': 0.7
        };
    }//500人以下の地域
    else if (density < 500) {
        feature.style = {
            'strokeColor': '#dd7d00',
            'fillColor': '#ff9f00',
            'fillOpacity': 0.7
        };
    }//1000人以下の地域
    else if (density < 1000) {
        feature.style = {
            'strokeColor': '#dd4d00',
            'fillColor': '#ff6f00',
            'fillOpacity': 0.7
        };
    }//5000人以下の地域
    else if (density < 5000) {
        feature.style = {
            'strokeColor': '#dd1d00',
            'fillColor': '#ff3f00',
            'fillOpacity': 0.7
        };
    }//5000人以上の地域
    else {
        feature.style = {
            'strokeColor': '#dd0000',
            'fillColor': '#ff0000',
            'fillOpacity': 0.7
        };
    }
}

//===========================================================//
//選択した町域の情報を表示する関数
//===========================================================//
function onFeatureSelect(evt) {
    //選択された町域の情報を取得する
    feature = evt.feature;
    flug = 1;
    //文字列に選択された町域の情報を格納
    table = '<table>';
    table += '<tr class="parameter"><td>町名</td><td>' + feature.attributes["MOJI"] + '</td></tr>';
    table += '<tr class="parameter"><td>人口</td><td>' + feature.attributes["JINKO"] + '</td></tr>';
    table += '<tr class="parameter"><td>世帯</td><td>' + feature.attributes["SETAI"] + '</td></tr>';
    table += '<table>';
    //文字列を指定の場所に表示
    $("div[id=info]").append(table);
}

//===========================================================//
//町域の選択が外れた時の処理を行う関数
//===========================================================//
function onFeatureUnselect(evt) {
    //町域の情報を取得
    feature = evt.feature;
    //前にどこか選択されていたら、その情報を消す
    if (flug) {
        $("div[id=info]").empty();
        flug = 0;
    }
}