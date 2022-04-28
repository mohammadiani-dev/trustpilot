<?php
function trpi_comment_page_shortcode(){
    ob_start();
    if(isset($_GET['comment_id'])){
        $title = get_comment_meta($_GET['comment_id'] , 'title' , true);
        wp_title($title);
    }
    return ob_get_clean();
}
add_shortcode("trpi_comment_page" , "trpi_comment_page_shortcode");

add_filter( 'pre_get_document_title', 'cyb_change_page_title' );
function cyb_change_page_title () {
  if(isset($_GET['comment_id'])){
        $title = get_comment_meta($_GET['comment_id'] , 'title' , true);
        return $title;
  }
}