<?php

function trpi_single_filter_reviews_shortcode(){
    ob_start();

    include __DIR__ . "/../widgets/single-filter.php";

    return ob_get_clean();
}

add_shortcode("trpi_single_filter_reviews" , "trpi_single_filter_reviews_shortcode");