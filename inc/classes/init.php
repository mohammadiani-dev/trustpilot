<?php namespace TrustPilot;

use Avans\func;
use Google_Client;

if (!defined('ABSPATH'))
    exit;

class init{

    public function __construct()
    {
        $this->class_loader();
        add_action( "init", [$this,'component_loader'] );
        add_action( "init" , [$this ,'init_google_api'] );
        add_action( "save_post", [$this,'save_post'] );
        add_action( "wp_enqueue_scripts", [$this,'enqueue_scripts'] );
        add_action( "admin_enqueue_scripts", [$this,'enqueue_admin_scripts'] );
        add_action( "template_redirect" , [$this , "load_global_vars" ] , 9999 );
        add_action( "wp_head", [$this,'add_meta_tags_for_review_page'] , 5 );
        add_filter( "query_vars", [$this,'add_query_vars'] );
        add_filter( 'pre_get_document_title', [$this,'change_page_title'] );
    }  

    public function change_page_title () {
        if(isset($_GET['review_id'])){
            $title = get_comment_meta($_GET['review_id'] , 'title' , true);
            return $title;
        }
    }

    public function init_google_api(){

        // login URL
        
        if(!is_user_logged_in()){
            $gClient = new Google_Client();
            $GLOBALS['gClient'] = $gClient;
            $gClient->setClientId("532487010082-8hj4rtsmhmhcjjqk5o6rm1sipbfusmu4.apps.googleusercontent.com");
            $gClient->setClientSecret("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");
            $gClient->setApplicationName("behtarin.company");
            $gClient->setRedirectUri(   add_query_arg([ 'action' =>  'trpi_login_with_google' ],admin_url('admin-ajax.php'))   );
            $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
            $login_url = $gClient->createAuthUrl();
            $GLOBALS['login_url'] = $login_url;
        }

    }

    public function class_loader(){

        if(is_admin() && isset($_GET['post'])){
            $GLOBALS['trpiBusiness'] = business::instance(sanitize_text_field($_GET['post']));
        }
        new admin();
        new adminAjax();
        new userAjax();
        new notifications();
        new rest();
    }

    public function add_query_vars( $query_vars ){
        $query_vars[] = 'review_id';
        return $query_vars;
    }

    public function load_global_vars(){

        if(!is_admin()){
            if(isset($_GET['post_id']) && is_page()){
                $GLOBALS['trpiBusiness'] = business::instance(sanitize_text_field($_GET['post_id']));
            }else{
                $GLOBALS['trpiBusiness'] = business::instance(get_queried_object_id());
            }
        }

        $uri = $_SERVER['REQUEST_URI'];
        $dir = explode('/',strtok($uri,'?'));
        if( !is_user_logged_in() && $dir[1]=='add-review' ){
            $return_url = home_url($_SERVER['REQUEST_URI']);
            setcookie("trpi_return_url" , $return_url , time() + 1200 , '/');
            wp_redirect(home_url('login'));
            exit;
        }
        if( is_user_logged_in() && isset($_COOKIE['trpi_return_url']) ){
            
            setcookie("trpi_return_url" , "" , time() - 10 , '/');
            wp_redirect($_COOKIE['trpi_return_url']);
            exit;
            // $user_id = get_current_user_id();
            // $state = get_post_meta($user_id , "user-state" , true);

            // if($state == "completed"){
            // }

        }

    }


    public function save_post($post_id){}

    public function add_meta_tags_for_review_page(){
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

    public function component_loader(){

        if(!current_user_can( 'manage_options' )){
            add_filter( 'show_admin_bar', '__return_false' );
        }

        require_once TRUST_PILOT_PATH.'template/shortcodes/shortcodes.php';
    }

    public function enqueue_scripts(){
        wp_enqueue_style("trustPilot",TRUST_PILOT_URL."/assets/css/main.min.css",[],TRUST_PILOT_ASSETS_VER);


        wp_register_script("trustPilot",TRUST_PILOT_URL."/assets/js/main.min.js",['jquery'],TRUST_PILOT_ASSETS_VER,true);
        wp_localize_script("trustPilot" , "TRPI_DATA" , ['plugin_url' => TRUST_PILOT_URL , 'home_url' => home_url() , 'ajax_url' => admin_url('admin-ajax.php')]);
        wp_enqueue_script("trustPilot");

        wp_register_script("trustPilot_vue","https://unpkg.com/vue@3.2.29/dist/vue.global.prod.js",[],"1.0.0",true);
        wp_register_script("trustPilot_axios","https://unpkg.com/axios/dist/axios.min.js",[],"1.0.0",true);
    }

    public function enqueue_admin_scripts(){
        ?>
        <style>
            .wrapper_business_data .wrapper_field {display: flex;margin: 16px 0;}
            .wrapper_business_data .wrapper_field label {flex-basis: 200px;}
            .wrapper_business_data .wrapper_field input, .wrapper_business_data .wrapper_field select,.wrapper_business_data .wrapper_field textarea {flex-basis: calc(100%  - 200px);border: 1px solid #aaaaaa9a;min-height: 37px;}
            .wrapper_business_data .wrapper_field textarea {height: 122px;}
        </style>
        <?php
    }
    

}