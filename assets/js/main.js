var rater = require('rater-js');
const Swal = require('sweetalert2');

var domtoimage = require('dom-to-image');
var validator = require('validator');
var html2canvas = require('html2canvas');

import { saveAs } from 'file-saver';
import isEmail from 'validator/lib/isEmail';



const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});


jQuery(document).ready(function($){

    if (document.querySelector(".buisiness_star_rating") !== null) {
        $.each($(".buisiness_star_rating"), function (i, v) {
          var BizFixRater = rater({
            element: v,
            step: 1,
            starSize: 25,
          });
          BizFixRater.disable();
        });
  }


  // $(document).on("keyup", ".trpi_search_buisiness input", debounce(function () {
  //   var search = $(".trpi_search_buisiness input").val();
  //   var url = new URL(TRPI_DATA.home_url + '/search-business');
  //   url.searchParams.set("q", search);
  //   show_loading();
  //   window.location = url;
  // } , 4000));

  $(document).on( "keyup" , ".trpi_search_buisiness input", function (event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
      var search = $(".trpi_search_buisiness input").val();
      var url = new URL(TRPI_DATA.home_url + '/search-business');
      url.searchParams.set("search", search);
      show_loading();
      window.location = url;
    }
  });
  

  $(document).on("click", "#apply_city_filter", function () {
    var city = $(this).closest("div").find("input").val();
    if (city.length) {
      show_loading();
      const url = new URL(window.location);
      url.searchParams.set("city", city); 
      window.location = url;
    }
  });

  $(document).on("change", "input[name='count_review']", function () {
    var count = $(this).val();
    const url = new URL(window.location);
    if (count == '0') {
      url.searchParams.delete("count"); 
      show_loading();
      window.location = url;
    }else if (count.length) {
      url.searchParams.set("count", count); 
      show_loading();
      window.location = url;
    }
  });

  $(document).on("change", "input[name='time_period']", function () {
    var period = $(this).val();
    const url = new URL(window.location);
    if (period == '0') {
      url.searchParams.delete("period"); 
      show_loading();
      window.location = url;
    }else if (period.length) {
      url.searchParams.set("period", period); 
      show_loading();
      window.location = url;
    }
  });

  function hide_loading() {
    $(".trpi_loading").remove();
  }
  function show_loading() {
    $('body').append(
          `<!-- Loader -->
              <div class="trpi_loading">
              
              <div class="backdrop"></div>

                <div class="loading">
                    <div class="blobs">
                      <div class="blob-center"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                      <defs>
                        <filter id="goo">
                          <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                          <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                          <feBlend in="SourceGraphic" in2="goo" />
                        </filter>
                      </defs>
                    </svg>
                </div>              
              </div>`
    )
  }

  function show_loading_dark() {
    $('body').append(
          `<!-- Loader -->
              <div class="trpi_loading_dark">
              
              <div class="backdrop"></div>

                <div class="loading">
                    <div class="blobs">
                      <div class="blob-center"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                      <div class="blob"></div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                      <defs>
                        <filter id="goo">
                          <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                          <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                          <feBlend in="SourceGraphic" in2="goo" />
                        </filter>
                      </defs>
                    </svg>

                    <p>در حال بررسی</p>
                </div> 
                
                
                
              </div>`
    )
  }
  function hide_loading_dark() {
    $(".trpi_loading_dark").remove();
  }



  $(document).on("click", "#register_business", function () {
    var wrapper = $(this).closest("#register_business_form");
    var verify = $("#register_virify_step");
    
    var name = wrapper.find(".name input").val();
    var email = wrapper.find(".email input").val();
    var domein = wrapper.find(".domein input").val();
    var password = wrapper.find(".password input").val();

    if (!name.length) {
      return Toast.fire({ icon: "error", title: "نام کسب و کار وارد نشده است!" });
    }
    
    if (!domein.length || !validator.isURL(domein)) {
      return Toast.fire({ icon: "error", title: "دامنه کسب و کار وارد نشده است!" });
    }
    
    if (!validator.isEmail(email)) {
      return Toast.fire({ icon: "error", title: "ایمیل کسب و کار به شکل درستی وارد نشده است!" });
    }

    if (!validator.isStrongPassword(password, { minLength: 8, minLowercase: 1, minUppercase: 0, minNumbers: 1, minSymbols: 0, returnScore: false, pointsPerUnique: 1, pointsPerRepeat: 0.5, pointsForContainingLower: 10, pointsForContainingUpper: 10, pointsForContainingNumber: 10, pointsForContainingSymbol: 10 })) {
      return Toast.fire({ icon: "error", title: "رمزعبور وارد نشده است!" });
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: 'trpi_register_business',
        name, email, domein, password
      },
      success: function (response) {
        if (response) {
          Toast.fire({ icon: "success", title: "ثبت نام شما با موفقیت انجام شد!" });

          show_loading();

          location.reload();
          return false;

        } else {
          return Toast.fire({ icon: "error", title: "ثبت نام خطا رو به رو شد!" });
        }
      }
    });
  });

  $(document).on("click", "#register_virify_step .verify_file button", function () {
    var data = $(this).data("valid");
    var blob = new Blob([data],
      { type: "text/plain;charset=utf-8" });
    saveAs(blob, data + ".txt");
  });

  $(document).on("click", "#register_virify_step #verify_business", function () {
    var otp = $(this).closest("#register_virify_step").find(".wrppaer_inputs input").val();

    if (otp.length !== 6) {
      return Toast.fire({icon:"error" , title:"کد اعتبار سنجی ایمیل به درستی وارد نشده است!"})
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      type: "post",
      dataType: "json",
      data: {
        action: "trpi_verify_business",
        otp
      }, success: function (response) {

        if (response.success === true) {
          Toast.fire({ icon: 'success', title: response.data });

          show_loading();
          location.reload();
          return false;

        } else if(response.success === false) {
          Toast.fire({ icon: 'error', title: response.data });
        }

      },
      error: function (error) {
        console.log(error);
      }
    });

  });

  $(document).on("click", "#save_complete_company_data", function () {

    var wrapper = $(this).closest("#complete_company_data");
    var form_data = new FormData();

    var category = wrapper.find(".category select").val();
    var about = wrapper.find(".about textarea").val();
    var phone = wrapper.find(".phone input").val();
    var state = wrapper.find(".state select").val();
    var city = wrapper.find(".city input").val();
    var address = wrapper.find(".address textarea").val();
  
    var file = $("#complete_company_logo");

    console.log(
      category,
      about,
      phone,
      state,
      city,
      address,
      file
    )

    if (!category.length) {
      return Toast.fire({ icon: 'error', title: 'دسته بندی به درستی انتخاب نشده است' });
    }
    if (!about.length) {
      return Toast.fire({ icon: 'error', title: 'توضیحات کسب و کار وارد نشده است' });
    }
    if (!phone.length) {
      return Toast.fire({ icon: 'error', title: 'شماره تلفن کسب و کار وارد نشده است' });
    }
    if (!state.length) {
      return Toast.fire({ icon: 'error', title: 'استان محل کسب و کار انتخاب نشده است' });
    }
    if (!city.length) {
      return Toast.fire({ icon: 'error', title: 'شهرستان محل کسب و کار وارد نشده است' });
    }
    if (!address.length) {
      return Toast.fire({ icon: 'error', title: 'آدرس دقیق کسب و کار وارد نشده است' });
    }
    if (document.getElementById("complete_company_logo").files.length == 0) {
      return Toast.fire({ icon: 'error', title: 'لوگو کسب و کار انتخاب نشده است' });
    }

    form_data.append("category", category);
    form_data.append("about", about);
    form_data.append("phone", phone);
    form_data.append("state", state);
    form_data.append("city", city);
    form_data.append("address", address);
    
    if (file.prop("files") && file.prop("files")[0]) {
      var file_data = file.prop("files")[0];
      form_data.append("thumb", file_data);
    }

    form_data.append("action", "trpi_complete_data_business");

    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      type: "post",
      success: function (response) {
        if (response.success) {
            show_loading();
            location.reload();
            return false;
        } else {
          Toast.fire({ icon: "error", title: "ذخیره اطلاعات با خطا مواجه شد!" });
        }
      },
      error: function (response) {
          Toast.fire({ icon: "error", title: "ذخیره اطلاعات با خطا مواجه شد!" });
      },
    });
  

  });

  $(document).on("click", "#login_to_account" , function () {
    var email = $(this).closest("#login_general_form").find(".wrapper_field.email input").val();
    var password = $(this).closest("#login_general_form").find(".wrapper_field.password input").val();

    if (!email.length) {
      return Toast.fire({ icon: "error", title : "ایمیل وارد نشده است!" });
    }

    if (!password.length) {
      return Toast.fire({ icon: "error", title : "رمز عبور وارد نشده است!" });
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: "trpi_login_to_acoount",
        email,
        password
      },
      success: function (response) {
        if (response.success) {
          Toast.fire({ icon: "success", title: response.data });
          show_loading();
          window.location = TRPI_DATA.home_url;
        } else {
          Toast.fire({ icon: "error", title: response.data });
        }
      },
      error: function (error) {
        console.log(error);
      }
    });


  });

  $(document).on("click", "#singup_account", function () {

    var fullname = $(this).closest("#register_basic_user").find(".fullname input").val();
    var email = $(this).closest("#register_basic_user").find(".email input").val();
    var password = $(this).closest("#register_basic_user").find(".password input").val();

    if (!fullname.length) {
      return Toast.fire({ icon: "error", title : "نام و نام خانوادگی وارد نشده است!" });
    }

    if (!email.length) {
      return Toast.fire({ icon: "error", title : "ایمیل وارد نشده است!" });
    }

    if (!validator.isEmail(email)) {
      return Toast.fire({ icon: "error", title : "ایمیل با فرمت درستی وارد نشده است!" });
    }


    if (!password.length) {
      return Toast.fire({ icon: "error", title : "رمز عبور وارد نشده است!" });
    }

    if (!validator.isStrongPassword(password , { minLength: 8, minLowercase: 1, minUppercase: 0, minNumbers: 1, minSymbols: 0, returnScore: false, pointsPerUnique: 1, pointsPerRepeat: 0.5, pointsForContainingLower: 10, pointsForContainingUpper: 10, pointsForContainingNumber: 10, pointsForContainingSymbol: 10 } )) {
      return Toast.fire({ icon: "error", title : "رمز عبور وارد شده ضعیف است!" });
    }

    
    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: "trpi_singup_account",
        email,
        password,
        fullname
      },
      success: function (response) {
        if (response.success) {
          Toast.fire({ icon: "success", title: response.data });
          show_loading();
          location.reload();
        } else {
          Toast.fire({ icon: "error", title: response.data });
        }
      },
      error: function (error) {
        console.log(error);
      }
    });


  });

  $(document).on("click", "#otp_check_user #verify_user_by_otp", function () {

    var otp = $(this).closest("#otp_check_user").find(".otp input").val();
    
    if (otp.length !== 6) {
      return Toast.fire({icon:"error" , title:"کد اعتبار سنجی ایمیل به درستی وارد نشده است!"})
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      type: "post",
      dataType: "json",
      data: {
        action: "trpi_verify_account",
        otp
      }, success: function (response) {

        if (response.success === true) {
          Toast.fire({ icon: 'success', title: response.data });

          show_loading();
          window.location = TRPI_DATA.home_url;
          return false;

        } else if(response.success === false) {
          Toast.fire({ icon: 'error', title: response.data });
        }

      },
      error: function (error) {
        console.log(error);
      }
    });
    
  });

  $(document).on("click", "#forgot_password_form #send_reset_password_link", function () {

    var email = $(this).closest("#forgot_password_form").find(".email input").val();

    if (!email.length) {
      return Toast.fire({ icon: "error", title : "ایمیل وارد نشده است!" });
    }

    if (!validator.isEmail(email)) {
      return Toast.fire({ icon: "error", title : "ایمیل با فرمت درستی وارد نشده است!" });
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      type: "post",
      dataType: "json",
      data: {
        action: "trpi_forgot_password",
        email
      }, success: function (response) {

        if (response.success === true) {
          Toast.fire({ icon: 'success', title: response.data });

          // show_loading();
          // window.location = TRPI_DATA.home_url;
          // return false;

        } else if(response.success === false) {
          Toast.fire({ icon: 'error', title: response.data });
        }

      },
      error: function (error) {
        console.log(error);
      }
    });
    
  });

  $(document).on("click", "#reset_password_form  #trpi_save_new_password", function () {
    var pass1 = $(this).closest("#reset_password_form").find(".password1 input").val();
    var pass2 = $(this).closest("#reset_password_form").find(".password2 input").val();

    var token = $(this).data("token");
    var user = $(this).data("user");

    if (!pass1.length) {
      return Toast.fire({ icon: "error", title : "رمز عبور وارد نشده است!" });
    }

    if (!validator.isStrongPassword(pass1 , { minLength: 8, minLowercase: 1, minUppercase: 0, minNumbers: 1, minSymbols: 0, returnScore: false, pointsPerUnique: 1, pointsPerRepeat: 0.5, pointsForContainingLower: 10, pointsForContainingUpper: 10, pointsForContainingNumber: 10, pointsForContainingSymbol: 10 } )) {
      return Toast.fire({ icon: "error", title : "رمز عبور وارد شده ضعیف است!" });
    }

    if (pass1 !== pass2) {
      return Toast.fire({ icon: "error", title : "رمز عبور با تکرار آن یکسان نیست!" });
    }

    $.ajax({
      url: TRPI_DATA.ajax_url,
      type: "post",
      dataType: "json",
      data: {
        action: "trpi_reset_password",
        pass: pass1,
        token,
        user
      }, success: function (response) {

        if (response.success === true) {
          Toast.fire({ icon: 'success', title: response.data });

          show_loading();
          window.location = TRPI_DATA.home_url+'/login';
          return false;

        } else if(response.success === false) {
          Toast.fire({ icon: 'error', title: response.data });
        }

      },
      error: function (error) {
        console.log(error);
      }
    });


    
  });
  
  $(document).on("click", "#resend_otp_code", function () {
    reset_timer_resend_otp();

    $.ajax({
      url: TRPI_DATA.ajax_url,
      type: "post",
      dataType: "json",
      data: {
        action: "trpi_resend_otp_code",
      }, success: function (response) {

        if (response.success === true) {
          // Toast.fire({ icon: 'success', title: response.data });
        } else if(response.success === false) {
          Toast.fire({ icon: 'error', title: response.data });
        }

      },
      error: function (error) {
        console.log(error);
      }
    });

  });

  function reset_timer_resend_otp(){
    var time = 120;
    var btn = $("#resend_otp_code");
    btn.attr("disabled", true);

    var x = setInterval(function () {
      btn.text(time);
      time = time - 1;
      if (time == 0) {
        btn.attr("disabled", false);
        btn.text("ارسال مجدد");
        clearInterval(x);
      }
    }, 1000);
  }


  $(".complete_company_logo").click(function (e) {
    $("#complete_company_logo").click();
  });

  $("#complete_company_logo").change(function () {
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(".complete_company_logo").attr("src", e.target.result);
      };
      reader.readAsDataURL(this.files[0]);
    }
  });

  $(document).on("click", ".review-item .like_review", function (e) {
    var likeBtn = $(this);
    var id = $(this).closest(".review-item").data('id');
    var wrapper = $(this).closest(".review-item");
    var loading = wrapper.find(".loading_like");
    loading.show();
    likeBtn.hide();

    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: "trpi_like_review",
        id,
      },
      success: function (response) {
        wrapper.find(".like_review_count").text(response);
        loading.hide();
        likeBtn.show();
        update_like_icon(likeBtn);
      }
    });
  });




  $(document).on("click", ".reply_to_review", function () {
    var review_id = $(this).closest(".review-item").data("id");
    var html = `
          <div class="replay_to_review_box">
            <strong>متن پاسخ</strong>
            <textarea placeholder="چند خطی بنویسید..."></textarea>
            <button class="submit_replay_review" data-review-id="${review_id}">ارسال پاسخ</button>
          </div>
      `
    trpi_open_popup( 'ارسال پاسخ' , html , 'large');
  });


  function debounce(func, timeout = 2000){
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
  }
    


  $('.single-filter-review .review_search input').keyup(debounce(function () {
    get_reviews();
  } , 1000)); // This is the line you want!
  



  $(document).on("click", ".submit_replay_review", function () {

    var content = $(this).closest(".replay_to_review_box").find("textarea").val();
    var review_id = $(this).data("review-id");
    var post_id = $(".trpi_review_item").data("post-id");
    
  
    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: 'trpi_submit_replay_review',
        content,
        review_id,
        post_id
      },
      
    });
    
  });

  $(document).on("click", ".submit_flag_reason", function () {

    var btn = $(this);
    
    var reason = $("input[name=review_flag_type]:checked").val();
    var review_id = $(this).data("review-id");
    var business_id = $(".trpi_review_item").data("post-id");

    btn.text("در حال بررسی...");

    $.ajax({
      url: TRPI_DATA.ajax_url,
      dataType: 'json',
      type: 'post',
      data: {
        action: 'trpi_submit_flag_reason',
        reason,
        review_id,
        business_id
      },
      success: function (response) {
        if (response > 0) {
          $(".trpi_wrapper_popup").hide();
          Toast.fire({ icon: "success", title: "گزارش شما برای مدیریت ارسال شد." });
        }
      }
    });




  });

  $(document).on("click", ".review-item .flag_review", function (e) {
    var review_id = $(this).closest(".review-item").data("id");
    var html = `
          <strong>چرا فکر می کنید این تجربه ی خوبی نیست؟</strong>
          <p>چون این تجربه : </p>
          <div>
            <label>
              <input type="radio" name="review_flag_type" value="مضر یا غیرقانونی است!">
              <span>مضر یا غیرقانونی است!</span>
            </label>
          </div>
          <div>
            <label>
              <input type="radio" name="review_flag_type" value="شامل اطلاعات شخصی است.">
              <span>شامل اطلاعات شخصی است.</span>
            </label>
          </div>
          <div>
            <label>
              <input type="radio" name="review_flag_type" value="شامل تبلیغات است.">
              <span>شامل تبلیغات است.</span>
            </label>
          </div>
          <div>
            <label>
              <input type="radio" name="review_flag_type" value="بر اساس یک تجربه‌ی واقعی نیست!">
              <span>بر اساس یک تجربه‌ی واقعی نیست!</span>
            </label>
          </div>

          <button class="submit_flag_reason" data-review-id="${review_id}">ارسال گزارش</button>
      `
    trpi_open_popup( 'گزارش تخلف' , html);
  });

  


  function update_like_icon(likeBtn) {

    var type = likeBtn.data("type");
    likeBtn.attr("width", "18");
    likeBtn.attr("height", "18");
    
    if (type == 'like') {
          likeBtn.html('<path d="M896 1664q-26 0-44-18l-624-602q-10-8-27.5-26t-55.5-65.5-68-97.5-53.5-121-23.5-138q0-220 127-344t351-124q62 0 126.5 21.5t120 58 95.5 68.5 76 68q36-36 76-68t95.5-68.5 120-58 126.5-21.5q224 0 351 124t127 344q0 221-229 450l-623 600q-18 18-44 18z"/>');
          likeBtn.attr("viewBox", "0 0 1792 1792");
          likeBtn.data("type" , "dislike");
    } else {
          likeBtn.html('<path d="M14.5 25.892a.997.997 0 0 1-.707-.293l-9.546-9.546c-2.924-2.924-2.924-7.682 0-10.606 2.808-2.81 7.309-2.923 10.253-.332 2.942-2.588 7.443-2.479 10.253.332 2.924 2.924 2.924 7.683 0 10.606l-9.546 9.546a.997.997 0 0 1-.707.293zM9.551 5.252a5.486 5.486 0 0 0-3.89 1.608 5.505 5.505 0 0 0 0 7.778l8.839 8.839 8.839-8.839a5.505 5.505 0 0 0 0-7.778 5.505 5.505 0 0 0-7.778 0l-.354.354a.999.999 0 0 1-1.414 0l-.354-.354a5.481 5.481 0 0 0-3.888-1.608z"/>');
          likeBtn.attr("viewBox", "0 0 29 29");
          likeBtn.data("type" , "like");
      
    }


  }
  
    $(document).on("click", ".review-item .share_review", function (e) {
      e.preventDefault();
      var link = $(this).data('copy');
      var html = `
      <img src="${$(this).data('image')}">
      <h3>با اشتراک گذاری با دیگران تجربیات مفید را به دیگران انتقال بدید.</h3>
      <div class="link_warapper">
        <input value="${link}" type="text" />
        <svg width="24" height="24"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M384 96L384 0h-112c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48H464c26.51 0 48-21.49 48-48V128h-95.1C398.4 128 384 113.6 384 96zM416 0v96h96L416 0zM192 352V128h-144c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h192c26.51 0 48-21.49 48-48L288 416h-32C220.7 416 192 387.3 192 352z"/></svg>
      </div>
      <div class="icon_wrapper">
          <a target="_blank" href="whatsapp://send?text=${link}" title="اشتراک گذاری در واتساپ">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
          </a>
          <a target="_blank" href="mailto:enteryour@addresshere.com?body=${link}" title="اشتراک گذاری در ایمیل">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 64C490.5 64 512 85.49 512 112C512 127.1 504.9 141.3 492.8 150.4L275.2 313.6C263.8 322.1 248.2 322.1 236.8 313.6L19.2 150.4C7.113 141.3 0 127.1 0 112C0 85.49 21.49 64 48 64H464zM217.6 339.2C240.4 356.3 271.6 356.3 294.4 339.2L512 176V384C512 419.3 483.3 448 448 448H64C28.65 448 0 419.3 0 384V176L217.6 339.2z"/></svg>
          </a>
          <a target="_blank" href="tg://msg_url?url=${link}" title="اشتراک گذاری در تلگرام">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"/></svg>
          </a>
          <a target="_blank" href="http://twitter.com/share?url=${link}" title="اشتراک گذاری در توییتر">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>
          </a>
          <a target="_blank" href="http://www.facebook.com/sharer.php?u=${link}" title="اشتراک گذاری در فیس بوک">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
          </a>
          <a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=${link}" title="اشتراک گذاری در لینکدین" >
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
          </a>
          <a target="_blank" href="${$(this).data('image')}">
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M447.1 32h-384C28.64 32-.0091 60.65-.0091 96v320c0 35.35 28.65 64 63.1 64h384c35.35 0 64-28.65 64-64V96C511.1 60.65 483.3 32 447.1 32zM111.1 96c26.51 0 48 21.49 48 48S138.5 192 111.1 192s-48-21.49-48-48S85.48 96 111.1 96zM446.1 407.6C443.3 412.8 437.9 416 432 416H82.01c-6.021 0-11.53-3.379-14.26-8.75c-2.73-5.367-2.215-11.81 1.334-16.68l70-96C142.1 290.4 146.9 288 152 288s9.916 2.441 12.93 6.574l32.46 44.51l93.3-139.1C293.7 194.7 298.7 192 304 192s10.35 2.672 13.31 7.125l128 192C448.6 396 448.9 402.3 446.1 407.6z"/></svg>
          </a>
        </div>
      `

        trpi_open_popup( 'اشتراک گذاری در شبکه های اجتماعی' , html);

    }); 
  
  $(document).on("click", ".link_warapper input", function () {
      trpi_copy_text($(this).val());
    });
  
    function trpi_copy_text(text , msg) {
      var textArea = document.createElement("textarea");
      textArea.value = text;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand("Copy");
      textArea.remove();
      Toast.fire({
        icon: 'success',
        title: msg !== undefined ? msg : 'لینک با موفقیت کپی شد!'
      });
    }

    if (document.querySelector(".buisiness_user_star_rating") !== null) {
      var defualtStar = $(".buisiness_user_star_rating").data("star");
      var BizUserRater = rater({
        element: document.querySelector(".buisiness_user_star_rating"),
        step: 1,
        starSize: 35,
        rateCallback: function rateCallback(rating, done) {
          BizUserRater.setRating(rating);
          $(".buisiness_user_star_rating").data("star", rating);
          done();
        },
      });
      BizUserRater.setRating(parseFloat(defualtStar));
    }
  
    $(document).on("click", ".trpi_wrapper_popup .close_section" , function () {
      $(".trpi_wrapper_popup").hide();
    }); 
  
    function trpi_open_popup(title , content , size = 'small') {
      var popup = $(".trpi_wrapper_popup");

      
      popup.find(".popup_main").removeClass('small');
      popup.find(".popup_main").removeClass('large');

      popup.find(".popup_main").addClass(size);

      popup.find(".p-body").html(content);
      popup.find(".p-header p").text(title);

      popup.show();
    }

    $(document).on("click", ".add_review_section .buisiness_user_star_rating" , function () {
      var review = $(this).data("star");
      window.location = $("#trpi_add_new_review_button").attr("href") + '&star=' + review;
    });
  
  
  // انتشار تجربه
    $(document).on("click", "#trpi_publish_review", function (e) {

      e.preventDefault();

      var wrapper = $(this).closest(".trpi_add_review_form");
      var nonce = $(this).data("nonce");
      var post_id = wrapper.data("post-id");
      
      var star = wrapper.find(".buisiness_user_star_rating").data("star");

      if (parseInt(star) == 0) {
        Toast.fire({ icon: 'warning', title: 'لطفا یک امتیاز ستاره ای برای تجربه خود ثبت کنید!' });
        return;
      }

      var content = wrapper.find(".content-field textarea").val();

      if (!content.length) {
        Toast.fire({ icon: 'warning', title: 'محتوای تجربه خالی است!' });
        return;
      }

      var title = wrapper.find(".title-field input").val();

      if (!title.length) {
        Toast.fire({ icon: 'warning', title: 'عنوان تجربه خالی است!' });
        return;
      }

      var condition = wrapper.find(".condition-field input").is(":checked");

      if (!condition) {
        Toast.fire({ icon: 'warning', title: 'شرایط ارسال تجربه رو تایید نکردید!' });
        return;
      }

      show_loading_dark();

      $.ajax({
        url: wrapper.data("ajax"),
        dataType: 'json',
        type: 'post',
        catch : false,
        data: {
          action: 'trpi_submit_form_review',
          star,
          post_id,
          content,
          title,
          nonce,
          // dataImage 
        },
        success: function (response) {
            if (response.success) {
                wrapper.find(".content-field textarea").val("");
                wrapper.find(".title-field input").val("");
                wrapper.find(".condition-field input").prop('checked', false);
                wrapper.find(".buisiness_user_star_rating").data("star" , 0);
                BizUserRater.setRating(0);
                update_badge_company(response.data , title , content , star);
            } else {
                  Toast.fire({ icon: 'error', title: response.data });
            }
          }
      });

      
    });
  
  
  function update_badge_company(data) {

    var node = document.getElementById("fansy_review_thumb");
    var node2 = document.getElementById("company_badge_box");
    $(".trpi_badge_holder").show();
    var company_badge_box = $("#company_badge_box");
    company_badge_box.find(".trpi_star_valid").css("width" , data.width + '%');
    company_badge_box.find(".level").text(data.level);
    company_badge_box.find(".total").text(data.total);


    var fansy_review_thumb = $(".fansy_review_thumb");
    fansy_review_thumb.find(".review-title").text(data.title);
    fansy_review_thumb.find(".review-content").text(trpiWordTrim(data.content , 400 , '...'));
    fansy_review_thumb.find(".trpi_star_valid").css('width', (data.rating / 5) * 100 + '%');

    $('body').append(fansy_review_thumb);
    $('body').append(company_badge_box);
    fansy_review_thumb.show();
  
    html2canvas(node2, {
      allowTaint: true,
      useCORS: true,
      scale: 1,
      backgroundColor : '#000032' ,
    }).then(function (canvas2) {
      var badge_img = canvas2.toDataURL();

      html2canvas(node, {
        allowTaint: true,
        useCORS: true,
        scale: 1,
      }).then(function (canvas) {
        var review_img = canvas.toDataURL();

        $.ajax({
          url: TRPI_DATA.ajax_url,
          dataType: 'json',
          type: 'post',
          catch : false,
          data: {
            action: 'trpi_update_badge_and_image_review',
            post_id : data.post_id,
            review_id : data.review_id,
            review_img,
            badge_img
          },
          success: function (response) {
            fansy_review_thumb.hide();
            company_badge_box.hide();
            hide_loading_dark();
            if (response.success) {
              Toast.fire({ icon: 'success', title: 'تجربه شما با موفقیت ثبت شد!' });
              window.location = TRPI_DATA.home_url + '?p=' + data.post_id;
              } else {
                  Toast.fire({ icon: 'error', title: response.data });
              }
            }
        });
        // fansy_review_thumb.hide();
      });
  
    });


  }
  
    
  function trpiWordTrim(value, length, overflowSuffix) {
    if (value.length <= length) return value;
    var strAry = value.split(' ');
    var retLen = strAry[0].length;
    for (var i = 1; i < strAry.length; i++) {
        if(retLen == length || retLen + strAry[i].length + 1 > length) break;
        retLen+= strAry[i].length + 1
    }
    return strAry.slice(0,i).join(' ') + (overflowSuffix || '');
  }
  
    
    if (document.querySelector(".filter-progress-section input[type=checkbox]")) {

      $(document).on('click', ".filter-progress-section input[type=checkbox]", function () {
        get_reviews();
      });
      
  }
  

    $(document).on("click", ".trpi_review_item a.page-numbers", function (e) {
      e.preventDefault();
      var page = $(this).attr("href").split("cpage=");
      get_reviews(page[1]);
    });
  
  
  
    function get_reviews(page = 1) {
        var post_id = $(".single-filter-review").data("post-id");
        var rate = get_filter_rate();
        var search = $('.single-filter-review .review_search input').val();
        show_loading();
          $.ajax(
            {
              url: TRPI_DATA.ajax_url,
              dataType: 'html',
              type: 'get',
              data: {
                action: "trpi_filter_review",
                rate,
                post_id,
                page,
                search
              },
              success: function (response) {
                $(".trpi_review_item").html(response);
                trpi_scroll_to_element($(".trpi_review_item"));
                hide_loading();
              },
              error: function () {
                hide_loading();
              }
            }
          )
      }

      function trpi_scroll_to_element(el) {
        $([document.documentElement, document.body]).animate(
          {
            scrollTop: el.offset().top - 40,
          },
          1000
        );
      }
      function get_filter_rate() {
            var list = [];
            $.each( $(".filter-progress-section input[type=checkbox]"), function (i,v) {
                if ($(this).is(":checked") == true) {
                  list.push($(this).data("score"));
                }
            });
            return list.join(',');
      }

});