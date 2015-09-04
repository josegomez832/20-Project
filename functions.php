<?



function instagram_tag(){
	$client_id = '9544d2a52af545ea911ea49e6c17e3a1';
    $client_secret = '4a95cc1c09664b16b0ed17d105144d85';
    $token = "31266149.9544d2a.8030b2bbca864d009801eab4646ff5c2";
	//Need to create houston as a variable
    $tag_count = "https://api.instagram.com/v1/tags/losangeles?access_token=".$token;
    $tag_response = file_get_contents($tag_count);
    $tags = json_decode($tag_response, true);
    
   // print_r($tags);

    echo $tags['data']['media_count'];
}

function instagram_location(){

}
