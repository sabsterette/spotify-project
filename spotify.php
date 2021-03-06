<?php

$client_id="fb7853c47e8d4db0a7118e65d99d4b4b";
$client_secret="d1c318925a6c4255be2fa7fbc841066e";

$redirect_uri="https://open.spotify.com/";
$authorization_endpoint="https://accounts.spotify.com/authorize?client_id=$client_id&response_type=token&redirect_uri=$redirect_uri";
$FILE="/var/www/html/case/team123/printtest";
echo "<h1>SPOTIFY PLAYLIST ANALYZER</h1>";

$token;
$playlist;

echo "<h2> How to use this? </h2> <br>";
echo "<p>Want to style your playlist in a certain way so that it plays songs in a certain order according to how the song feels? This tool will allow you to sort any playlist based on the key of the songs, how loud the songs are, and how danceable (rated by Spotify) the songs are! <br> <b>Click on the authenticate link below</b> then when you get redirected after hitting agree <b>copy the resulting URL and paste it on the left</b>. Then <b>paste the link to the playlist on the right</b></p>"; 

echo nl2br("\n<a href=\"$authorization_endpoint\" target=\"_blank\"> please authenticate </a>\n");
if (empty($_GET["tokenurl"])) {
	echo "<form action='spotify.php' method='GET'>\n";
	echo "<textarea name='tokenurl'>enter your url with token here</textarea>\n";
	echo "<textarea name='playlisturl'> enter playlist url </textarea>" . "\n";
	echo "<input type=submit>\n</form>\n";
} else {
	$token=$_GET["tokenurl"];
	$token=preg_replace('/.*token=/', "", $token);
	$token=preg_replace('/&token.*/', "", $token);
        $playlist=$_GET["playlisturl"];
	$playlist=preg_replace('/.*list\//', "", $playlist);
	if(empty($playlist) || empty($token)) {
		echo "one or more of URLS are incorrect, please try again";
	}
	else {
		$token_play_file=fopen("token-playlist.txt", "w") or die("can't open");
		fwrite($token_play_file, $token);
		fwrite($token_play_file, ";;");
		fwrite($token_play_file, $playlist);
		fclose($token_play_file);
		exec("curl -X GET \"https://api.spotify.com/v1/playlists/$playlist/tracks\" -H \"Authorization: Bearer $token\" > playlist.json");
		echo "Thanks for the urls! <br>";
		echo "<a href='output.php'> Proceed to the next page to sort your playlist </a>";
		echo "<br> Please note that it might take awhile to load";
	}
}
// check for incorrect tokens
function check_token($auth_token) {
}

function check_playlist_id($playlist_id) {
}
?>
