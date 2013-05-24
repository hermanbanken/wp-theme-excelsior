<?php
/**
 * Handles Facebook related features including
 * - post commenting login with facebook
 * - liking
 */

add_action( 'wp_footer', 'roots_facebook_channel', 100);
add_action( 'customize_register', 'roots_facebook_settings' );
add_action( 'pre_comment_on_post', 'roots_facebook_save_comment', 100, 1 );

function roots_facebook_settings( $wp_customize )
{
	// Setting
	$wp_customize->add_setting( 'facebook_app_id' , array( 'default'     => '', 'transport'   => 'refresh' ) );
	$wp_customize->add_setting( 'facebook_app_secret' , array( 'default'     => '', 'transport'   => 'refresh' ) );
	
	// Make it available
	$wp_customize->add_section( 'roots_facebook_settings' , array(
	    'title'      => __('Facebook settings','roots'),
	    'priority'   => 1000,
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'facebook_app_id', array(
		'label'        => __( 'App ID', 'roots' ),
		'section'    => 'roots_facebook_settings',
		'settings'   => 'facebook_app_id',
	) ) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'facebook_app_secret', array(
		'label'        => __( 'App Secret', 'roots' ),
		'section'    => 'roots_facebook_settings',
		'settings'   => 'facebook_app_secret',
	) ) );
}

function roots_facebook_channel(){
	echo "<!--Starting Facebook channel stuff -->";
	$appid = get_theme_mod("facebook_app_id");
	$channelUrl = false;
	
	if($appid){
		?>
		<script>
		$(document).ready(function() {
		  $.ajaxSetup({ cache: true });
		  $.getScript('//connect.facebook.net/<?php echo get_locale() ?>/all.js', function(){
		    window.fbAsyncInit = function() {
		      FB.init({
		        appId: '<?php echo $appid ?>',
		        <?php if($channelUrl) echo "channelUrl: '$channelUrl'," ?>
		      });       
		      $('.dependsOn-facebook').removeClass('disabled').removeAttr('disabled');
		      FB.getLoginStatus(function updateStatusCallback(){
		      	$(window).trigger("updateFBStatusCallback", arguments);
		      });
		    };
		  });
		});
		</script>
		<?php
	}
	echo "<!--End of Facebook channel stuff -->";
}

function roots_facebook_save_comment($comment_post_ID){
	global $comment_post_ID, $user_ID, $comment_type, $user_ID;
	
	if(!isset($_POST['facebook']) || empty($_POST['facebook'])) die("Missing facebook sign");;
	// Only if app secret is set
	$app_id = get_theme_mod("facebook_app_id");
	$secret = get_theme_mod("facebook_app_secret");
	if(!$app_id || !$secret) return;
	
	$sr = $_POST['facebook'];
	$data = roots_fb_parse_signed_request($sr);
	// Invalid request
	if(!$data) return;
	
	require_once locate_template('/lib/vendor/facebook/facebook.php');
	$facebook = new Facebook(array("appId"=>$app_id,"secret"=>$secret));
	$facebook->setAccessToken($data->code); // Re-use OAuth access code
	$profile = $facebook->api('/'.$data['user_id'],'GET');
	
	$comment_author       = $profile['name'];
	$comment_author_email = $profile['email'];
	$comment_author_url   = $profile['link'];
	$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
	$comment_parent 			= isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
	
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
	
	$comment_id = wp_new_comment( $commentdata );

	$comment = get_comment($comment_id);
	$user = wp_get_current_user();
	do_action('set_comment_cookies', $comment, $user);

	$location = empty($_POST['redirect_to']) ? get_comment_link($comment_id) : $_POST['redirect_to'] . '#comment-' . $comment_id;
	$location = apply_filters('comment_post_redirect', $location, $comment);

	wp_safe_redirect( $location );
	exit;
}

function roots_fb_parse_signed_request($signed_request) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

	function base64_url_decode($input) {
	  return base64_decode(strtr($input, '-_', '+/'));
	}

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);
	
  $expected_sig = hash_hmac('sha256', $payload, get_theme_mod("facebook_app_secret"), $raw = true);
   if ($sig !== $expected_sig) {
     error_log('Bad Signed JSON signature!');
     return null;
   }

  return $data;
}