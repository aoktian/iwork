<?php
use I\Setting;

function getgpc( $k, $v = NULL ) {
    if (isset($_GET[$k])) {
        return $_GET[$k];
    } elseif (isset($_POST[$k])) {
        return $_POST[$k];
    }
    return $v;
}

function aget( $a, $k, $default = NULL ) {
    if (array_key_exists($k, $a)) {
        return $a[$k];
    } else {
        return $default;
    }
}

function removehost($content) {
    return str_replace('http://' . $_SERVER['HTTP_HOST'], '', $content);
}

function page_get_start($page, $ppp, $totalnum) {
    $totalpage = ceil($totalnum / $ppp);
    $page =  max(1, min($totalpage, intval($page)));
    return ($page - 1) * $ppp;
}

function cutstr($string, $length, $dot = ' ...') {
    if(strlen($string) <= $length) {
        return $string;
    }

    $pre = chr(1);
    $end = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

    $strcut = '';
    $n = $tn = $noc = 0;
    while($n < strlen($string)) {

        $t = ord($string[$n]);
        if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1; $n++; $noc++;
        } elseif(194 <= $t && $t <= 223) {
            $tn = 2; $n += 2; $noc += 2;
        } elseif(224 <= $t && $t <= 239) {
            $tn = 3; $n += 3; $noc += 2;
        } elseif(240 <= $t && $t <= 247) {
            $tn = 4; $n += 4; $noc += 2;
        } elseif(248 <= $t && $t <= 251) {
            $tn = 5; $n += 5; $noc += 2;
        } elseif($t == 252 || $t == 253) {
            $tn = 6; $n += 6; $noc += 2;
        } else {
            $n++;
        }

        if($noc >= $length) {
            break;
        }

    }
    if($noc > $length) {
        $n -= $tn;
    }

    $strcut = substr($string, 0, $n);

    $strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    $pos = strrpos($strcut, chr(1));
    if($pos !== false) {
        $strcut = substr($strcut,0,$pos);
    }
    return $strcut.$dot;
}
