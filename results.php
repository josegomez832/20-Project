<!doctype html>
<html lang="en" ng-app="plunker">
<head>
  <meta charset="utf-8">
  <title>jQuery.getJSON demo</title>
  <style>
  html, body,  { height: 100%; margin: 0; padding: 0;}
  img.img,a img.img {
    height: auto;
    width:100%;
    float: left;
  }
  a img.img{
    display: block;
  }
  ul{
    margin:0;
    padding:0;
    width:100%;
    display: block;
  }
  ul li{
    width:25%;
    margin:0;
    display: block;
    float: left;
  }
  #map, #google{
  	width:100%;
  	height:400px;
  }
  </style>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  
  <script src="moment.js"></script>
  <script src="livestamp.js"></script>
  <script src="Chart.min.js"></script>
  <script src="js/history.js"></script>
  <link rel="stylesheet" href="css/rzslider.css" />
  <link rel="stylesheet" href="css/demo.css" />
</head>
<body ng-controller="MainCtrl">
   <form action="results.php" method="get">
    <input type="text" id="location" placeholder="Search City" name="location"/>
    <button type="submit">Submit</button>
  </form>
  <div id="google"></div>
<div id="feed">
<ul></ul>
</div>
<p id="demo"></p>
  {{ slider_data.value }}   
  <rzslider
                    rz-slider-floor="1"
                    rz-slider-ceil="5"
                    rz-slider-step="1"
                    rz-slider-precision="1"
                    rz-slider-model="slider_data.value"
                    rz-slider-on-change="onChange()"></rzslider>

  <?php
    if(!empty($_GET['location'])){
      $maps_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($_GET['location']);
      $maps_json = file_get_contents($maps_url);
      $maps_array = json_decode($maps_json, true);
      $lat = $maps_array['results'][0]['geometry']['location']['lat'];
      $lng = $maps_array['results'][0]['geometry']['location']['lng'];
    }

    $client_id = '9544d2a52af545ea911ea49e6c17e3a1';
    $client_secret = '4a95cc1c09664b16b0ed17d105144d85';
    $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";

    $request = "https://api.instagram.com/v1/media/search?lat=".$lat."&lng=".$lng."&distance=1000&client_id=".$client_id;
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE); 
?>
 


</ul>
</div>




<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKB7Oy4gwlex36Tm9Pq676FR6C8fwJq7k"></script>
<script type="text/javascript">

    var url = "https://api.instagram.com/v1/media/search?lat=<?php echo $lat; ?>&lng=<?php echo $lng; ?>&distance=1000&client_id=<?php echo $client_id; ?>&count=40&callback=?";
      //This pulls the instagram images
      $.ajax({
      type: 'GET',
      async: false,
      url:url,
      dataType: "json",
      success: function (response) {
        //http://techmonks.net/instagram-using-the-api/
        for(var i = 0; i < 40; i++){
          $("#feed ul").append("<li><a target='_blank' href='"+response.data[i].link +"'><img src='"+response.data[i].images.low_resolution.url+"'/></a></li>");
        }          
      },
      error: function (xhr, status) {
        //handle errors
        console.log('Instagram API not working');
      }
    }); 

    //Google maps integration
    $.getJSON(url, function(data){
        var places =  data.data[1].link;
        var coor1 = data.data[1].location.latitude;
        var coor2 = data.data[1].location.longitude;
        var locations = [];
        var userInfo = data.length;
         for(var a = 0; a < 40; a++){
            var name = data.data[a].user.username;
            var lat = data.data[a].location.latitude;
            var lng = data.data[a].location.longitude;
            var mapStuff = [name, lat, lng];
            locations.push(mapStuff);
            //console.log('"'+name+'",'+lat+','+lng);
            //console.log(locations);            
          }
        
        $('#demo').html("lat="+coor1+"lng="+coor2);
         
         var coordinates = new google.maps.LatLng(coor1, coor2);

         var map = new google.maps.Map(document.getElementById('google'),{
            zoom: 10,
            center: coordinates           
         });

          var infowindow = new google.maps.InfoWindow();
          var marker, i;
          var markers = new Array();
          for (i=0; i<locations.length;i++){
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map:map,
            animation: google.maps.Animation.DROP
          });      
      
          markers.push(marker);
          google.maps.event.addListener(marker, 'click', (function(marker, i){
            return function(){
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
              }
            })(marker, i));
          } 

          google.maps.event.addListener(map, 'center_changed', function(){
              var control_center = map.getCenter();
              var lng = control_center.lng();
              var lat = control_center.lat();
              //parent.location.hash="&lng="+lng +"&lat="+lat;
              //console.log(map.getCenter());
              //console.log(lng +', ' +lat);
              console.log('center_changed');
          });

    


    });

  
</script>   
<script>
  </script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
<script src="js/rzslider.js"></script>
<script>
    var app = angular.module('plunker', ['rzModule']);
    app.controller('MainCtrl', function($scope)
    {             
      $scope.slider_data = {value: 1};
      $scope.otherData = {value: 10};

      $scope.onChange = function() {
        console.info('changed', $scope.slider_data.value);
        $scope.otherData.value = $scope.slider_data.value * 10;
      };
    });
</script>
</body>
</html>