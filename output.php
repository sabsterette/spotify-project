<?php
echo "<!DOCTYPE html> \n <html> \n <body>";

class Song {
	public $id;
	public $name;
	public $artists;
	public $loudness;
	public $key;
	public $danceability;
	function set_id($id) {$this->id = $id;}
	function get_id() {return $this->id;}
	function set_name($name) {$this->name = $name;}
	function get_name() {return $this->name;}
	function set_artists($artists) {$this->artists = $artists;}
        function get_artists() {return $this->artists;}
	function set_loudness($loudness) {$this->loudness = $loudness;}
        function get_loudness() {return $this->loudness;}
        function set_key($key) {$this->key = $key;}
        function get_key() {return $this->key;}	
	function set_danceability($danceability) {$this->danceability=$danceability;}
	function get_danceability() {return $this->danceability;}
}

function getTrackAnalysis($tracks, $token) {//, $client_id, $token) {
	$songID=$tracks['track']['id'];
	exec("curl -X GET \"https://api.spotify.com/v1/audio-features/$songID\" -H \"Authorization: Bearer $token\" > feature.json");
	
	$audio_features=file_get_contents("feature.json");
	$features=json_decode($audio_features, true);
	$song=new Song();
	$song->set_id($tracks['track']['id']);
	$song->set_name($tracks['track']['name']);
	$song->set_artists($tracks['track']['artists']);
	$song->set_loudness($features['loudness']);
	$song->set_key($features['key']);
	$song->set_danceability($features['danceability']);
	return $song;
}

function cmpKey($song1, $song2) {
	return $song1->get_key() - $song2->get_key();
}
function cmpLoudness($song1, $song2) {
	if($song1->get_loudness() == $song2->get_loudness()) return 0;
	return ($song1->get_loudness() < $song2->get_loudness()) ? -1 : 1;
}
function cmpDanceability($song1, $song2) {
	if($song1->get_danceability() == $song2->get_danceability()) return 0;
	return ($song1->get_danceability() < $song2->get_danceability()) ? -1 : 1;
}

$letterKey = array(0=>"C", 1=>"C#", 2=>"D", 3=>"D#Eb", 4=>"E", 5=>"F", 6=>"F#/Gb", 7=>"G",8=>"G#/Ab", 9=>"A", 10=>"A#/Bb", 11=>"B");

$client_id="fb7853c47e8d4db0a7118e65d99d4b4b";

$token=file_get_contents("token-playlist.txt");
$token=preg_replace('/;.*/', "", $token);
echo nl2br("SORT SONGS BY\n");
echo "<form action='output.php' method='post'>";
echo "<select name='formSort'> \n <option value='key'>Key</option> \n";
echo "<option value='loudness'>loudness</option> \n <option value='danceability'>Danceability</option> \n";
echo "</select>\n";
echo "<input name='submit' type='submit' value='SORT'> <br>";

if(isset($_POST['formSort'])) {
	$sortby=$_POST['formSort'];
} else {
	//by default sort by key
	$sortyby='key';
}

echo "SORTING BY: $sortby" . "<br>";
$tracksjson=file_get_contents("playlist.json");
$arr=json_decode($tracksjson, true);
if(array_key_exists('tracks', $arr)) {
	$items=$arr['tracks']['items'];
} else {
	$items=$arr['items'];
}
$songarray=array();
foreach($items as $item) {
	$songarray[]=getTrackAnalysis($item, $token);
}
switch($sortby) {
	case "key":
		usort($songarray, "cmpKey");
		echo "KEYY";
		break;
	case "loudness":
		echo "LOUD";
		usort($songarray,"cmpLoudness");
		break;
	case "danceability":
		echo "DANCE";
		usort($songarray, "cmpDanceability");
		break;
}
foreach($songarray as $song) {
	$testkey=$song->get_key();
	if(!isset($testkey)) {
		echo "<a href='spotify.php'> PLEASE GO BACK AND AUTHORIZE AGAIN </a>"; 
		return 0;
	}
}
echo "<table> \n\t <tr> \n\t\t<th> Song Name </th> \n\t\t<th> Key </th>";
echo "\n\t\t<th> Loudness </th>\n\t\t<th> Danceability </th>\n\t\t<th> Artists </th>\n\t</tr>";
foreach($songarray as $song) {
	echo "\n\t<tr>";
	echo "\n\t\t<td>" . "<a href='https://open.spotify.com/track/" . $song->get_id() . "' target=\"_blank\">" . $song->get_name() ."</a></td>";
	echo "\n\t\t<td>" . $letterKey[$song->get_key()] . "</td>";
	echo "\n\t\t<td>" . $song->get_loudness() . "</td>";
	echo "\n\t\t<td>" . $song->get_danceability() . "</td>";
        echo "\n\t\t<td>\n\t\t\t<ul>";
	$artists=$song->get_artists();
        foreach($artists as $artist) {
                echo "\n\t\t\t<li>" . $artist['name'] . "</li>";
        }
        echo "\n\t\t\t</ul> \n\t\t</td>";
	echo "\n\t</tr>";
}
echo "</table> \n";
echo "</body> \n </html>";
?>
