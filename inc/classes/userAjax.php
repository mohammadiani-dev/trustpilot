<?php namespace TrustPilot;

use Avans\update;
use functions;
use Google_Service_Oauth2;
use WP_Error;

if (!defined('ABSPATH'))
    exit;

class userAjax{
    

    public function __construct()
    {
        add_action("wp_ajax_trpi_submit_form_review" , [$this,'trpi_submit_form_review_callback']);

        add_action("wp_ajax_trpi_filter_review" , [$this,'trpi_filter_review_callback']);
        add_action("wp_ajax_nopriv_trpi_filter_review" , [$this,'trpi_filter_review_callback']);

        add_action("wp_ajax_nopriv_trpi_register_business" , [$this,'trpi_register_business_callback']);       
        add_action("wp_ajax_trpi_register_business" , [$this,'trpi_register_business_callback']);  

        add_action("wp_ajax_trpi_verify_business" , [$this,'trpi_verify_business_callback']);     

        add_action("wp_ajax_trpi_like_review" , [$this,'trpi_like_review_callback']);       
        add_action("wp_ajax_trpi_submit_flag_reason" , [$this,'trpi_submit_flag_reason_callback']);  
        add_action("wp_ajax_trpi_submit_replay_review" , [$this,'trpi_submit_replay_review_callback']);     
        
        add_action("wp_ajax_trpi_complete_data_business" , [$this,'trpi_complete_data_business_callback']);     

        add_action('wp_ajax_nopriv_trpi_login_with_google', [$this,'trpi_login_with_google_callback']);
        add_action('wp_ajax_trpi_login_with_google', [$this,'trpi_login_with_google_callback']);

        add_action("wp_ajax_nopriv_trpi_login_to_acoount" , [$this , 'trpi_login_to_acoount_callback']);
        add_action("wp_ajax_nopriv_trpi_singup_account" , [$this , 'trpi_singup_account_callback']);
        add_action("wp_ajax_trpi_verify_account" , [$this , 'trpi_verify_account_callback']);
        add_action("wp_ajax_trpi_resend_otp_code" , [$this , 'trpi_resend_otp_code_callback']);

        add_action("wp_ajax_nopriv_trpi_forgot_password" , [$this , 'trpi_forgot_password_callback']);
        add_action("wp_ajax_nopriv_trpi_reset_password" , [$this , 'trpi_reset_password_callback']);
        
        add_action("wp_ajax_trpi_update_badge_and_image_review" , [$this , 'trpi_update_badge_and_image_review_callback']);

        
    }

    public function trpi_update_badge_and_image_review_callback(){
        $post_id = (int)sanitize_text_field( $_POST['post_id'] );
        $review_id = (int)sanitize_text_field(  $_POST['review_id'] );
        if(isset($_POST['review_img'])){
            $img = sanitize_text_field( $_POST['review_img'] ); // Your data 'data:image/png;base64,AAAFBfj42Pj4';
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);

            $upload_dir = wp_upload_dir();
            $upload_dir = $upload_dir['basedir'] . '/reviews/'.$post_id;
            if(!file_exists($upload_dir)) wp_mkdir_p($upload_dir);

            // Write the log file.
            $file  = $upload_dir . '/'.$review_id.'.jpg';
            $file  = fopen($file, 'a');
            fwrite($file, $data);
            fclose($file);

        }
        if(isset($_POST['badge_img'])){
            $img = sanitize_text_field($_POST['badge_img']); // Your data 'data:image/png;base64,AAAFBfj42Pj4';

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);

            $upload_dir = wp_upload_dir();
            $upload_dir = $upload_dir['basedir'] . '/business/'.$post_id;
            if(!file_exists($upload_dir)) wp_mkdir_p($upload_dir);

            // Write the log file.
            $file  = $upload_dir . DIRECTORY_SEPARATOR .'badge.jpg';
            if(file_exists($file)){
                unlink($file);
            }
                
