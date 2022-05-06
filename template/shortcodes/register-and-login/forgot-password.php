<?php

function trpi_forgot_password_shortcode_callback(){
    ob_start();

    if(is_user_logged_in()){
        ?>
            <div>
                <p>شما هم اکنون وارد شده اید</p>
                <a href="<?php echo wp_logout_url(get_the_permalink()); ?>">خروج از حساب کاربری</a>
            </div>
        <?php
        return ob_get_clean();
    }


    ?>

    <div id="forgot_password_form">
        
        <div class="wrapper_field email">
            <label>ایمیل</label>
            <input type="email" placeholder=""   autocomplete="off">
        </div>

        <div class="wrapper_field">
            <button id="send_reset_password_link">ارسال ایمیل بازیابی رمز عبور</button>
        </div>
    </div>


    <?php
    return ob_get_clean();
}

add_shortcode("trpi-forgot-password" , "trpi_forgot_password_shortcode_callback");