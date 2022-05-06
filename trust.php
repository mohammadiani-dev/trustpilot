<?php
/*
Plugin Name: trust pilot
Plugin URI: http://mohammadiani.com
Author: یوسف محمدیانی
Version: 1.1.0
Text Domain: trust pilot
Domain Path: /languages
Author URI: http://mohammadiani.com
*/

if(!defined("TRUST_PILOT_PATH")) define("TRUST_PILOT_PATH",plugin_dir_path(__FILE__));
if(!defined("TRUST_PILOT_URL")) define("TRUST_PILOT_URL",plugin_dir_url(__FILE__));
if(!defined("TRUST_PILOT_DB_VER")) define("TRUST_PILOT_DB_VER","1.0.1");
if(!defined("TRUST_PILOT_ASSETS_VER")) define("TRUST_PILOT_ASSETS_VER","12.8.0");
if(!defined("TRUST_PILOT_PREFIX")) define("TRUST_PILOT_PREFIX","trpi_");

use TrustPilot\init;


add_action("init" , function(){
            add_rewrite_rule(
        'reviews/([0-9]+)/?$',
        'index.php?pagename=reviews&review_id=$matches[1]',
        'top' );
} , 10 , 1);

require_once __DIR__ . '/vendor/autoload.php';
new init;



// Add term page
function pippin_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[term_icon]">آیکون دسته بندی</label>
		<input type="text" name="term_meta[term_icon]" id="term_meta[term_icon]" value="">
		<p class="description">نام کلاس آیکون</p>
	</div>
<?php
}
add_action( 'categories_add_form_fields', 'pippin_taxonomy_add_new_meta_field', 10, 2 );



// Edit term page
function pippin_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[term_icon]">آیکون دسته بندی</label></th>
		<td>
			<input type="text" name="term_meta[term_icon]" id="term_meta[term_icon]" value="<?php echo esc_attr( $term_meta['term_icon'] ) ? esc_attr( $term_meta['term_icon'] ) : ''; ?>">
            <p class="description">نام کلاس آیکون</p>
		</td>
	</tr>
<?php
}
add_action( 'categories_edit_form_fields', 'pippin_taxonomy_edit_meta_field', 10, 2 );




// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_categories', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_categories', 'save_taxonomy_custom_meta', 10, 2 );