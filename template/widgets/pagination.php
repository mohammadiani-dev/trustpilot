<?php
    if ($data['total_page'] > 1) {
    echo '<div data-ajax="'.admin_url('admin-ajax.php').'" class="pagination_custom_scores"><span class="des">صفحه '.$data['page'].' از '.$data['total_page'].'</span>'
    .paginate_links(array(
        'base' => add_query_arg('cpage', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => $data['total_page'],
        'current' => $data['page']
    ))
    .'</div>';
}