<?php

function getTrackAnalysis($tracks) {//, $client_id, $token) {
	echo "song name: " . $tracks['track']['name'] . "; ";
	echo "artist: ";
	$artists=$tracks['track']['artists'];
        foreach($artists as $artist) {
                echo $artist['name'] . "; ";
        }
        echo "song id: " . $tracks['track']['id'] . "<br>";

}
$client_id="fb7853c47e8d4db0a7118e65d99d4b4b";

$token=file_get_contents("token-playlist.txt");
$token=preg_replace('/.* /', "", $token);
echo $token . "<br>";
$tracksjson=file_get_contents("playlist.json");
var_dump($tracksjson);
$arr=json_decode($tracksjson, true);
$items=$arr['tracks']['items'];
foreach($items as $item) {
	getTrackAnalysis($item);
}

?>