            $file  = fopen($file, 'a');
            fwrite($file, $data);
            fclose($file);


        }
        wp_send_json_success();
    }

    public function trpi_resend_otp_code_callback(){

        $user_id = get_current_user_id();

        if($user_id == 0){
            wp_send_json_error("کاربر معتبر نیست!");
        }

        $verification_code = update_user_meta( $user_id, "verification_code" , rand(111111 , 999999));

        do_action("after_resend_otp_action" , $user_id , $verification_code);

        wp_send_json_success();
    }

    
    public function trpi_reset_password_callback(){

        $password = trim(sanitize_text_field( $_POST["pass"] ));
        $token = trim(sanitize_text_field( $_POST["token"] ));
        $user_id = (int)trim(sanitize_text_field( $_POST["user"] ));

        if(empty($password) || strlen($password) < 9){
            wp_send_json_error("رمز عبور ضعیف یا نامعتبر است");
        }

        if(!isset($_POST['token']) || !isset($_POST['user'])){
            wp_send_json_error("دسترسی شما به این عملیات محدود شده است.");
        }

        $user = get_user_by("ID" , $user_id);
        $activation_key = $user->user_activation_key;
        $activation_key = explode(":" , $activation_key);
        $expire_time = (int)$activation_key[0];
        
        if(end($activation_key) !== md5($token) || time() > $expire_time ){
            wp_send_json_error("لینک بازیابی رمز عبور نامعتبر است!");
        }


        reset_password(get_user_by("ID" , $user->ID) , $password);

        wp_send_json_success("تغییر رمز با موفقیت انجام شد!");

    }

    public function trpi_forgot_password_callback(){
    
        $email = sanitize_email($_POST['email']);

        if(empty($email) || !isset($email)){
            wp_send_json_error("ایمیل به درستی ست نشده است!");
        }

        if(!email_exists($email)){
            wp_send_json_error("این ایمیل قبلا ثبت نشده است!");
        }


        $key = wp_generate_password(20, false);
        $user = get_user_by("email" , $email);

        $update = wp_update_user([
            "ID" => $user->ID,
            "user_activation_key" => time() + 3600 . ':' . md5($key),
        ]);

        do_action("after_generate_reset_pass_key" , $user , $key);

        wp_send_json_success("لینک بازیابی رمز عبور به ایمیل وارد شده ارسال گردید.");        
    }

    public function trpi_verify_account_callback(){
        $otp = (int)$_POST['otp'];
        if(strlen($otp) != 6){
            wp_send_json_error("فرمت کد تایید وارد شده صحیح نیست!");
        }

        $user = wp_get_current_user();
        $verification_code = get_user_meta($user->ID , "verification_code" , true);

        if($verification_code != $otp){
            wp_send_json_error("کد تایید وارد شده صحیح نیست!");
        }
        
        $email = get_user_meta($user->ID , "reviewer_email" , true);

        $update = wp_update_user([
            'ID' => $user->ID,
            'user_email' => $email,
        ]);

        if(is_wp_error($update)){
            wp_send_json_error("خطا در عملیات فعال سازی...");
        }

        update_user_meta($user->ID , "user-state" , "completed");

        wp_send_json_success("فعال سازی حساب با موفقیت انجام شد!");

    }

    public function trpi_singup_account_callback(){

        $fullname = sanitize_text_field( $_POST['fullname'] );
        $email = sanitize_email( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );

        if(empty($fullname) || empty($email) || empty($password) ){
            wp_send_json_error("اطلاعات به درستی وارد نشده است!");
        }

        if(email_exists($email)){
            wp_send_json_error("با این ایمیل فرد دیگری ثبت نام کرده است!");
        }

        $user_login = '';

        do{
            $user_login = "user-" . rand(1111 , 9999) . '-' . rand(1111 , 9999)  . '-' . rand(1111 , 9999);
        }while(username_exists( $user_login ));

        $user_login = sanitize_user( $user_login );
        
        $meta = [
            'verification_code' => rand(111111 , 999999),
            'reviewer_email' => $email
        ];

        $user_id   = wp_insert_user([
            'user_login' => wp_slash($user_login),
            'user_pass' => $password,
            'display_name' => $fullname,
            'meta_input' => $meta,
            'role' => 'reviewer',
        ]);

        if(is_wp_error($user_id)){
            wp_send_json_error($user_id->get_error_message());
        }
        
        // do_action("after_register_business" , $user_id , $meta);
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        update_user_meta($user_id , "user-state" , "waiting-otp");

        wp_send_json_success("ثبت نام با موفقیت انجام شد.در حال ارسال کد تایید ایمیل...");
    }

    public function trpi_login_to_acoount_callback(){
        $email = sanitize_text_field( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );

        $authenticate = wp_authenticate(  $email ,  $password );

        if(is_wp_error($authenticate)){
            wp_send_json_error($authenticate->get_error_message());
        }

        wp_set_current_user($authenticate->ID);
        wp_set_auth_cookie($authenticate->ID);

        wp_send_json_success("ورود شما با موفقیت انجام شد.درحال تغییر مسیر...");
    }


    public function trpi_login_with_google_callback(){
        global $gClient;
        if (isset($_GET['code'])) {
            $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
            if(!isset($token["error"])){
                // get data from google
                $oAuth = new Google_Service_Oauth2($gClient);
                $userData = $oAuth->userinfo_v2_me->get();
            }
            
            // check if user email already registered
            if(!email_exists($userData['email'])){
                // generate password
                $bytes = openssl_random_pseudo_bytes(2);
                $password = md5(bin2hex($bytes));


                do{
                    $user_login = "user-" . rand(1111 , 9999) . '-' . rand(1111 , 9999)  . '-' . rand(1111 , 9999);
                }while(username_exists( $user_login ));
        
                $user_login = sanitize_user( $user_login );


                $new_user_id = wp_insert_user(array(
                    'user_login'		=> $user_login,
                    'user_pass'	 		=> $password,
                    'user_email'		=> $userData['email'],
                    'first_name'		=> $userData['givenName'],
                    'last_name'			=> $userData['familyName'],
                    'user_registered'	=> date('Y-m-d H:i:s'),
                    'role'				=> 'reviewer'
                    )
                );
                if($new_user_id) {
                    // send an email to the admin
                    wp_new_user_notification($new_user_id);
                    
                    // log the new user in
                    do_action('wp_login', $user_login, $userData['email']);
                    wp_set_current_user($new_user_id);
                    wp_set_auth_cookie($new_user_id, true);
                    
                    // send the newly created user to the home page after login
                    wp_redirect(home_url()); exit;
                }
            }else{
                //if user already registered than we are just loggin in the user
                $user = get_user_by( 'email', $userData['email'] );
                do_action('wp_login', $user->user_login, $user->user_email);
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID, true);
                wp_redirect(home_url()); exit;
            }


            var_dump($userData);
        }else{
            wp_redirect(home_url());
            exit();
        }
    }

    public function trpi_complete_data_business_callback(){

        $user = wp_get_current_user();

        $email = get_user_meta($user->ID , "company_email" , true);
        $domein = get_user_meta($user->ID , "company_domein" , true);        

        $insert = wp_insert_post(
            [
                'post_title' => $user->display_name,
                'post_author' => $user->ID,
                'post_type' => "business",
                'tax_input'    => array(
                    "categories" => [(int)$_POST['category']] //Video Cateogry is Taxnmony Name and being used as key of array.
                ),
                'post_name' => $domein,
                'post_content' => sanitize_textarea_field($_POST['about']),
                'meta_input' => [
                    TRUST_PILOT_PREFIX."domein" => $domein,
                    TRUST_PILOT_PREFIX."city"   => sanitize_text_field($_POST['city']),
                    TRUST_PILOT_PREFIX."state"  => sanitize_text_field($_POST['state']),
                    TRUST_PILOT_PREFIX."other-data" => [
                        'phone' => sanitize_text_field($_POST['phone']),
                        'address' => sanitize_text_field($_POST['address']),
                        'email' => $email,
                    ]
                ]
            ]
        );

        if(is_wp_error($insert)){
            wp_send_json_error($insert);
        }

        wp_set_object_terms($insert, (int)$_POST['category'] , 'categories');

        $attach_id = media_handle_upload('thumb' , $insert);
        $result = set_post_thumbnail( $insert, $attach_id );

        update_user_meta($user->ID , "user-state" , "completed");
        
        wp_send_json_success($insert);
    }

    public function trpi_submit_replay_review_callback(){


        $user = wp_get_current_user();

        if(!$user){
            wp_send_json("login error"  , 403);
        }

        $insert = wp_insert_comment([
            'comment_author' => $user->display_name,
            'comment_content' => sanitize_text_field( $_POST["content"] ),
            'comment_post_ID' => (int)sanitize_text_field( $_POST["post_id"] ),
            'comment_type' => sanitize_text_field( "trpi_review" ),
            'comment_approved' => 0,
            'comment_parent' => sanitize_text_field($_POST["review_id"]),
            'user_id' => $user->ID,
        ]);



        wp_send_json($insert);
    }

    public function trpi_register_business_callback(){

        if(is_user_logged_in()){
            return;
        }

        $company_name = sanitize_text_field($_POST['name']);
        $company_email = sanitize_text_field($_POST['email']);
        $company_domein = sanitize_text_field($_POST['domein']);
        $company_password = sanitize_text_field($_POST['password']);

        if(email_exists( $company_email )){
            wp_send_json_error( "با ایمیل قبلا درسایت ثبت نام شده است" );
        }

        $user_login = '';

        do{
            $user_login = "user-" . rand(1111 , 9999) . '-' . rand(1111 , 9999)  . '-' . rand(1111 , 9999);
        }while(username_exists( $user_login ));

        $user_login = sanitize_user( $user_login );
        $user_pass = $company_password; //wp_generate_password( 12, false );
        
        $meta = [
            'company_name' =>  $company_name,
            'company_email' =>  $company_email,
            'company_domein' =>  $company_domein,
            'verification_code' => rand(111111 , 999999),
        ];

        $user_id   = wp_insert_user([
            'user_login' => wp_slash($user_login),
            'user_pass' => $user_pass,
            'display_name' => $company_name,
            'meta_input' => $meta,
            'role' => 'business_owner',
        ]);

        

        if(!is_wp_error($user_id)){
            // do_action("after_register_business" , $user_id , $meta);

            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);

            update_user_meta($user_id , "user-state" , "waiting-otp");

            wp_send_json(md5($user_id));
        }


        wp_send_json(false);
    }

    public function trpi_verify_business_callback(){

        //verify otp
        $otp = $_POST['otp'];

        $user = wp_get_current_user();
        update_user_meta($user->ID , "verification" , true);
        update_user_meta($user->ID , "user-state" , "waiting-complete-info");

        wp_send_json_success("اعتبارسنجی با موفقیت انجام شد.");

        if(strlen($otp) !== 6){
            wp_send_json_error("کد تایید وارد شده فرمت نادرستی دارد!");
        }

        $user = wp_get_current_user();
        $verification_code = get_user_meta($user->ID , "verification_code" , true);
        if($verification_code !== $otp){
            wp_send_json_error("کد تایید وارد شده نادرست است!");
        }
        //check is exist verification file in root server

        $roles = ( array ) $user->roles; 

        if(!in_array("business_owner" , $roles)){
            wp_send_json_error("شما دسترسی لازم برای انجام این عملیات را ندارید.");
        }

        $address = get_user_meta($user->ID , "company_domein" , true);


        $rFile = $address.md5($user->ID);
        $ch = curl_init($rFile);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($code == 200){
            update_user_meta($user->ID , "verification" , true);
            update_user_meta($user->ID , "user-state" , "waiting-complete-info");

            $update = wp_update_user([
                'ID' => $user->ID,
                'user_email' => get_user_meta($user->ID , "company_email" , true),
            ]);

            if(is_wp_error($update)){
                wp_send_json_error("خطا در به روز رسانی اطلاعات");
            }

            wp_send_json_success("اعتبارسنجی با موفقیت انجام شد.");
        }else{
            wp_send_json_error("فایل اعتبارسنجی قابل مشاهده نیست!");
        }
        
    }

    public function trpi_submit_flag_reason_callback(){

        $post = wp_insert_post([
            "post_type" => "review_flags",
            "post_title" => $_POST['reason'],
        ]);

        update_post_meta($post , "trpi_review_id" , $_POST['review_id']);
        update_post_meta($post , "trpi_business_id" , $_POST['business_id']);

        wp_send_json($post);

    }

    public function trpi_like_review_callback(){

        $commentid = $_POST['id'];

        $count = (int)get_comment_meta($commentid , '_likes_count' , true);
        $users = get_comment_meta($commentid , '_likes_users' , true);
        $users = explode(',' , $users);

        $current_user = get_current_user_id();

        if(!in_array($current_user , $users)){
            array_push($users , $current_user);
            $count++;
        }else{
            $users = array_filter($users, function($item) use ($current_user) {
                return $item != $current_user;
            });
            $count--;
        }

        $count = $count < 0 ? 0 : $count;

        update_comment_meta($commentid , "_likes_count" , $count);
        update_comment_meta($commentid , "_likes_users" , implode("," , $users));

        wp_send_json($count);
    }

    public function trpi_submit_form_review_callback(){

        if(!wp_verify_nonce( $_POST["nonce"] ,  "trpi_submit_review")){
            wp_send_json("nonce error" , 403);
        }


        $user = wp_get_current_user();

        if(!$user){
            wp_send_json("login error"  , 403);
        }

        $insert = wp_insert_comment([
            'comment_author' => $user->display_name,
            'comment_content' => sanitize_text_field( $_POST["content"] ),
            'comment_post_ID' => (int)sanitize_text_field( $_POST["post_id"] ),
            'comment_type' => sanitize_text_field( "trpi_review" ),
            'comment_approved' => 1,
            'user_id' => $user->ID,
            'comment_meta' => [
                'star' => (int) $_POST["star"] ,
                'title' => sanitize_text_field($_POST["title"]),
            ],
        ]);

        if($insert > 0){
            $post_id = (int)sanitize_text_field( $_POST["post_id"] );
            $bussiness = new business($post_id);
            $star = round($bussiness->get_review_average() , 2);
            $data = [
                'star'=> $star,
                'title' => sanitize_text_field($_POST["title"]),
                'rating' => (int) $_POST["star"],
                'content' => sanitize_text_field( $_POST["content"] ),
                'post_id' =>  $post_id, 
                'review_id' => $insert,
                'width'=> ($star / 5) * 100,
                'level'=> $bussiness->get_level(),
                'total' => $bussiness->get_reviews()['total'],
            ];
            wp_send_json_success($data);
        }
        wp_send_json_error("خطا پایگاه داده!");
        
    }

    public function trpi_filter_review_callback(){

        if(!isset($_GET['post_id'])){
            return;
        }

        $trpiBusiness = business::instance($_GET['post_id']);
        $args = [];

        if( isset($_GET['rate']) && !empty($_GET['rate']) ){
            $args['star'] = sanitize_text_field( $_GET['rate'] );
        }

        if( isset($_GET['page']) && !empty($_GET['page']) ){
            $args['page'] = sanitize_text_field( $_GET['page'] );
        }

        if( isset($_GET['search']) && !empty($_GET['search']) ){
            $args['search'] = sanitize_text_field( $_GET['search'] );
        }

        $data = $trpiBusiness->get_reviews($args);

        foreach($data['result'] as $item){
            include TRUST_PILOT_PATH . 'template/widgets/review-item.php';
        }

        include TRUST_PILOT_PATH . 'template/widgets/pagination.php';

        die();
    }
}