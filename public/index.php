<?php 
error_reporting(E_ALL); ini_set('display_errors', 1);
date_default_timezone_set('Europe/Istanbul');
require "autoload.php";
require "../twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$dbFileName="database.txt";
$db= file($dbFileName);
$index = count($db);

define('CONSUMER_KEY', env('CONSUMER_KEY'));
define('CONSUMER_SECRET', env('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', env('OAUTH_CALLBACK'));

$userName = env('USER_NAME');
$tweetsPerQry = 5;
$ayrac="#>@";

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$new_tweets = $connection->get("statuses/user_timeline",['screen_name'=>$userName,'tweet_mode'=>'extended','count'=>$tweetsPerQry]);
$new_tweets = array_reverse($new_tweets);

function textExplode($text){
	global $ayrac;
	return explode($ayrac, $text);
}

function newTweet($tweet_id){
	global $db, $index;
	
	if ($index<=0) {
		return true;
	}else{
		foreach ($db as $value) {
			$data=textExplode($value);
			if ($data[4]==$tweet_id) {
				return false;
			}
		}
	}
	return true;
}

function logFile(){
	$log = fopen("log.txt", 'a');
	fwrite($log, date('Y-m-d H:i:s', time())."\n");
	fclose($log);
}

function addNewTweet($id,$screen_name,$name,$user_id,$tweet_id,$tweet_date,$date,$tweet_full_text,$not){
	global $dbFileName, $ayrac;
	$metin = $id.$ayrac.$screen_name.$ayrac.$name.$ayrac.$user_id.$ayrac.$tweet_id.$ayrac.$tweet_date.$ayrac.$date.$ayrac.$tweet_full_text.$ayrac.$not."\n";
	$db = fopen($dbFileName, 'a');
	fwrite($db,$metin);
	fclose($db);
}

foreach ($new_tweets as $tweet) {
	global $index;
	if (newTweet($tweet->id)) {
		if (isset($tweet->retweeted_status)) {
			if ($tweet->retweeted_status->is_quote_status) {
				$not = "RT edildi. Rt edilen twwetdeki alıntı kaydedilmedi.";	
			}else{
				$not = "RT edildi.";
			}
			addNewTweet(++$index,$tweet->user->screen_name,$tweet->user->name,$tweet->user->id,$tweet->id,gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),date('Y-m-d H:i:s', time()),$tweet->retweeted_status->full_text,$not);
			echo "<br> Eklendi";
		}elseif (isset($tweet->in_reply_to_status_id)) {
			addNewTweet(++$index,$tweet->user->screen_name,$tweet->user->name,$tweet->user->id,$tweet->id,gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),date('Y-m-d H:i:s', time()),$tweet->full_text,"Cevap tweeti.");
			echo "<br> Eklendi";
		}elseif ($tweet->is_quote_status) {
			addNewTweet(++$index,$tweet->user->screen_name,$tweet->user->name,$tweet->user->id,$tweet->id,gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),date('Y-m-d H:i:s', time()),$tweet->full_text,"tweet alıntılayarak onun üstüne yazılmıştır. Alıntılanan tweet kaydedilmedi.");
			echo "<br> Eklendi";
		}else {
			addNewTweet(++$index,$tweet->user->screen_name,$tweet->user->name,$tweet->user->id,$tweet->id,gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),date('Y-m-d H:i:s', time()),$tweet->full_text,"Tweet");
			echo "<br> Eklendi";
		}
	}
	else{
		echo "Eski tweet <br>";
	}
}
logFile();
?>