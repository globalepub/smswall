<?php
/*
Based on TwitterOAuth
PHP library for working with Twitter's OAuth API.
https://github.com/abraham/twitteroauth
*/
header("refresh:20;url=register_tweet.php");
header('Content-Type: text/html; charset=utf-8');

require_once('../smswall.inc.php');
include('../func.php');
require_once '../libs/twitteroauth.php';
require('../libs/Pusher.php');

date_default_timezone_set('Europe/Paris');

function sortStatusesById($item1,$item2){
    if ($item1->id_str == $item2->id_str) return 0;
    return ($item1->id_str > $item2->id_str) ? 1 : -1;
}

function tagSplitter($tagstr) {
    $tag_ari = explode(',', $tagstr);
    $clean = array();
    foreach($tag_ari as $tag){
        $clean[] = trim($tag);
    }
    $paramstr = urlencode( utf8_encode( implode(' OR ', $clean) ) );
    return $paramstr;
}

function search(array $query){
    $toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    return $toa->get('search/tweets', $query);
}

$last_result = $db->query("SELECT * FROM messages WHERE provider = 'TWITTER' ORDER BY ref_id DESC LIMIT 0,1");
$last = $last_result->fetch(PDO::FETCH_ASSOC);

$last_ref_id = ($last['ref_id']) ? $last['ref_id'] : '';

$query = array(
  "q" => tagSplitter( $config['hashtag'] ),
  "result_type" => "recent",
  "since_id" => $last_ref_id,
);

$results = search($query);

if(empty($results->errors)){

    $statuses = $results->statuses;
    usort($statuses,'sortStatusesById');

    foreach ($statuses as $result) {

        $links = "";
        $medias = "";
        if(!empty($result->entities)){
            foreach ($result->entities as $k => $v) {
                if($k == "urls" && !empty($v)){
                    $links = array();
                    foreach($v as $url){
                        $link = array();
                        $link['type'] = 'link';
                        $link['url'] = $url->url;
                        $link['expanded_url'] = $url->expanded_url;
                        $links[] = $link;
                    }
                }
                if($k == "media" && !empty($v)){
                    $medias = array();
                    foreach($v as $med){
                        $media = array();
                        $media['type'] = $med->type;
                        $media['url'] = $med->url;
                        $media['media_url'] = $med->media_url;
                        if(isset($med->sizes->thumb)){
                            $media['thumb_size'] = 'thumb';
                        }else if(isset($med->sizes->small)){
                            $media['thumb_size'] = 'small';
                        }else if(isset($med->sizes->medium)){
                            $media['thumb_size'] = 'medium';
                        }
                        $medias[] = $media;
                    }
                }
            }
        }

        // htmlisation du message
        $message_html = utf8_decode($result->text);
        if(!empty($links)){
            foreach($links as $link){
                $html_link = '<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">%s</a>';
                $link_formated = sprintf($html_link, $link['expanded_url'], $link['type'], $link['expanded_url'], $link['url']);
                $message_html = str_replace($link['url'], $link_formated, $message_html);
            }
        }
        if(!empty($medias)){
            foreach($medias as $media){
                $html_media = '<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">%s</a>';
                $media_formated = sprintf($html_media, $media['media_url'], $media['type'], $media['media_url'], $media['url']);
                $message_html = str_replace($media['url'], $media_formated, $message_html);
            }
        }

        $provider = 'TWITTER';
        $ref_id = $result->id_str;
        $author = $result->user->screen_name;
        $message = utf8_decode($result->text);
        $message_html = $message_html;
        $avatar = $result->user->profile_image_url;
        $links = (!empty($links)) ? json_encode($links) : "";
        $medias = json_encode($medias);
        $ctime = date('Y-m-d H:i:s', strtotime($result->created_at));
        $ctime_db = date('Y-m-d H:i:s', strtotime($result->created_at) - date("Z"));
        $visible = $config['modo_type'];

        try{
            $db->beginTransaction();
            $q = $db->prepare('INSERT INTO messages (provider,ref_id,author,message,message_html,avatar,links,medias,ctime,visible) VALUES(?,?,?,?,?,?,?,?,?,?)');
            $q->execute(array($provider,$ref_id,$author,$message,$message_html,$avatar,$links,$medias,$ctime_db,$visible));
            $lastId = $db->lastInsertId();
            $db->commit();
        }catch(PDOException $e){
            echo "DB Error : " . $e->errorInfo();
        }

        $arrayPush = array();
        $arrayPush['id'] = $lastId;
        $arrayPush['provider'] = $provider;
        $arrayPush['t_id'] = $ref_id;
        $arrayPush['message'] = utf8_encode($message);
        $arrayPush['message_html'] = utf8_encode($message_html);
        $arrayPush['visible'] = $visible;
        $arrayPush['author'] = $author;
        $arrayPush['avatar'] = $avatar;
        $arrayPush['links'] = $links;
        $arrayPush['medias'] = $medias;
        $arrayPush['ctime'] = $ctime;

        $pusher = new Pusher( PUSHER_KEY, PUSHER_SECRET, PUSHER_APPID );
        $pusher->trigger('Channel_' . $config['channel_id'], 'new_twut', $arrayPush);

    }
    ?>
    <html>
    <head>
        <?php
        if(count($statuses)==1) {
            $msg = "1 nouveau message";
        }else if(count($statuses)>1){
            $msg = count($statuses) . " nouveaux messages";
        }else{
            $msg = "Pas de nouveau message";
        }
        ?>
        <title><?php echo $msg; ?></title>
    </head>
    <body>
        <?php echo $msg; ?>
    </body>
    </html>
    <?php
}else{
    echo "<p>Problème d'authentification. Vérifiez vos paramètres de connexion à l'API Twitter</p>";
    ?>
    <ul style="list-style-type: none; border: 1px solid #ddd; padding: 5px;">
    <?php
    foreach($results->errors[0] as $k => $v){
        echo "<li><strong>" . $k . " :</strong> <code>" . $v . "</code></li>";
    }
    ?>
    </ul>
    <?php
}
