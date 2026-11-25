<?php
/**
 * Page Laboratoire : axes de recherche, index filtrable, recherche à la une.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

$kraak_current = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : ''; // phpcs:ignore
$kraak_base    = get_post_type_archive_link( 'recherche' );
$kraak_plural  = array(
	'concept'            => __( 'Concepts', 'kraak' ),
	'experimentation'    => __( 'Expérimentations', 'kraak' ),
	'diagramme'          => __( 'Diagrammes', 'kraak' ),
	'etude-parametrique' => __( 'Études paramétriques', 'kraak' ),
	'installation'       => __( 'Installations', 'kraak' ),
);
$kraak_terms = get_terms( array( 'taxonomy' => 'type_recherche', 'hide_empty' => false, 'orderby' => 'meta_value_num', 'meta_key' => 'kraak_order' ) ); // phpcs:ignore
if ( is_wp_error( $kraak_terms ) || ! $kraak_terms ) {
	$kraak_terms = get_terms( array( 'taxonomy' => 'type_recherche', 'hide_empty' => false ) );
}
$kraak_featured = get_posts( array( 'post_type' => 'recherche', 'posts_per_page' => 1, 'meta_key' => 'kraak_featured', 'meta_value' => '1' ) ); // phpcs:ignore
?>

<section class="page-head page-head--dark">
	<div class="container">
		<?php kraak_label( 'R', __( 'Recherche / Laboratoire', 'kraak' ) ); ?>
		<div class="split split--bottom">
			<h1 class="h-hero"><?php esc_html_e( 'Laboratoire', 'kraak' ); ?></h1>
			<p class="text-muted lead"><?php echo esc_html( kraak_opt( 'lab_intro' ) ); ?></p>
		</div>
		<div class="triptych">
			<?php
			echo kraak_opt_image( 'lab_img_1', 'village', 'kraak-card', array( 'alt' => '' ) ); // phpcs:ignore
			echo kraak_opt_image( 'lab_img_2', 'table-travail', 'kraak-card', array( 'alt' => '' ) ); // phpcs:ignore
			echo kraak_opt_image( 'lab_img_3', 'charpente-bois', 'kraak-card', array( 'alt' => '' ) ); // phpcs:ignore
			?>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php kraak_label( '01', __( 'Axes de recherche', 'kraak' ) ); ?>
		<div class="cols-3 axes">
			<?php foreach ( kraak_opt_lines( 'lab_axes', true ) as $i => $axe ) : ?>
				<article>
					<p class="mono-label"><?php echo esc_html( sprintf( 'R–%02d', $i + 1 ) ); ?></p>
					<h2 class="h-md"><?php echo esc_html( $axe[0] ); ?></h2>
					<p class="text-muted text-small"><?php echo esc_html( $axe[1] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--flush-top" id="index">
	<div class="container">
		<div class="toolbar toolbar--label">
			<?php kraak_label( '02', __( 'Index des recherches', 'kraak' ) ); ?>
			<ul class="filters filters--small" aria-label="<?php esc_attr_e( 'Filtrer par type', 'kraak' ); ?>">
				<li><a class="chip<?php echo $kraak_current ? '' : ' is-active'; ?>" href="<?php echo esc_url( $kraak_base . '#index' ); ?>"><?php esc_html_e( 'Tout', 'kraak' ); ?></a></li>
				<?php foreach ( (array) $kraak_terms as $t ) : ?>
					<li><a class="chip<?php echo $kraak_current === $t->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'type', $t->slug, $kraak_base ) . '#index' ); ?>"><?php echo esc_html( isset( $kraak_plural[ $t->slug ] ) ? $kraak_plural[ $t->slug ] : $t->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php if ( have_posts() ) : ?>
			<ul class="research-list">
				<?php
				while ( have_posts() ) :
					the_post();
					$kterm = kraak_term( get_post(), 'type_recherche' );
					?>
					<li>
						<a href="<?php the_permalink(); ?>">
							<span class="mono-label"><?php echo esc_html( kraak_code() ); ?></span>
							<span class="research-list__title"><?php the_title(); ?></span>
							<span class="mono-label research-list__type"><?php echo esc_html( $kterm ? $kterm->name : '' ); ?></span>
							<span class="mono-label research-list__year"><?php echo esc_html( kraak_meta( get_the_ID(), 'kraak_annee', __( '[Année]', 'kraak' ) ) ); ?></span>
							<?php echo kraak_arrow(); // phpcs:ignore ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Aucune recherche dans cette catégorie pour le moment.', 'kraak' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php if ( $kraak_featured ) : $kf = $kraak_featured[0]; ?>
	<section class="section">
		<div class="container split split--center featured-research">
			<figure><?php echo get_the_post_thumbnail( $kf, 'large', array( 'loading' => 'lazy', 'alt' => '' ) ); ?></figure>
			<div>
				<?php kraak_label( '03', __( 'Recherche à la une', 'kraak' ) ); ?>
				<h2 class="h-xl"><?php echo esc_html( get_the_title( $kf ) ); ?></h2>
				<p class="text-muted"><?php echo esc_html( get_the_excerpt( $kf ) ); ?></p>
				<a class="link-arrow" href="<?php echo esc_url( get_permalink( $kf ) ); ?>"><?php esc_html_e( 'Lire la recherche', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
