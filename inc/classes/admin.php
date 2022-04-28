<?php namespace TrustPilot;

if (!defined('ABSPATH'))
    exit;

class admin{

    public function __construct()
    {
        add_action("init", [$this,"add_post_type"] , 0);
        add_action("init" , [$this , "register_custom_roles_and_caps"]);
        add_action('admin_menu', [$this,'add_menu_page']);
        add_action('add_meta_boxes', [$this,'add_meta_boxes']);
        add_action('save_post', [$this,'save_post'],10,2);   
    }



    public function register_custom_roles_and_caps(){

        add_role( "business_owner", "صاحب کسب و کار" );
        add_role( "reviewer", "بازبین" );

        $caps  = array( 
            'reply_review',
            'edit_business_meta',
        );


        if( $role_object = get_role( 'administrator' )){
            foreach( $caps as $cap ){
                if( !$role_object->has_cap( $cap )){
                    $role_object->add_cap( $cap );
                }
            }
        }

        if( $role_object = get_role( 'business_owner' )){
            foreach( $caps as $cap ){
                if( !$role_object->has_cap( $cap )){
                    $role_object->add_cap( $cap );
                }
            }
        }

    }


    public function add_post_type(){

        $labels = array(
            'name'                => 'کسب و کار ها',
            'singular_name'       => 'کسب و کار',
            'menu_name'           => 'کسب و کارها',
            'all_items'           => 'همه',
            'view_item'           => 'نمایش',
            'add_new_item'        => 'افزودن کسب و کار جدید',
            'add_new'             => 'افزودن',
            'edit_item'           => 'ویرایش کسب و کار',
            'update_item'         => 'به روزرسانی کسب و کار',
            'search_items'        => 'جستجو',
            'not_found'           => 'هیچ کسب و کاری پیدا نشد!',
            'not_found_in_trash'  => 'هیچ کسب و کاری در زباله دان پیدا نشد!'
        );

        register_post_type('business', array(
            'label'               => 'کسب و کارها',
            'description'         => 'کسب و کار ها',
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'thumbnail')
        ));


        register_post_type('review_flags', array(
            'label'               => 'گزارش های تخلف',
            'description'         => 'گزارش های تخلف',
            'labels' => [],
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false, 
            'hierarchical' => false,
            'menu_position' => 20,
            'supports' => array('title', 'editor')
        ));



        $labels = array(
            'name' => 'دسته بندی ها',
            'singular_name' => 'دسته بندی',
            'search_items' =>  'جستجو دسته بندی',
            'all_items' => 'همه دسته بندی ها',
            'parent_item' => 'مادر دسته بندی',
            'parent_item_colon' => __( 'مادر دسته بندی:' ),
            'edit_item' => __( 'ویرایش دسته بندی' ), 
            'update_item' => __( 'به روزرسانی دسته بندی' ),
            'add_new_item' => __( 'افزودن دسته بندی جدید' ),
            'new_item_name' => __( 'نام دسته بندی جدید' ),
            'menu_name' => __( 'دسته بندی ها' ),
        );    
 
        register_taxonomy('categories',array('business'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
        ));

    }


    public function add_menu_page(){
        add_submenu_page(
            'triboon_content_orders',
            'مشاهده سفارشات اخیر',
            'مشاهده سفارشات اخیر',
            'manage_options',
            'edit.php?post_type=triboon_content_pro'
        );
    }

    public function add_meta_boxes(){

        add_meta_box(
            'trpi_data_business',
            'اطلاعات کسب و کار',
            array($this,'metabox_data_business_content'),
            array('business'),
            'advanced',
            'high'
        );
        
    }

    public function metabox_data_business_content(){
        include TRUST_PILOT_PATH . 'template/admin/metaboxes/data_business.php';
    }

    public function save_post(){
        
    }



}