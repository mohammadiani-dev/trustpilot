<?php

function trpi_category_carousel_shortcode(){
    ob_start();

    $terms = get_terms( 'categories', array("hide_empty" => false) );

    $arrays = [];
    $key = 0;

    for($i=0 ; $i < count($terms) ; $i++){
        if(!isset($arrays[$key])){
            $arrays[$key] = [];
        }

        if(count($arrays[$key]) < 3){
            $arrays[$key][] = $terms[$i];
        }else{
            $key = $key + 1;
            $arrays[$key][] = $terms[$i];
        }
    }

    ?>
    <div class="trpi_category_carousel owl-carousel owl-theme">
        <?php  foreach($arrays as $col): ?>
        <div class="col-cat">
            <?php  foreach($col as $term): $term_meta = get_option( "taxonomy_".$term->term_id );  ?>
                <a class="cat-item" href="<?php echo home_url('categories/' .$term->slug ); ?>">
                    <?php echo stripslashes($term_meta['term_icon']); ?>
                    <p><?php echo $term->name ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <?php  endforeach; ?>
    </div>
    <?php
    
    return ob_get_clean();
}

add_shortcode("trpi_category_carousel" , "trpi_category_carousel_shortcode");