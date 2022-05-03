<?php

function trpi_search_result_shortcode(){
    ob_start();
    if(!isset($_GET['search']) || empty($_GET['search'])){
            echo "امکان نمایش نتایج بدون سرچ وجود ندارد!";
        return;
    }

    global $wpdb;
    $post_tbl = $wpdb->prefix.'posts';
    $post_meta_tbl = $wpdb->prefix.'postmeta';
    $comment_tbl = $wpdb->prefix.'comments';
    $term_category_tbl = $wpdb->prefix.'term_relationships';
    $commnet_meta_tbl = $wpdb->prefix.'commentmeta';

    // ?city=تهران&count=25&period=365

    $query = "SELECT business.ID , business.post_title AS title , COUNT(reviews.comment_ID) as total FROM $post_tbl AS business";
    $join  = "";
    $join .= " JOIN $comment_tbl AS reviews ON business.ID = reviews.comment_post_ID ";
    $join .= " JOIN $term_category_tbl AS category ON business.ID = category.object_id ";
    
    $where = " WHERE business.post_type = 'business' ";
    
    if(isset($_GET['city'])){
        $city  = sanitize_text_field($_GET['city']);
        $join .= " JOIN $post_meta_tbl AS meta ON business.ID = meta.post_id ";
        $where .= " AND meta.meta_key = 'city' AND meta.meta_value LIKE '$city' ";
    }


    if(isset($_GET['period'])){
        $time = sanitize_text_field( $_GET['period'] );
        $time = (int) $time;
        $time = date( 'Y-m-d' , time() - ($time * 86400) );
        $where .= " AND reviews.comment_date > $time ";
    }

    if(isset($_GET['search'])){
        $search = sanitize_text_field(trim($_GET['search']));
        $key = TRUST_PILOT_PREFIX."domein";
        $join  .= " JOIN $post_meta_tbl AS meta_domain ON business.ID = meta_domain.post_id ";
        $where .= " AND meta_domain.meta_key = '$key' AND (meta_domain.meta_value LIKE '%$search%' OR business.post_title LIKE '%$search%') ";
    }

    if(is_tax('categories')){
        $cat_id = get_queried_object_id();
        $where .= " AND category.term_taxonomy_id = $cat_id ";
    }

    $query .= $join;
    $query .= $where;

    $groupBy = " GROUP BY business.ID ";
    $having  = " HAVING total > 0 ";

    if(isset($_GET['count'])){
        $count = (int)$_GET['count'];
        $cat_id = get_queried_object_id();
        $having  = " HAVING total >= $count ";
    }

    $query .= $groupBy;
    $query .= $having;

    $page = isset($_GET['cpage']) ? (int)$_GET['cpage'] : 1;
    $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $offset = ($page * $per_page) - $per_page;

    
    $total= $wpdb->get_var("SELECT COUNT(1) FROM ($query) AS combined_table");
    $total_page = ceil($total / $per_page);
    
    $query .= " LIMIT $offset, $per_page ";


    $result = $wpdb->get_results($query);

    if(count($result) > 0):

    foreach($result as $data):
        ?>
        
        <div class="buisiness-item">
            <div class="image">
                <?php echo get_the_post_thumbnail($data->ID); ?>
            </div>
            <div class="details">
                <h4><a href="<?php echo get_the_permalink($data->ID) ?>"><?php echo $data->title ?></a></h4>
                <div class="wrapper_review_details">
                    <div>
                        <?php $rate =  get_post_meta($data->ID , "average_review_rates" , true); ?>
                        <div class="trpi_wrapper_rating">
                            <div class="buisiness_star_rating" data-rating="<?php echo $rate; ?>" ></div>
                            <div><span><?php echo round($rate , 2); ?></span></div>
                        </div>
                    </div>
                    <p><span>امتیاز</span><span> / </span></p>
                    <?php $total = get_post_meta($data->ID , "total_reviews" , true); ?>
                    <p><span> از </span><?php echo $total; ?><span> تجربه </span></p>
                </div>
            </div>
        </div>

        <?php
    endforeach;

    else:;

        echo sprintf(__('هیچ نتیجه ای برای عبارت "%s" پیدا نشد!') , $search);

    endif;;

    $data = [
        'total_page' => $total_page,
        'page'  => ( get_query_var('cpage') ? get_query_var('cpage') : 1 ),
    ];
    include_once TRUST_PILOT_PATH . "template/widgets/pagination.php";

    return ob_get_clean();
}

add_shortcode("trpi_search_result" , "trpi_search_result_shortcode");