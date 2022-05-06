<?php namespace TrustPilot;

use DateTime;
use DateTimeZone;

class functions{

    public static function timeDiff($time){

        $date = new DateTime("now", new DateTimeZone('Asia/Tehran') );
        $now = $date->format('Y-m-d H:i:s');

        $diff = strtotime($now) - strtotime($time);

        if($diff < 60){
            return $diff.' ثانیه قبل';
        }
        elseif($diff < 3600){
            return round($diff / 60,0,1).' دقیقه قبل';
        }
        elseif($diff >= 3660 && $diff < 86400){
            return round($diff / 3600,0,1).' ساعت قبل';
        }
        elseif($diff > 86400){
            return round($diff / 86400,0,1).' روز قبل';
        }
    }

    public static function get_user_ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    public static function send_one(){
        return 1;
    }

    public static function send_email($data, $type){
        ob_start();
        
        include TRUST_PILOT_PATH . "template/email/${type}.php";
        $html=ob_get_contents();
        ob_end_clean();
        
        
        $data['site_name'] = 'بهترین';

        foreach ($data as $index=>$var) {
            $html =  str_replace('{'.$index.'}', $var, $html);
        }

        $emailsite = 'site@behtarin.company';
        $site_name = get_bloginfo('name');

        $headers  = "From: $site_name < $emailsite >\n";
        $headers .= "X-Sender: $site_name < $emailsite >\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 1\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\n";

        $result = wp_mail($data['email'], $data['title'], $html, $headers);

        // return $result;
    }


}