<?php
require_once(dirname(__FILE__) . '/../config/config.php');
require_once(dirname(__FILE__) . '/../lib/twitter/Twitter.php');
require_once('functions.php');

$next_tweet            = dirname(__FILE__) . '/../data/nextCoffeeTweet.txt';
$next_tweet_timestamp  = trim(file_get_contents(file_get_contents($next_tweet)));
$now                   = time();

if ($next_tweet_timestamp === '') {
	file_put_contents($next_tweet, calculateNextCoffeeTweet($now));
	die();
}

if ((int) $next_tweet_timestamp < $now) {
	$twitter = new Twitter();
	$twitter->setCredentials($config['username'], $config['password']);
	file_put_contents($next_tweet, calculateNextCoffeeTweet($now));
	$twitter->update(
		sprintf('O HAI! Um %s Uhr ist es Zeit für die nächste Kaffeepause!', date('H:i', time() + 300)),
		'xml',
		array(
			'lat'  => $config['lat'],
			'long' => $config['long'],
		)
	);
}
