<?php
require_once('../smswall.inc.php');
require_once '../libs/twitteroauth.php';

function search(array $query){
    $toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    return $toa->get('statuses/show', $query);
}

$query = array(
  "id" => $_GET['id']
);

$result = search($query);

header('Content-type: application/json');
echo json_encode($result);
