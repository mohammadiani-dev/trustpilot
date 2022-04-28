<?php

function trpi_business_details_shortcode(){
    ob_start();
    include __DIR__ . '/../widgets/business-details.php';
    return ob_get_clean();
}

add_shortcode("trpi_business_details" , "trpi_business_details_shortcode");