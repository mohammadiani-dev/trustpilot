<?php

function trpi_breadcrumb_shortcode(){
    ob_start();
    include __DIR__ . '/../widgets/breadcrumb.php';
    return ob_get_clean();
}
add_shortcode("trpi_breadcrumb" , "trpi_breadcrumb_shortcode");
