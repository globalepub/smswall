<?php

function make_clickable($msg){
    $ret = ' ' . $msg;
    $ret = preg_replace("/(\b)(http|ftp|https|mailto)([\:\/\/])([A-z0-9~!@$%&*()_+:?,.\/;'=#-]{2,}[A-z0-9~@$%&*_+\/'=])/","<a href=\"$2$3$4\" target=\"_blank\" ref=\"nofollow\">$2$3$4</a>",$ret);
    $ret = substr($ret, 1);
    return $ret;
}

function normalDate($ts){
    $print = date("d/m H:i:s",$ts);
    return $print;
}

function publicDate($ts){
    $print = date("d/m H:i:s", strtotime($ts));
    return $print;
}