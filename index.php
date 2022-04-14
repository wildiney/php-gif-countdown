<?php
date_default_timezone_set("America/Sao_Paulo");
include 'classes/GifEncoder.class.php';

$time = $_GET['time'];
$future_date = new DateTime(date('r',strtotime($time)));
$time_now = time();
$now = new DateTime(date('r', $time_now));

$frames = array();
$delays = array();

$image = imagecreatefrompng("img/countdown.png");
$delay = 100;

$font = array(
    'size'=>25,
    'angle'=>0,
    'x-offset'=>7,
    'y-offset'=>30,
    'file'=> 'digital-7.ttf',
    'color'=>imagecolorallocate($image, 55,100,130)
);

for($i = 0; $i <= 60; $i++){
    $interval = date_diff($future_date, $now);
    if($future_date < $now){
        $image = imagecreatefrompng('img/countdown.png');
        $text = $interval->format('00:00:00:00');
        imagettftext ($image, $font['size'], $font['angle'], $font['x-offset'], $font['y-offset'], $font['color'], $font['file'], $text);
        ob_start();
        imagegif($image);
        $frames[]=ob_get_contents();
        $delays[]=$delay;
        $loops = 1;
        ob_end_clean();
        break;
    } else {
        $image = imagecreatefrompng('img/countdown.png');
        $text = $interval->format('%a:%H:%I:%S');
        if(preg_match('/^[0-9]\:/', $text)){
            $text = '0'.$text;
        }
        imagettftext ($image, $font['size'], $font['angle'], $font['x-offset'], $font['y-offset'], $font['color'], $font['file'], $text);
        ob_start();
        imagegif($image);
        $frames[] = ob_get_contents();
        $delays[] = $delay;
        $loops = 0;
        ob_end_clean();
    }
    $now->modify('+1 second');
}

header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
$gif = new AnimatedGif($frames,$delays,$loops);
$gif->display();