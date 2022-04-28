<?php namespace TrustPilot;

use WP_REST_Request;
use WP_REST_Server;

class rest{

    public function __construct()
    {
        add_action('rest_api_init', array($this,'rest_api'));
        
    }


    public function rest_api(){
        $this->create_route('get-business','get_business',$this->get_business_args());
    }

    public function create_route($namespace,$callback,$args = false,$permission=true,$method = WP_REST_Server::READABLE){
        register_rest_route('business/v1', "/$namespace", array(
            'methods' => $method,
            'callback' => array($this,$callback),
            'permission_callback' => '__return_true',
            'args' => !$args ? array() : $args,
        ));
    }

    public function get_business_args(){
        $args = array(
            'business_id' => array(
                'required' => true,
                'type'     => 'integer',
            ),
        );
        return $args;
    }

    public function get_business(WP_REST_Request $request){

        $params = $request->get_params();
        $business_id = $params["business_id"];

        if(!isset($business_id) || empty($business_id)){
            wp_send_json(false);
        }

        $rate =  get_post_meta($business_id , "average_review_rates" , true); 
        // $total = get_post_meta($business_id , "total_reviews" , true); 
        $percent = floatval($rate) / 5 * 100;
        return $percent;

    }

}


?>
