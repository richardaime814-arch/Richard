<?php
/**
 * Page d'accueil.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

$kraak_slides   = kraak_get_projects( true, 5 );
$kraak_projects = kraak_get_projects( false, 5 );
$kraak_total    = count( $kraak_slides );
?>

<section class="hero" data-hero aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Projets à la une', 'kraak' ); ?>">
	<div class="hero__slides">
		<?php foreach ( $kraak_slides as $i => $slide ) : ?>
			<?php $kterm = kraak_term( $slide, 'type_projet' ); ?>
			<div class="hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide="<?php echo esc_attr( $i ); ?>" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%d / %d', $i + 1, $kraak_total ) ); ?>"<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>>
				<div class="hero__bg">
					<?php echo get_the_post_thumbnail( $slide, 'kraak-hero', array( 'loading' => 0 === $i ? 'eager' : 'lazy', 'fetchpriority' => 0 === $i ? 'high' : 'auto', 'alt' => '' ) ); ?>
				</div>
				<div class="hero__caption container">
					<p class="hero__caption-meta">
						<span><?php echo esc_html( get_the_title( $slide ) ); ?></span>
						<?php if ( $kterm ) : ?><span><?php echo esc_html( $kterm->name ); ?></span><?php endif; ?>
						<?php if ( get_post_meta( $slide->ID, 'kraak_annee', true ) ) : ?><span><?php echo esc_html( get_post_meta( $slide->ID, 'kraak_annee', true ) ); ?></span><?php endif; ?>
					</p>
					<a class="btn btn--light" href="<?php echo esc_url( get_permalink( $slide ) ); ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>"><?php esc_html_e( 'Voir le projet', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="hero__content container">
		<p class="label label--light">
			<?php echo kraak_square(); // phpcs:ignore ?>
			<span class="label__num" data-hero-count><?php echo esc_html( sprintf( 'P–%02d / %02d', 1, max( 1, $kraak_total ) ) ); ?></span>
			<span class="label__line"></span>
			<span class="label__text"><?php esc_html_e( 'À la une', 'kraak' ); ?></span>
		</p>
		<h1 class="hero__title"><?php echo nl2br( esc_html( kraak_opt( 'hero_title' ) ) ); ?></h1>
	</div>

	<div class="hero__bottom container">
		<ol class="hero__progress">
			<?php foreach ( $kraak_slides as $i => $slide ) : ?>
				<li><button type="button" data-goto="<?php echo esc_attr( $i ); ?>" class="<?php echo 0 === $i ? 'is-active' : ''; ?>" aria-label="<?php echo esc_attr( get_the_title( $slide ) ); ?>"><span><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><i></i></button></li>
			<?php endforeach; ?>
		</ol>
		<a class="hero__scroll" href="#le-studio"><?php esc_html_e( 'Faire défiler', 'kraak' ); ?> <?php echo kraak_arrow( 'down' ); // phpcs:ignore ?></a>
	</div>
</section>

<section class="section home-studio" id="le-studio">
	<div class="container">
		<?php kraak_label( '01', __( 'Le studio', 'kraak' ) ); ?>
		<div class="split">
			<h2 class="h-xl"><?php echo esc_html( kraak_opt( 'studio_title' ) ); ?></h2>
			<div class="split__aside">
				<p class="text-muted"><?php echo esc_html( kraak_opt( 'studio_text' ) ); ?></p>
				<a class="link-arrow" href="<?php echo esc_url( kraak_studio_url() ); ?>"><?php esc_html_e( 'Découvrir le studio', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</section>

<section class="section section--tight home-projects">
	<div class="container">
		<?php kraak_label( '02', __( 'Projets sélectionnés', 'kraak' ) ); ?>
		<div class="section-head">
			<h2 class="h-display"><?php esc_html_e( 'Projets', 'kraak' ); ?></h2>
			<a class="link-arrow hide-mobile" href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>"><?php esc_html_e( 'Voir tous les projets', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
		</div>
		<div class="home-grid">
			<?php
			global $post;
			foreach ( $kraak_projects as $i => $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				setup_postdata( $post );
				$is_wide = $i < 2;
				get_template_part( 'template-parts/card-projet', null, array(
					'size'  => $is_wide ? 'kraak-card' : 'kraak-tall',
					'class' => $is_wide ? 'card--wide' : ( 3 === $i ? 'card--short' : 'card--tall' ),
				) );
			endforeach;
			wp_reset_postdata();
			?>
		</div>
		<a class="btn btn--outline btn--block only-mobile" href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>"><?php esc_html_e( 'Tous les projets', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
	</div>
</section>

<section class="section section--dark home-lab">
	<div class="container split split--center">
		<div>
			<?php kraak_label( '03', __( 'Laboratoire', 'kraak' ) ); ?>
			<h2 class="h-xl"><?php echo esc_html( kraak_opt( 'lab_title' ) ); ?></h2>
			<p class="text-muted hide-mobile"><?php echo esc_html( kraak_opt( 'lab_text' ) ); ?></p>
			<ul class="index-list index-list--dark hide-mobile">
				<?php foreach ( kraak_opt_lines( 'lab_lines' ) as $i => $line ) : ?>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'recherche' ) ); ?>"><span><?php echo esc_html( $line ); ?></span><small><?php echo esc_html( sprintf( 'R–%02d', $i + 1 ) ); ?></small></a></li>
				<?php endforeach; ?>
			</ul>
			<a class="link-arrow link-arrow--light hide-mobile" href="<?php echo esc_url( get_post_type_archive_link( 'recherche' ) ); ?>"><?php esc_html_e( 'Entrer dans le laboratoire', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
		</div>
		<figure class="home-lab__media">
			<?php echo kraak_opt_image( 'lab_image', 'atelier-plans', 'large', array( 'alt' => '' ) ); // phpcs:ignore ?>
		</figure>
		<a class="link-arrow link-arrow--light only-mobile" href="<?php echo esc_url( get_post_type_archive_link( 'recherche' ) ); ?>"><?php esc_html_e( 'Entrer dans le laboratoire', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
	</div>
</section>

<section class="section home-awards">
	<div class="container">
		<?php kraak_label( '04', __( 'Distinctions', 'kraak' ) ); ?>
		<div class="section-head hide-mobile">
			<h2 class="h-display"><?php esc_html_e( 'Reconnaissances', 'kraak' ); ?></h2>
			<a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'distinction' ) ); ?>"><?php esc_html_e( 'Toutes les distinctions', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
		</div>
		<div class="awards-cols">
			<?php
			$kraak_awards = get_posts( array(
				'post_type'      => 'distinction',
				'posts_per_page' => 3,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
				'tax_query'      => array( array( 'taxonomy' => 'categorie_distinction', 'field' => 'slug', 'terms' => 'prix' ) ), // phpcs:ignore
			) );
			foreach ( $kraak_awards as $award ) :
				?>
				<article class="award-col">
					<p class="mono-label"><?php echo esc_html( get_post_meta( $award->ID, 'kraak_annee', true ) ); ?></p>
					<h3><?php echo esc_html( get_the_title( $award ) ); ?></h3>
					<?php if ( $award->post_content ) : ?><p class="text-small hide-mobile"><?php echo esc_html( wp_strip_all_tags( $award->post_content ) ); ?></p><?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section home-cta">
	<div class="container">
		<div class="hide-mobile"><?php kraak_label( '05', __( 'Contact', 'kraak' ) ); ?></div>
		<h2 class="h-display h-display--cta"><?php echo esc_html( kraak_opt( 'cta_title' ) ); ?></h2>
		<div class="btn-row">
			<a class="btn btn--dark" href="<?php echo esc_url( kraak_contact_url() ); ?>"><?php esc_html_e( 'Écrire au studio', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
			<a class="btn btn--outline hide-mobile" href="mailto:<?php echo esc_attr( kraak_opt( 'email' ) ); ?>"><?php echo esc_html( kraak_opt( 'email' ) ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
