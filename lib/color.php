<?php
require("color.class.php");
/**
 * Category custom Color field
 */
add_action( 'wp_enqueue_scripts', 'roots_enqueue_color_stylesheets', 200 );
add_action( 'admin_enqueue_scripts', 'roots_enqueue_color_picker', 1000 );
add_action( 'edit_category_form_fields', 'roots_edit_category', 10, 1);
add_action( 'edited_category', 'roots_save_category', 10, 2);

function roots_enqueue_color_stylesheets() {
	$colors = get_option("category_color");
	$query = "?todo=RELATIEF";
	foreach($colors as $id => $color) $query .= ($query ? "&" : "") . "$id=".urlencode($color);
	
	wp_register_style( 'roots-category-color', "/wp-content/themes/Excelsior_2013/assets/css/category-color.php$query", false, null);
	wp_enqueue_style( 'roots-category-color' );
}

/* Enqueue color picker */
function roots_enqueue_color_picker() {
	wp_enqueue_style( 'wp-color-picker' );
}

/* Custom html */
function roots_edit_category($tag)
{
	$default = "#02b09b";
	
	$colors = get_option("category_color");
	$category_color = is_array($colors) ? $colors[$tag->slug] : false;
	if(!$category_color) $category_color = $default;
  ?>
     <tr class="form-field">
         <th scope="row" valign="top"><label for="category_color"><?php _e("Color", "roots"); ?></label></th>
         <td>
             <input name="category_color" id="category_color" type="text" value="<?php echo esc_attr($category_color); ?>" data-default-color="<?php echo $default; ?>" size="7" />
             <p class="description"><?php _e("A color used to differ posts in different categories.", "roots"); ?></p>
         </td>
     </tr>
	<?php
}
/* Custom saving function */
function roots_save_category($term_id, $tt_id)
{
	if (!$term_id) return;
	
	$term = get_term_by("id", $term_id, 'category');
	
	$colors = get_option("category_color");
	if(!$colors) $colors = array();
	
	if (isset($_POST['category_color']) && preg_match("/^(rgb\(\d{1,3},\d{1,3},\d{1,3}\)|#[0-9a-f]{3}|#[0-9abcdef]{6})$/", $_POST['category_color'])){
		$colors[$term->slug] = $_POST['category_color'];
		update_option("category_color", $colors);
	}
}

// /**
//  * Post custom Color field
//  */
// 
// add_action( 'add_meta_boxes', 'roots_add_color_box');
// add_action( 'save_post', 'roots_color_box_savedata' );
// 
// /* Adds a box to the main column on the Post edit screen */
// function roots_add_color_box() {
//     $screens = array( 'post' );
//     foreach ($screens as $screen) {
//         add_meta_box(
//             'roots_colorbox',
//             __( 'Colorpicker', 'roots' ),
//             'roots_color_box_html',
//             $screen,
// 						'side'
//         );
//     }
// }
// 
// /* Prints the box content */
// function roots_color_box_html() {
// 	global $post;
// 	wp_nonce_field( plugin_basename( __FILE__ ), 'roots_noncename' );
// 	$value = get_post_meta( $post->ID, '_roots_bg_color', true );
// 	
// 	echo '<label for="roots_bg_color">';
// 	_e("Selecteer een achtergrondkleur voor dit bericht", 'roots' );
// 	echo '</label> ';
// 	echo '<input type="text" id="roots_bg_color" name="roots_bg_color" value="'.esc_attr($value).'" data-default-color="#02b09b" size="7" />';
// }
// 	
// /* When the post is saved, saves our custom data */
// function roots_color_box_savedata( $post_id ) {
// 	// First we need to check if the current user is authorised to do this action. 
//   if ( ! current_user_can( 'edit_post', $post_id ) )
//   		return;
// 	
//   // Secondly we need to check if the user intended to change this value.
//   if ( ! isset( $_POST['roots_noncename'] ) || ! wp_verify_nonce( $_POST['roots_noncename'], plugin_basename( __FILE__ ) ) )
//       return;
// 	
// 	// Thirdly we can save the value to the database
//   $color = sanitize_text_field( $_POST['roots_bg_color'] );
// 	
//   $result = update_post_meta($post_id, '_roots_bg_color', $color);
// }
?>