<?php
/*
Plugin Name: trust pilot
Plugin URI: http://mohammadiani.com
Author: یوسف محمدیانی
Version: 1.0.0
Text Domain: trust pilot
Domain Path: /languages
Author URI: http://mohammadiani.com
*/

//in yek update baraye check kardane

if(!defined("TRUST_PILOT_PATH")) define("TRUST_PILOT_PATH",plugin_dir_path(__FILE__));
if(!defined("TRUST_PILOT_URL")) define("TRUST_PILOT_URL",plugin_dir_url(__FILE__));
if(!defined("TRUST_PILOT_DB_VER")) define("TRUST_PILOT_DB_VER","1.0.1");
if(!defined("TRUST_PILOT_ASSETS_VER")) define("TRUST_PILOT_ASSETS_VER","9.8.0");
if(!defined("TRUST_PILOT_PREFIX")) define("TRUST_PILOT_PREFIX","trpi_");

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use TrustPilot\init;

require_once __DIR__ . '/vendor/autoload.php';
new init;


add_action('init',function(){
        add_rewrite_rule(
        'reviews/([0-9]+)/?$',
        'index.php?pagename=reviews&review_id=$matches[1]',
        'top' );
});

add_action( 'wp_head', 'wpse26388_rewrites_init' , 5 );
function wpse26388_rewrites_init(){


    $uri = $_SERVER['REQUEST_URI'];
    $dir = explode('/',strtok($uri,'?'));
    if($dir[1]=='reviews' && count($dir) >= 3):
        $code = $dir[2];
        $comment = get_comment($code);
        if(!isset($comment)){
            return;
        }
        $title = get_comment_meta($comment->comment_ID , 'title' , true);
        $image = wp_get_upload_dir()['baseurl'] . '/reviews/' . $comment->comment_post_ID . '/' .$comment->comment_ID . '.jpg';

        $description = strlen($comment->comment_content) > 100 ? substr($comment->comment_content, 0, 100) . "..."  : $comment->comment_content;

        ?>
            <meta property='og:title' content='<?php echo $title ?>'/>
            <meta property='og:image' content='<?php echo $image ?>'/>
            <meta property='og:description' content='<?php echo $description; ?>'/>
            <meta property='og:url' content='<?php echo home_url('reviews/' . $comment->comment_ID ) ?>'/>
            <meta property='og:image:width' content='600' />
            <meta property='og:image:height' content='600' />
            <meta property="og:type" content='article'/>
        <?php
    
    endif;

}


function my_logged_in_redirect() {
    $uri = $_SERVER['REQUEST_URI'];
    $dir = explode('/',strtok($uri,'?'));
    if( !is_user_logged_in() && $dir[1]=='add-review' ){
        $return_url = home_url($_SERVER['REQUEST_URI']);
        setcookie("trpi_return_url" , $return_url , time() + 1200 , '/');
        wp_redirect(home_url('login'));
        exit;
    }
    if( is_user_logged_in() && isset($_COOKIE['trpi_return_url']) ){

        $user_id = get_current_user_id();
        $state = get_post_meta($user_id , "user-state" , true);

        if($state == "completed"){
            setcookie("trpi_return_url" , "" , time() - 10 , '/');
            wp_redirect($_COOKIE['trpi_return_url']);
            exit;
        }

    }
     
}
add_action( 'template_redirect', 'my_logged_in_redirect' );

add_filter( 'query_vars', 'wpse26388_query_vars' );
function wpse26388_query_vars( $query_vars ){
    $query_vars[] = 'review_id';
    return $query_vars;
}




