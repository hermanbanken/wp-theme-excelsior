<?php
	
class Color {
	public $a = 0;
	public $b = 0;
	public $c = 0;
	
	function __construct($a, $b, $c){
		$this->a = $a;
		$this->b = $b;
		$this->c = $c;
	}
	
	private static function contain($a){
		return max(0, min(255,$a));
	}
	
	function invert(){
		return new Color(255 - $this->a, 255 - $this->b, 255 - $this->c);
	}
	function invertLargest(){
		$peek = $this->locatePeek();
		return new Color(
			$peek === 0 ? 256/2 : $this->a,
			$peek === 1 ? 256/2 : $this->b,
			$peek === 2 ? 256/2 : $this->c
		);
	}
	
	function distance(Color $other){
		return (pow($this->a - $other->a,2)/255 + pow($this->b - $other->b,2)/255 + pow($this->c - $other->c,2)/255)/3/255;
	}
	
	static function random(){
		return new Color(rand(0, 255),rand(0, 255),rand(0, 255));
	}
	
	function diff($other){
		return new Color(
			$this->a - $other->a,
			$this->b - $other->b,
			$this->c - $other->c
		);
	}
	
	function to(Color $other, $where){
		$diff = $this->diff($other);
		return new Color(
			$this->a - $diff->a * $where,
			$this->b - $diff->b * $where,
			$this->c - $diff->c * $where
		);
	}
	
	static function white(){ return new Color(255,255,255); }
	static function black(){ return new Color(0,0,0); }
	
	function __toString(){
		$hex = "#";
		$hex .= str_pad(dechex($this->a), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($this->b), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($this->c), 2, "0", STR_PAD_LEFT);
		return $hex;
	}
	
	private function locatePeek(){
		$peek = 0;
		$color = -1;
		if(abs($this->a - 255/2) > $peek)
		{
			$peek = abs($this->a - 255/2); $color = 0;
		}
		if(abs($this->b - 255/2) > $peek)
		{
			$peek = abs($this->b - 255/2); $color = 1;
		}
		if(abs($this->c - 255/2) > $peek)
		{
			$peek = abs($this->c - 255/2); $color = 2;
		}
		return $color;
	}
	
	static function fromHex($hex){
		if(substr($hex, 0, 1) == "#")
			$hex = substr($hex, 1);
		return new Color(hexdec(substr($hex, 0, 2)),hexdec(substr($hex, 2, 2)),hexdec(substr($hex, 4, 2)));
	}
}

add_action( 'add_meta_boxes', 'roots_add_color_box');
add_action( 'save_post', 'roots_color_box_savedata' );
add_action( 'admin_enqueue_scripts', 'roots_enqueue_color_picker' );

/* Enqueue color picker */
function roots_enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
}

/* Adds a box to the main column on the Post edit screen */
function roots_add_color_box() {
    $screens = array( 'post' );
    foreach ($screens as $screen) {
        add_meta_box(
            'roots_colorbox',
            __( 'Colorpicker', 'roots' ),
            'roots_color_box_html',
            $screen,
						'side'
        );
    }
}

/* Prints the box content */
function roots_color_box_html() {
	global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'roots_noncename' );
	$value = get_post_meta( $post->ID, '_roots_bg_color', true );
	
	echo '<label for="roots_bg_color">';
	_e("Selecteer een achtergrondkleur voor dit bericht", 'roots' );
	echo '</label> ';
	echo '<input type="text" id="roots_bg_color" name="roots_bg_color" value="'.esc_attr($value).'" data-default-color="#02b09b" size="7" />';
}
	
/* When the post is saved, saves our custom data */
function roots_color_box_savedata( $post_id ) {
	// First we need to check if the current user is authorised to do this action. 
  if ( ! current_user_can( 'edit_post', $post_id ) )
  		return;
	
  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['roots_noncename'] ) || ! wp_verify_nonce( $_POST['roots_noncename'], plugin_basename( __FILE__ ) ) )
      return;
	
	// Thirdly we can save the value to the database
  $color = sanitize_text_field( $_POST['roots_bg_color'] );
	
  $result = update_post_meta($post_id, '_roots_bg_color', $color);
}
?>