<?php
    /*
        To create your own client id, client secret and token visit https://instagram.com/developer/
        Then go to Manage Clients
        Register a New Client
        Use "http://localhost" as your website uri and redirect uri
        new id and secret should be given to you
        to get token visit https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token
        Replace CLIENT_ID with your id and REDIRECT-URI with http://localhost, press enter and should redirect to you a url with your token
    */
    //http://davidwalsh.name/xbox-api
    $client_id = '9544d2a52af545ea911ea49e6c17e3a1';
    $client_secret = '4a95cc1c09664b16b0ed17d105144d85';
    $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";
    //http://jelled.com/instagram/access-token
    //if getting a 403 error visit https://teamtreehouse.com/forum/oauth-error-403

    //Need to create lat and lng as variables
    $request = "https://api.instagram.com/v1/media/search?lat=29.7575275&lng=-95.3580718&distance=3000&client_id=".$client_id;
    $response = file_get_contents($request);
    $results = json_decode($response, TRUE);
    echo '<div style="height:400px;overflow:scroll;">';
    echo "<pre>";
   print_r($results);
    echo "</pre>";
    echo '</div>';
 
?>

  <h1>Hashtag</h1>
  <ul>
    <?php foreach($results['data'] as $info){ ?>
    <li>    
      <span data-livestamp="<?php echo $info['created_time']; ?>"></span>
      <a href="<?php echo $info['link']; ?>" target="_blank">
        <img src="<?php echo $info['images']['standard_resolution']['url']; ?>" class="img" />
      </a>
      <img src="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678087-heart-128.png" width="25px" height="25px" />
      <h4><?php echo $info['likes']['count']; ?></h4>
       <p>
         <?php //foreach($info['tags'] as $tags){ 
              //<strong>#<?php echo $tags; </strong>, 
        //} ?>
    </p></li>
<?php }?>
</ul>