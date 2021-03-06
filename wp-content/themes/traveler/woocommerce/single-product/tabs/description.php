<?php
/**
 * Description tab
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version   4.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Product Description', ST_TEXTDOMAIN ) ) );

?>

<?php if ( $heading ): ?>
  <h2 class="tab-content-title"><?php echo balanceTags($heading); ?></h2>
<?php endif; ?>

<?php the_content(); ?>
