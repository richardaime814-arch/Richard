<?php
/**
 * Page Distinctions : distinction mise en avant + listes par catégorie
 * (Prix, Concours, Expositions, Publications, Conférences).
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

// Regroupe les distinctions par catégorie.
$kraak_groups = array();
$kraak_feat   = null;
while ( have_posts() ) {
	the_post();
	$p = get_post();
	if ( ! $kraak_feat && get_post_meta( $p->ID, 'kraak_featured', true ) ) {
		$kraak_feat = $p;
	}
	$t   = kraak_term( $p, 'categorie_distinction' );
	$key = $t ? $t->slug : 'autres';
	if ( ! isset( $kraak_groups[ $key ] ) ) {
		$kraak_groups[ $key ] = array( 'name' => $t ? $t->name : __( 'Autres', 'kraak' ), 'items' => array() );
	}
	$kraak_groups[ $key ]['items'][] = $p;
}
// Ordre des catégories.
$kraak_order = array_keys( kraak_default_terms()['categorie_distinction'] );
uksort( $kraak_groups, function ( $a, $b ) use ( $kraak_order ) {
	$ia = array_search( $a, $kraak_order, true );
	$ib = array_search( $b, $kraak_order, true );
	return ( false === $ia ? 99 : $ia ) - ( false === $ib ? 99 : $ib );
} );
?>

<section class="page-head">
	<div class="container">
		<?php kraak_label( 'D', __( 'Publications / Distinctions', 'kraak' ) ); ?>
		<h1 class="h-hero"><?php esc_html_e( 'Distinctions', 'kraak' ); ?></h1>
		<p class="lead text-muted"><?php echo esc_html( kraak_opt( 'distinctions_intro' ) ); ?></p>
	</div>
</section>

<?php if ( $kraak_feat ) : ?>
	<?php
	$kf_project = (int) get_post_meta( $kraak_feat->ID, 'kraak_projet', true );
	$kf_link    = $kf_project ? get_permalink( $kf_project ) : get_post_meta( $kraak_feat->ID, 'kraak_link', true );
	$kf_img     = has_post_thumbnail( $kraak_feat ) ? get_the_post_thumbnail( $kraak_feat, 'kraak-card', array( 'alt' => '' ) ) : ( $kf_project ? get_the_post_thumbnail( $kf_project, 'kraak-card', array( 'alt' => '' ) ) : '' );
	?>
	<section class="section section--flush-top">
		<div class="container split split--center featured-award">
			<?php if ( $kf_img ) : ?><figure><?php echo $kf_img; // phpcs:ignore ?></figure><?php endif; ?>
			<div>
				<?php kraak_label( get_post_meta( $kraak_feat->ID, 'kraak_annee', true ), __( 'Prix', 'kraak' ) ); ?>
				<h2 class="h-lg"><?php echo esc_html( get_the_title( $kraak_feat ) ); ?><?php echo false === strpos( get_the_title( $kraak_feat ), (string) get_post_meta( $kraak_feat->ID, 'kraak_annee', true ) ) ? ' ' . esc_html( get_post_meta( $kraak_feat->ID, 'kraak_annee', true ) ) : ''; ?></h2>
				<?php if ( $kraak_feat->post_content ) : ?><p class="text-muted"><?php echo esc_html( wp_strip_all_tags( $kraak_feat->post_content ) ); ?></p><?php endif; ?>
				<?php if ( $kf_link ) : ?>
					<a class="link-arrow" href="<?php echo esc_url( $kf_link ); ?>"><?php echo $kf_project ? esc_html__( 'Voir le projet lauréat', 'kraak' ) : esc_html__( 'En savoir plus', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="section section--flush-top">
	<div class="container">
		<?php $kn = 0; foreach ( $kraak_groups as $group ) : $kn++; ?>
			<div class="award-group">
				<?php kraak_label( sprintf( '%02d', $kn ), $group['name'] ); ?>
				<ul class="award-list">
					<?php foreach ( $group['items'] as $item ) : ?>
						<?php
						$klink = get_post_meta( $item->ID, 'kraak_link', true );
						$kproj = (int) get_post_meta( $item->ID, 'kraak_projet', true );
						$kurl  = $klink ? $klink : ( $kproj ? get_permalink( $kproj ) : '' );
						?>
						<li>
							<span class="mono-label"><?php echo esc_html( get_post_meta( $item->ID, 'kraak_annee', true ) ); ?></span>
							<?php if ( $kurl ) : ?>
								<a class="award-list__title" href="<?php echo esc_url( $kurl ); ?>"<?php echo $klink ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( get_the_title( $item ) ); ?></a>
							<?php else : ?>
								<span class="award-list__title"><?php echo esc_html( get_the_title( $item ) ); ?></span>
							<?php endif; ?>
							<span class="mono-label award-list__mention"><?php echo esc_html( get_post_meta( $item->ID, 'kraak_mention', true ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
		<?php if ( ! $kraak_groups ) : ?>
			<p class="empty"><?php esc_html_e( 'Les distinctions seront bientôt publiées.', 'kraak' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
