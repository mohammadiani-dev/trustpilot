<?php

function trpi_login_shortcode_callback(){
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


    global $login_url;

    ?>

    <div id="login_general_form">
        
        <a id="TrpiloginByGoogle" href="<?php echo $login_url ?>" >
            <span>ورود با گوگل</span>
            <span><img src="<?php echo TRUST_PILOT_URL . '/assets/images/google-icon.png' ?>"></span>
        </a>
        <div class="divider-or">
            <hr>
            <span>یا</span>
        </div>
        <div class="wrapper_field email">
            <label>ایمیل</label>
            <input type="email" placeholder=""   autocomplete="off">
        </div>

        <div class="wrapper_field password">
            <label>رمز عبور</label>
            <input type="password"    autocomplete="off" >
        </div>

        <div class="wrapper_field">
            <button id="login_to_account">ورود به حساب کاربری</button>
        </div>
        <div>ثبت‌نام نکرده اید؟ <a href="<?php echo home_url("signup"); ?>">ساخت حساب کاربری</a></div>
        <div>رمز عبور خود را فراموش کرده اید؟ <a href="<?php echo home_url("forgot-password"); ?>">بازیابی گذرواژه</a></div>
    </div>


    <?php
    return ob_get_clean();
}

add_shortcode("trpi-login" , "trpi_login_shortcode_callback");