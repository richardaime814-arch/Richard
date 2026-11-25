<?php
/**
 * Page standard (Mentions légales, etc.).
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-head">
		<div class="container">
			<h1 class="h-hero h-hero--page"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="section section--flush-top">
		<div class="container">
			<div class="prose prose--page"><?php the_content(); ?></div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
