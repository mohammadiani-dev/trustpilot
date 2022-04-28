<?php namespace TrustPilot;

use Avans\func;
use Google_Client;

if (!defined('ABSPATH'))
    exit;

class init{

    public function __construct()
    {
        $this->class_loader();
        add_action('init',[$this,'component_loader']);
        add_action("init" ,[$this ,'init_google_api']);
        add_action('save_post',[$this,'save_post']);
        add_action('wp_enqueue_scripts',[$this,'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this,'enqueue_admin_scripts']);
        add_action("template_redirect" , [$this , "load_global_vars" ] , 9999 );
    }  

    public function init_google_api(){

        require_once TRUST_PILOT_PATH . 'inc/google-api/vendor/autoload.php';
        $gClient = new Google_Client();
        $gClient->setClientId("315275401202-3rv3cum8fr5dibvdejs3aomcks63vtph.apps.googleusercontent.com");
        $gClient->setClientSecret("c9owW3nh5r73K6I4SKaAPhGA");
        $gClient->setApplicationName("behtarin");
        $gClient->setRedirectUri(   add_query_arg([ 'action' =>  'trpi_login_with_google' ],admin_url('admin-ajax.php'))   );
        $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

        // login URL
        $login_url = $gClient->createAuthUrl();

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

    public function load_global_vars(){
        if(!is_admin()){
            if(isset($_GET['post_id']) && is_page()){
                $GLOBALS['trpiBusiness'] = business::instance(sanitize_text_field($_GET['post_id']));
            }else{
                $GLOBALS['trpiBusiness'] = business::instance(get_queried_object_id());
            }
        }
    }


    public function save_post($post_id){


    }

    public function component_loader(){
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
            .toplevel_page_triboon_pro_packages a.wp-first-item{
                display: none !important;
            }
        </style>
        <?php
    }
    

}