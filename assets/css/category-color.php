<?php 
header('Content-type: text/css'); 
require("../../lib/color.class.php");

$body = "body.blog";

foreach($_GET as $slug => $color){
	if(
		!preg_match("|^[\d]+$|", $slug) && 
		preg_match("|^[\w\d\-]+$|", $slug) && 
		preg_match("/^(rgb\(\d{1,3},\d{1,3},\d{1,3}\)|#[0-9a-f]{3}|#[0-9abcdef]{6})$/", urldecode($color))
	){ 
		$color = Color::fromHex($color);
		
		$isDark = $color->distance(Color::white()) > $color->distance(Color::black());
		$colorNeutralize = $color->shift($isDark ? 16 : -32);
		$colorText = $isDark ? Color::white() : Color::black();
		$colorLink = $colorText; //$color->distance(Color::fromHex("#02b09b")) > $color->distance(Color::fromHex("#48648e")) ? Color::fromHex("#02b09b") : Color::fromHex("#48648e");
		$colorInv = $color->invertLargest();
?>
/* Custom colors for: <?php echo $slug; ?> */
<?php echo $body; ?> article.layout-archive.category-<?php echo $slug; ?> .inner a {	color: <?php echo $colorLink; ?>; font-weight: bold; }
<?php echo $body; ?> article.layout-archive.category-<?php echo $slug; ?> .inner { background-color: <?php echo $color; ?>; color: <?php echo $colorText; ?>; }
<?php echo $body; ?> article.layout-archive.category-<?php echo $slug; ?> .inner header a { color: <?php echo $colorText; ?>; font-weight: 300; }
<?php echo $body; ?> article.layout-archive.category-<?php echo $slug; ?> .inner .shade { background-color: <?php echo $colorNeutralize; ?>; }
<?php }
}

?>