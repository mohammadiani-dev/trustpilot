<?php namespace TrustPilot;

class user {


    public static function get_reviews_count($user_id){

        global $wpdb;
        $comments_tbl = $wpdb->prefix . 'comments';

        $count_cached = (int)get_user_meta($user_id , 'reviews_count' , true);
        if($count_cached > 0){
            return $count_cached;
        }

        $query = "SELECT COUNT(comment_ID) FROM $comments_tbl WHERE user_id = $user_id AND comment_approved = 1 AND comment_type = 'trpi_review'";

        $count =  (int)$wpdb->get_var($query);

        update_user_meta($user_id , 'reviews_count' , $count);

        return $count;
        
    }

    public static function update_reviews_count($user_id){
        delete_user_meta($user_id , 'reviews_count' );
        self::get_reviews_count($user_id);
    }

}