<?php
echo "hello world" . "\n";

$client_id="fb7853c47e8d4db0a7118e65d99d4b4b";
$client_secret="d1c318925a6c4255be2fa7fbc841066e";

$redirect_uri="https://open.spotify.com/";
$authorization_endpoint="https://accounts.spotify.com/authorize?client_id=$client_id&response_type=token&redirect_uri=$redirect_uri";
$FILE="/var/www/html/case/team123/printtest";
echo nl2br("\n<a href=\"$authorization_endpoint\" target=\"_blank\"> please authenticate </a>\n");
$token;
$playlist;

if (empty($_GET["tokenurl"])) {
	echo "<form action='spotify.php' method='GET'>\n";
	echo "<textarea name='tokenurl'>enter your url with token here</textarea>\n";
	echo "<textarea name='playlisturl'> enter playlist url </textarea>" . "\n";
	echo "<input type=submit>\n</form>\n";
} else {
	$token=$_GET["tokenurl"];
	$token=preg_replace('/.*token=/', "", $token);
	$token=preg_replace('/&token.*/', "", $token);
	echo nl2br("$token\n");
        $playlist=$_GET["playlisturl"];
	$playlist=preg_replace('/.*list\//', "", $playlist);
	#$playlist=preg_replace('/?.*/', "", $playlist);
        echo nl2br("$playlist\n");
	if(empty($playlist) || empty($token)) {
		echo "one or more of URLS are incorrect, please try again";
	}
	else {
		$token_play_file=fopen("token-playlist.txt", "w") or die("can't open");
		fwrite($token_play_file, $token);
		fwrite($token_play_file, "\n");
		fwrite($token_play_file, $playlist);
		fclose($token_play_file);
		exec("curl -X GET \"https://api.spotify.com/v1/playlists/$playlist/tracks\" -H \"Authorization: Bearer $token\" > playlist.json");
		//$ch=curl_init($playlist_endpoint);
		//$fp=fopen("playlist.json", "w") or die ("cant open playlist");
		
		//curl_setopt($ch, CURLOPT_FILE, $fp);
		//curl_setopt($ch, CURLOPT_HEADER, 0);

		//curl_exec($ch);
		//if(curl_error($ch)) {
		//	fwrite($fp, curl_error($ch));
		//}
		//curl_close($ch);
		//fclose($fp);
	}
}
// check for incorrect tokens
function check_token($auth_token) {
}

function check_playlist_id($playlist_id) {
}
?>
