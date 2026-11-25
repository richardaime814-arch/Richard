<?php
/**
 * Gabarit de secours (articles, recherche, archives génériques).
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="page-head">
	<div class="container">
		<h1 class="h-hero"><?php echo is_search() ? esc_html__( 'Recherche', 'kraak' ) : ( is_archive() ? wp_kses_post( get_the_archive_title() ) : esc_html__( 'Actualités', 'kraak' ) ); ?></h1>
	</div>
</section>
<section class="section section--flush-top">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="research-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<li><a href="<?php the_permalink(); ?>"><span class="mono-label"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span><span class="research-list__title"><?php the_title(); ?></span><span></span><span></span><?php echo kraak_arrow(); // phpcs:ignore ?></a></li>
				<?php endwhile; ?>
			</ul>
			<div class="pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Aucun contenu trouvé.', 'kraak' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
