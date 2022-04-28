<?php 
use TrustPilot\functions;

$likes = (int)get_comment_meta($item->comment_ID , "_likes_count" , true);
$users = get_comment_meta($item->comment_ID , "_likes_users" , true);
$users = explode(',' , $users);
$isLiked = in_array(get_current_user_id() , $users);

?>
<div class="review-item" data-id="<?php echo $item->comment_ID; ?>">
    <div class="item-header">
        <div class="user-box">
            <div>
                <?php echo get_avatar($item->user_id); ?>
            </div>
            <div>
                <?php $user = get_user_by("ID" , $item->user_id); ?>
                <strong><?php echo $user->display_name; ?></strong>
                <div>
                    <div class="review-count">1 نقد و بررسی</div>
                </div>
            </div>
        </div>

        <button class="reply_to_review">
            <span>پاسخ دادن</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M17,9.5H7.41l1.3-1.29A1,1,0,0,0,7.29,6.79l-3,3a1,1,0,0,0-.21.33,1,1,0,0,0,0,.76,1,1,0,0,0,.21.33l3,3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42L7.41,11.5H17a1,1,0,0,1,1,1v4a1,1,0,0,0,2,0v-4A3,3,0,0,0,17,9.5Z" fill="#aaa" ></path></svg>        </button>
    </div>
    <hr>
    <div class="item-body">
        <div class="meta">
            <div class="date">
                <?php echo functions::timeDiff($item->comment_date); ?>
            </div>
            <div class="buisiness_star_rating" data-rating="<?php echo $item->comment_star; ?>"></div>
        </div>
        <h5 class="review-title"><?php echo $item->comment_title;  ?></h5>
        <p class="review-content"><?php echo $item->comment_content;  ?></p>
    </div>
    <hr>
    <div class="item-footer">
        <div>
            <?php if($isLiked): ?>
            <svg class="like_review" data-type="dislike" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 1792 1792"><path d="M896 1664q-26 0-44-18l-624-602q-10-8-27.5-26t-55.5-65.5-68-97.5-53.5-121-23.5-138q0-220 127-344t351-124q62 0 126.5 21.5t120 58 95.5 68.5 76 68q36-36 76-68t95.5-68.5 120-58 126.5-21.5q224 0 351 124t127 344q0 221-229 450l-623 600q-18 18-44 18z"/></svg>
            <?php else : ?>
            <svg class="like_review" data-type="like" xmlns="http://www.w3.org/2000/svg" width="18" height="18" x="0" y="0" version="1.1" viewBox="0 0 29 29" xml:space="preserve"><path d="M14.5 25.892a.997.997 0 0 1-.707-.293l-9.546-9.546c-2.924-2.924-2.924-7.682 0-10.606 2.808-2.81 7.309-2.923 10.253-.332 2.942-2.588 7.443-2.479 10.253.332 2.924 2.924 2.924 7.683 0 10.606l-9.546 9.546a.997.997 0 0 1-.707.293zM9.551 5.252a5.486 5.486 0 0 0-3.89 1.608 5.505 5.505 0 0 0 0 7.778l8.839 8.839 8.839-8.839a5.505 5.505 0 0 0 0-7.778 5.505 5.505 0 0 0-7.778 0l-.354.354a.999.999 0 0 1-1.414 0l-.354-.354a5.481 5.481 0 0 0-3.888-1.608z"/></svg>
            <?php endif; ?>
            <svg class="loading_like" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="20" height="20" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <path fill="#000" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
                    <animateTransform attributeType="xml"
                    attributeName="transform"
                    type="rotate"
                    from="0 25 25"
                    to="360 25 25"
                    dur="0.6s"
                    repeatCount="indefinite"/>
                </path>
            </svg>
            <span class="like_review_count"><?php echo $likes; ?></span>
         </div>
        <div>
            <div>
                <svg  class="share_review" data-image="<?php echo wp_upload_dir()['baseurl'] . '/reviews/' . $item->comment_post_ID . '/' . $item->comment_ID . '.jpg'; ?>" data-copy="<?php echo home_url('reviews/' . $item->comment_ID ); ?>" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="20px" height="20px"><path d="M 36 5 C 32.151772 5 29 8.1517752 29 12 C 29 12.585766 29.198543 13.109464 29.335938 13.654297 L 17.345703 19.652344 C 16.059118 18.073938 14.181503 17 12 17 C 8.1517722 17 5 20.151775 5 24 C 5 27.848225 8.1517722 31 12 31 C 14.181503 31 16.059118 29.926062 17.345703 28.347656 L 29.335938 34.345703 C 29.198543 34.890536 29 35.414234 29 36 C 29 39.848225 32.151772 43 36 43 C 39.848228 43 43 39.848225 43 36 C 43 32.151775 39.848228 29 36 29 C 33.818497 29 31.940882 30.073938 30.654297 31.652344 L 18.664062 25.654297 C 18.801457 25.109464 19 24.585766 19 24 C 19 23.414234 18.801457 22.890536 18.664062 22.345703 L 30.654297 16.347656 C 31.940882 17.926062 33.818497 19 36 19 C 39.848228 19 43 15.848225 43 12 C 43 8.1517752 39.848228 5 36 5 z M 36 8 C 38.226909 8 40 9.7730927 40 12 C 40 14.226907 38.226909 16 36 16 C 33.773091 16 32 14.226907 32 12 C 32 9.7730927 33.773091 8 36 8 z M 12 20 C 14.226909 20 16 21.773093 16 24 C 16 26.226907 14.226909 28 12 28 C 9.7730915 28 8 26.226907 8 24 C 8 21.773093 9.7730915 20 12 20 z M 36 32 C 38.226909 32 40 33.773093 40 36 C 40 38.226907 38.226909 40 36 40 C 33.773091 40 32 38.226907 32 36 C 32 33.773093 33.773091 32 36 32 z"/></svg>
            </div>
            <div>
                <svg class="flag_review" width="20"  height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M22.23,13l4.58-6.42A1,1,0,0,0,26,5H7V4A1,1,0,0,0,5,4V28a1,1,0,0,0,2,0V21H26a1,1,0,0,0,.81-1.58ZM7,19V7H24.06l-3.87,5.42a1,1,0,0,0,0,1.16L24.06,19Z" data-name="flag"/></svg>
            </div>
        </div>
    </div>
    <?php
        $args = array(
            'parent' => $item->comment_ID,
            'hierarchical' => true,
        );
        $questions = get_comments($args);
    ?>
    <?php if(count($questions) > 0): ?>
    <div class="item-reply">
        <div class="reply-header">
            <strong class="reply-title"><span> پاسخ از </span><?php echo $title ?></strong>
            <p class="reply-date">
                <?php echo functions::timeDiff($questions[0]->comment_date); ?>
            </p>
        </div>
        <div class="reply-content"><?php echo $questions[0]->comment_content; ?></div>
    </div>
    <?php endif; ?>
</div>
