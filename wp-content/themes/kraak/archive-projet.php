<?php
/**
 * Page Projets : grille filtrable (Tous, Architecture, Recherche, Concours, Expérimental),
 * vue grille / liste et « Charger plus ».
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

$kraak_current = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : ''; // phpcs:ignore
$kraak_base    = get_post_type_archive_link( 'projet' );
$kraak_terms   = get_terms( array( 'taxonomy' => 'type_projet', 'hide_empty' => false, 'orderby' => 'meta_value_num', 'meta_key' => 'kraak_order' ) ); // phpcs:ignore
if ( is_wp_error( $kraak_terms ) || ! $kraak_terms ) {
	$kraak_terms = get_terms( array( 'taxonomy' => 'type_projet', 'hide_empty' => false ) );
}
?>

<section class="page-head">
	<div class="container">
		<?php kraak_label( __( 'Index', 'kraak' ), __( 'Projets', 'kraak' ) ); ?>
		<h1 class="h-hero"><?php esc_html_e( 'Projets', 'kraak' ); ?></h1>
		<p class="lead text-muted"><?php echo esc_html( kraak_opt( 'projects_intro' ) ); ?></p>
	</div>
</section>

<section class="section section--flush-top">
	<div class="container">
		<div class="toolbar">
			<ul class="filters" aria-label="<?php esc_attr_e( 'Filtrer par type', 'kraak' ); ?>">
				<li><a class="chip<?php echo $kraak_current ? '' : ' is-active'; ?>" href="<?php echo esc_url( $kraak_base ); ?>"><?php esc_html_e( 'Tous', 'kraak' ); ?></a></li>
				<?php foreach ( (array) $kraak_terms as $t ) : ?>
					<li><a class="chip<?php echo $kraak_current === $t->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'type', $t->slug, $kraak_base ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<div class="view-toggle hide-mobile" role="group" aria-label="<?php esc_attr_e( 'Affichage', 'kraak' ); ?>">
				<button type="button" data-view="grid" class="is-active" aria-pressed="true"><?php esc_html_e( 'Grille', 'kraak' ); ?></button>
				<button type="button" data-view="list" aria-pressed="false"><?php esc_html_e( 'Liste', 'kraak' ); ?></button>
			</div>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="project-grid" data-grid>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card-projet', null, array( 'size' => 'kraak-card' ) );
				endwhile;
				?>
			</div>

			<?php $kraak_next = get_next_posts_page_link(); ?>
			<?php if ( $GLOBALS['wp_query']->max_num_pages > max( 1, (int) get_query_var( 'paged' ) ) && $kraak_next ) : ?>
				<div class="load-more">
					<a class="btn btn--outline" href="<?php echo esc_url( $kraak_next ); ?>" data-load-more><?php esc_html_e( 'Charger plus de projets', 'kraak' ); ?></a>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Aucun projet dans cette catégorie pour le moment.', 'kraak' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
