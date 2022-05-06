<?php

if(!defined("TRUST_PILOT_SHORTCODES")) define("TRUST_PILOT_SHORTCODES" , TRUST_PILOT_PATH . "template/shortcodes/" );


//single business page shortcodes
include_once TRUST_PILOT_SHORTCODES . "single-business/address-box-section.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/count-and-level-reviews.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/add-review-section.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/verify-status.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/reviews-list.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/filter-section.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/about-section.php";
include_once TRUST_PILOT_SHORTCODES . "single-business/star-rate.php";


//add review page shortcodes
include_once TRUST_PILOT_SHORTCODES . "add-review-form/header.php";
include_once TRUST_PILOT_SHORTCODES . "add-review-form/form.php";


//register,login,reset pass,forgot pass pages shortcodes
include_once TRUST_PILOT_SHORTCODES . "register-and-login/register-business.php";
include_once TRUST_PILOT_SHORTCODES . "register-and-login/register.php";
include_once TRUST_PILOT_SHORTCODES . "register-and-login/login.php";
include_once TRUST_PILOT_SHORTCODES . "register-and-login/forgot-password.php";
include_once TRUST_PILOT_SHORTCODES . "register-and-login/reset-password.php";


//category and search  shortcodes
include_once TRUST_PILOT_SHORTCODES . "archive-business/list.php";
include_once TRUST_PILOT_SHORTCODES . "archive-business/filter.php";
include_once TRUST_PILOT_SHORTCODES . "archive-business/search.php";

//single comment page shortcode
include_once TRUST_PILOT_SHORTCODES . "comment-page/comment-page.php";
include_once TRUST_PILOT_SHORTCODES . "comment-page/comment-sidebar.php";


//common shortcodes
include_once TRUST_PILOT_SHORTCODES . "common/star-rater.php";
include_once TRUST_PILOT_SHORTCODES . "common/serach-input.php";


//home page shortcodes
include_once TRUST_PILOT_SHORTCODES . "home-page/category-carousel.php";
