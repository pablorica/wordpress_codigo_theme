<?php
/**
 * Block Name: Headline
 *
 * This is the template that displays the headline block.
 *
 * @param   array $block The block settings and attributes.
 * @param   bool $is_preview True during AJAX preview.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (!function_exists('get_field')) {
	exit; // Exit if ACF is not enabled
}



if(get_field('bmodal_enable')):

    $modal_title = get_field('bmodal_title');
    $modal_cta   = get_field('bmodal_cta');
?>
	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary modal-trigger" data-toggle="modal" data-target="#homeModal" data-updated="<?=get_option('popup_updated')?>">Pop-Up</button>
	<!-- Modal -->
	<div class="modal fade" id="homeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="text-center"><?=$modal_title?></div>
				</div>
				<div class="modal-footer">
				<?php 
				if( $modal_cta): 
					$link_url = $modal_cta['url'];
					$link_title = $modal_cta['title'];
					$link_target = $modal_cta['target'] ? $modal_cta['target'] : '_self';
					?>
					<a role="button" class="btn btn-white" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- !Modal -->
<?php endif;  ?>