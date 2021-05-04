<?php
global $col_loop_pos; 
?>
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-6 px-0 loop-post loop-smallpost '.$col_loop_pos); ?>>

		<div class="d-flex flex-column loop-content">
			<h3 class="loop-title my-auto"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		</div>

	</article>
	<!-- /article -->

