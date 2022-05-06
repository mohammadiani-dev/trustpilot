<?php
function trpi_review_details_shortcode(){
    ob_start();
    global $trpiBusiness;
    $total = $trpiBusiness->get_review_total();
    $level = $trpiBusiness->get_level();
    ?>
    <div class="trpi_review_details">
        <p><span><?php echo $total; ?></span> نقد و بررسی </p>
        <span class="seprator"></span>
        <p><?php echo $level; ?></p>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode("trpi_review_details" , "trpi_review_details_shortcode");