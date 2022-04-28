<?php namespace TrustPilot;

class notifications{

    public function __construct()
    {
        add_action("after_register_business" , [$this ,  "after_register_business"] , 10 , 2);
    }


    public function after_register_business($user_id , $meta){

        $data = [
            'site_name' => "بهترین ها",
            'email' => $meta['company_email'],
        ];

        $data = array_merge($data , $meta);

        functions::send_email($data , 'register-business');

    }





}