<?php

function trpi_business_details_shortcode(){
    ob_start();

    global $trpiBusiness; 
    $state = $trpiBusiness->get_meta("state");
    $city = $trpiBusiness->get_meta("city");
    $meta = $trpiBusiness->get_meta("other-data");

    $fullAddress = $state . '-' . $city ;
    if(isset($meta) && is_array($meta)){
        $phone = $meta['phone'];
        $address = $meta['address'];
        $email = $meta['email'];
        $fullAddress .= '-' . $address;
    }
    ?>

    <div class="trpi_buisiness_details">

        <div class="title">
            <h4>
                <span>درباره </span>
                <span><?php echo get_the_title() ?> </span>
            </h4>
        </div>

        <div class="desc">
            <?php echo get_the_content() ?>
        </div>

        
        <?php if(isset($meta) && is_array($meta)): ?>

        <hr>

        <div class="contact">
            <h4>اطلاعات تماس</h4>
            <ul>

                <?php if(isset($meta['email']) && !empty($meta['email'])): ?>
                    <li>
                        <a href="mailto:<?php echo $email; ?>">
                            <svg width="16" heigth="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M464 64C490.5 64 512 85.49 512 112C512 127.1 504.9 141.3 492.8 150.4L275.2 313.6C263.8 322.1 248.2 322.1 236.8 313.6L19.2 150.4C7.113 141.3 0 127.1 0 112C0 85.49 21.49 64 48 64H464zM217.6 339.2C240.4 356.3 271.6 356.3 294.4 339.2L512 176V384C512 419.3 483.3 448 448 448H64C28.65 448 0 419.3 0 384V176L217.6 339.2z"/></svg>
                            <span><?php echo $email; ?></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if(isset($meta['phone']) && !empty($meta['phone'])): ?>
                <li>
                    <a href="tel://<?php echo $phone; ?>">
                        <svg width="16" heigth="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M18.92 351.2l108.5-46.52c12.78-5.531 27.77-1.801 36.45 8.98l44.09 53.82c69.25-34 125.5-90.31 159.5-159.5l-53.81-44.04c-10.75-8.781-14.41-23.69-8.974-36.47l46.51-108.5c6.094-13.91 21.1-21.52 35.79-18.11l100.8 23.25c14.25 3.25 24.22 15.8 24.22 30.46c0 252.3-205.2 457.5-457.5 457.5c-14.67 0-27.18-9.968-30.45-24.22l-23.25-100.8C-2.571 372.4 5.018 357.2 18.92 351.2z"/></svg>
                        <span><?php echo $phone; ?></span>
                    </a>
                </li>
                <?php endif; ?>


                <?php if(isset($meta['address']) && !empty($meta['address'])): ?>
                <li>
                    <a target="_blank" href="https://www.google.com/maps/search/<?php echo $fullAddress; ?>">
                        <svg width="16" heigth="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M168.3 499.2C116.1 435 0 279.4 0 192C0 85.96 85.96 0 192 0C298 0 384 85.96 384 192C384 279.4 267 435 215.7 499.2C203.4 514.5 180.6 514.5 168.3 499.2H168.3zM192 256C227.3 256 256 227.3 256 192C256 156.7 227.3 128 192 128C156.7 128 128 156.7 128 192C128 227.3 156.7 256 192 256z"/></svg>
                        <span><?php echo $fullAddress; ?></span>
                    </a>
                </li>
                <?php endif; ?>


            </ul>
        </div>
        <?php endif; ?>


    </div>

    <?php
    return ob_get_clean();
}

add_shortcode("trpi_business_details" , "trpi_business_details_shortcode");