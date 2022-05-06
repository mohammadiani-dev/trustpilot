<?php namespace TrustPilot;

use stdClass;

class business{
    
    public $post_id;

    protected static $_instance = null;

    public static function instance($post_id) {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self($post_id);
        }
        return self::$_instance;
    }

    public function __construct($post_id)
    {
        $this->post_id = $post_id;
    }

    public function get_meta($meta_key){
        return get_post_meta($this->post_id ,TRUST_PILOT_PREFIX .  $meta_key , true);
    }

    public static function get_business_by_review_id($review_id){
        global $wpdb;
        $table_log = $wpdb->prefix.'comments';
        $row = $wpdb->get_row("SELECT comment_post_ID FROM $table_log WHERE comment_ID = '$review_id'");
        if(is_object($row)){
            return $row->comment_post_ID;
        }
        return false;
    }

    public static function get_review($review_id){

        global $wpdb;

        $table_log = $wpdb->prefix.'comments';
        $table_meta = $wpdb->prefix.'commentmeta';

        $sql = "";
        $where = " WHERE ";
        $where_clauses = [];
        

        $query = " comment_ID = '$review_id' ";
        array_push($where_clauses, $query);

        $query = " comment_type = 'trpi_review' ";
        array_push($where_clauses, $query);

        $query = " comment_approved = '1' ";
        array_push($where_clauses, $query);



        if (count($where_clauses) > 0) {
            foreach ($where_clauses as $index => $where_clause) {
                if ($index>0) {
                    $where .= 'AND'.$where_clause;
                } else {
                    $where .= $where_clause;
                }
            }
        }

        $query = "SELECT * FROM {$table_log} $where";

        $results  = $wpdb->get_results($query.$sql);


        $ids = [];

        $resFinal = [];
        foreach($results as $res){
            $ids[] = $res->comment_ID;
            $resFinal[$res->comment_ID] = $res;
        }

        if(count($results) > 0){
            $imploaded_ids = implode(",",$ids);
            $query2 = "SELECT * FROM {$table_meta} WHERE comment_id IN ($imploaded_ids) ";
            $results2 = $wpdb->get_results($query2);
            foreach($results2 as $res){
                $resFinal[$res->comment_id]->user_review_count = user::get_reviews_count($resFinal[$res->comment_id]->user_id) ;
                $resFinal[$res->comment_id]->{'comment_' . $res->meta_key} = $res->meta_value;
            }
        }

        // $search = [
        //     'query' => $query,
        //     'result' => $resFinal,
        // ];

        return $resFinal[$review_id];

    }
    
    public function get_reviews(array $args = array()){

        global $wpdb;
        $post_id = $this->post_id;

        $table_log = $wpdb->prefix.'comments';
        $table_meta = $wpdb->prefix.'commentmeta';

        $sql = "";
        $where = " WHERE ";
        $where_clauses = [];

        $page = isset($args['page']) ? (int)$args['page'] : 1;
        $per_page = isset($args['per_page']) ? $args['per_page'] : 10;
        $offset = ($page * $per_page) - $per_page;


        if (!isset($args['star'])) {
            $sql .= " ORDER BY comment_ID DESC ";
        }

        $sql .= " LIMIT $offset, $per_page ";

        $query = " comment_post_ID = $post_id ";
        if (isset($args['star'])) {
            $query = " comment.comment_post_ID = $post_id ";
        }
        array_push($where_clauses, $query);

        if(isset($args['search'])){
            $search = $args['search'];
            $query = " comment_content LIKE '%$search%' ";
            if (isset($args['star'])) {
                $query = " comment.comment_content LIKE '%$search%' ";
            }
            array_push($where_clauses, $query);
        }
        

        $query = " comment_type = 'trpi_review' ";
        if (isset($args['star'])) {
            $query = " comment.comment_type = 'trpi_review' ";
        }
        array_push($where_clauses, $query);

        $query = " comment_approved = '1' ";
        if (isset($args['star'])) {
            $query = " comment.comment_approved = '1' ";
        }
        array_push($where_clauses, $query);

        if (isset($args['user'])) {
            $users = $args['user'];
            $query = " user_id IN ($users) ";
            if (isset($args['star'])) {
                $query = " comment.user_id IN ($users) ";
            }
            array_push($where_clauses, $query);
        }

        if (isset($args['star'])) {
            $star = $args['star'];
            $query = " meta.meta_key = 'star' AND meta.meta_value IN ($star) ";
            array_push($where_clauses, $query);
        }



        if (count($where_clauses) > 0) {
            foreach ($where_clauses as $index => $where_clause) {
                if ($index>0) {
                    $where .= 'AND'.$where_clause;
                } else {
                    $where .= $where_clause;
                }
            }
        }

        if(isset($args['star'])){
            $query = "SELECT * FROM {$table_log} AS comment JOIN {$table_meta} AS meta ON comment.comment_ID = meta.comment_id $where";
            $total= $wpdb->get_var("SELECT COUNT(1) FROM  {$table_log} AS comment JOIN {$table_meta} AS meta ON comment.comment_ID = meta.comment_id $where");
        }else{
            $query = "SELECT * FROM {$table_log} $where";
            $total= $wpdb->get_var("SELECT COUNT(1) FROM ($query) AS combined_table");
        }

        $total_page = ceil($total / $per_page);

        $results  = $wpdb->get_results($query.$sql);


        // return $results;

        $ids = [];

        $resFinal = [];
        foreach($results as $res){
            $ids[] = $res->comment_ID;
            $resFinal[$res->comment_ID] = $res;
        }

        if(count($results) > 0){
            $imploaded_ids = implode(",",$ids);
            $query2 = "SELECT * FROM {$table_meta} WHERE comment_id IN ($imploaded_ids) ";
            $results2 = $wpdb->get_results($query2);
            foreach($results2 as $res){
                $resFinal[$res->comment_id]->user_review_count = user::get_reviews_count($resFinal[$res->comment_id]->user_id) ;
                $resFinal[$res->comment_id]->{'comment_' . $res->meta_key} = $res->meta_value;
            }
        }

        $search = [
            'query' => $query,
            'total' => (int)$total,
            'page' => (int)$page,
            'total_page' => (int)$total_page,
            'result' => $resFinal,
        ];

        return $search;

    }

    public function get_review_total(){
        if(isset($this->total_reviews)){
            return $this->total_reviews;
        }

        global $wpdb;
        $post_id = $this->post_id;
        $table_log = $wpdb->prefix.'comments';

        $where = "WHERE comment_post_ID = $post_id AND comment_type = 'trpi_review' AND comment_approved = '1'" ; 

        $query = "SELECT * FROM {$table_log} $where"; 
        $this->total_reviews = $wpdb->get_var("SELECT COUNT(1) FROM ($query) AS combined_table");

        update_post_meta($post_id , "total_reviews" , $this->total_reviews);

        return $this->total_reviews;
    }

    public function get_review_average(){

        if(isset($this->review_average)){
            return $this->review_average;
        }

        global $wpdb;
        $post_id = $this->post_id;
        $table_log = $wpdb->prefix.'comments';
        $table_meta = $wpdb->prefix.'commentmeta';

        $where = "WHERE comment_post_ID = $post_id AND comment_type = 'trpi_review' AND comment_approved = '1' AND meta.meta_key='star'" ; 
        $sum = $wpdb->get_var("SELECT sum(meta.meta_value) FROM {$table_log} AS comment JOIN {$table_meta} AS meta ON comment.comment_ID = meta.comment_id $where");

        $this->review_average = (int)$this->get_review_total() == 0 ? 0 : (int)$sum / (int)$this->get_review_total();

        update_post_meta($post_id , "average_review_rates" , $this->review_average);

        return $this->review_average;

    }


    public function get_level(){

        if(isset($this->get_level)){
            return $this->get_level;
        }

        $rate = $this->get_review_average();

        switch(true){
            case $rate > 4:
                return "عالی";
            break;
            case $rate > 3:
                return "خوب";
            break;
            case $rate > 2:
                return "متوسط";
            break;
            case $rate > 1:
                return "ضعیف";
            break;
            case $rate > 0:
                return "بسیار ضعیف";
            break;
        }

    }

    public function get_star_rating(){

        $total = $this->get_review_total();
        return [
                '5' => [
                    'label' => 'عالی',
                    'value' => $total == 0 ? '0%' : round((int)$this->get_count_star(5) / $total * 100 , 0 , 1) . '%',
                ],
                '4' => [
                    'label' => 'خوب',
                    'value' => $total == 0 ? '0%' : round((int)$this->get_count_star(4) / $total * 100 , 0 , 1) . '%',
                ],
                '3' => [
                    'label' => 'متوسط',
                    'value' => $total == 0 ? '0%' : round((int)$this->get_count_star(3) / $total * 100 , 0 , 1) . '%',
                ],
                '2' => [
                    'label' => 'ضعیف',
                    'value' => $total == 0 ? '0%' : round((int)$this->get_count_star(2) / $total * 100 , 0 , 1) . '%',
                ],
                '1' => [
                    'label' => 'بسیار ضعیف',
                    'value' => $total == 0 ? '0%' : round((int)$this->get_count_star(1) / $total * 100 , 0 , 1) . '%',
                ],
        ];

    }


    public function get_count_star($star){
        global $wpdb;
        $post_id = $this->post_id;
        $table_log = $wpdb->prefix.'comments';
        $table_meta = $wpdb->prefix.'commentmeta';
        $where = "WHERE comment_post_ID = $post_id AND comment_type = 'trpi_review' AND comment_approved = '1' AND meta.meta_key='star' AND meta.meta_value = '$star' " ; 
        $query = "SELECT COUNT(1) FROM {$table_log} AS comment JOIN {$table_meta} AS meta ON comment.comment_ID = meta.comment_id $where"; 
        return $wpdb->get_var($query);
    }

    public static function get_list_business($cat){
        // $posts = get_posts([
        //     'post_type' => 'business',
        //     'number'
        // ]);
    }

}
