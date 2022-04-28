<?php

function trpi_review_details_shortcode(){
    ob_start();

    include __DIR__ . "/../widgets/reviews-detail.php";

    return ob_get_clean();
}

add_shortcode("trpi_review_details" , "trpi_review_details_shortcode");