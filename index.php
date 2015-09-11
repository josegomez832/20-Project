<?php
  $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";
  $baseURL = 'https://api.instagram.com/v1/users/';
  $endpoint = '/media/recent/?access_token=' . $token .'&count=1'; //replace "CLIENT-ID"
  $portlandID = '231329742';
  $sanFranID = '12270527';
  $nyID = '289258065';

  $sanfranciso = $baseURL . $sanFranID . $endpoint;
  $sf_json = file_get_contents($sanfranciso);
  $results = json_decode($sf_json, true);
  //sf id = 12270527
  $ny = $baseURL . $nyID . $endpoint;
  $ny_json = file_get_contents($ny);
  $nyResults = json_decode($ny_json, true);

  $portland = $baseURL . $portlandID . $endpoint;
  $portland_json = file_get_contents($portland);
  $portland_results = json_decode($portland_json, true);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  #map{
  	width:100%;
  	height:400px;
  }
  </style>
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script src="moment.js"></script>
  <script src="livestamp.js"></script>
  <script src="Chart.min.js"></script>
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet" >
</head>
<body>

    <div class="container">
      <div class="jumbotron">
        <h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h1>
          <form action="results.php" method="get">
            <input type="text" id="location" placeholder="Search" name="location"/>
            <button type="submit" class="btn">Submit</button>
          </form>
      </div>
    </div> <!-- /container -->


    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h1>#sanfrancisco</h1>
          
          <?php 
                if(!empty($results)){
                   foreach($results['data'] as $key=>$image){
                      echo '<img src="'.$image['images']['low_resolution']['url'].'" alt=""/><br/>';
                    }
                  }
          ?>
        </div>

        <div class="col-md-4">
          <h1>#newyork</h1>           
          <?php 
                if(!empty($nyResults)){
                   foreach($nyResults['data'] as $key=>$image){
                      //echo '<div class="item">';
                      echo '<img src="'.$image['images']['low_resolution']['url'].'" alt=""/>';
                      //echo '</div>';
                    }
                  }
          ?>
        </div>
         

        <div class="col-md-4">
          <h1>#portland</h1>
          <?php 
                if(!empty($portland_results)){
                   foreach($portland_results['data'] as $key=>$image){
                      echo '<img src="'.$image['images']['low_resolution']['url'].'" alt=""/><br/>';
                    }
                  }
          ?>
          
        </div>
      </div>
    </div>


    <div class="container">
      <footer class="footer">
        <p>&copy; Company 2014</p>
      </footer>
    </div>


</body>
</html>