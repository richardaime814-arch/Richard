<?php
/**
 * Fiche d'une recherche du Laboratoire.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$kid   = get_the_ID();
	$kterm = kraak_term( $kid, 'type_recherche' );
	?>
	<?php if ( has_post_thumbnail() ) : ?>
		<section class="project-hero">
			<?php the_post_thumbnail( 'kraak-hero', array( 'loading' => 'eager', 'alt' => '' ) ); ?>
		</section>
	<?php endif; ?>

	<section class="section section--project-head">
		<div class="container">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'kraak' ); ?>">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'recherche' ) ); ?>"><span class="only-mobile">← </span><?php esc_html_e( 'Laboratoire', 'kraak' ); ?></a>
				<span>/</span>
				<span><?php echo esc_html( kraak_code() ); ?></span>
			</nav>
			<div class="split split--top">
				<h1 class="h-xl project-title"><?php the_title(); ?></h1>
				<dl class="facts">
					<div><dt><?php esc_html_e( 'Année', 'kraak' ); ?></dt><dd><?php echo esc_html( kraak_meta( $kid, 'kraak_annee', __( '[Année]', 'kraak' ) ) ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Type', 'kraak' ); ?></dt><dd><?php echo esc_html( $kterm ? $kterm->name : '—' ); ?></dd></div>
					<div><dt><?php esc_html_e( 'Référence', 'kraak' ); ?></dt><dd><?php echo esc_html( kraak_code() ); ?></dd></div>
				</dl>
			</div>
			<div class="split split--top intention">
				<?php kraak_label( '01', __( 'Recherche', 'kraak' ) ); ?>
				<div>
					<?php if ( has_excerpt() ) : ?><p class="lead-xl"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
					<div class="prose text-muted"><?php the_content(); ?></div>
				</div>
			</div>
		</div>
	</section>

	<?php $kgallery = kraak_gallery( $kid ); ?>
	<?php if ( $kgallery ) : ?>
		<section class="project-gallery">
			<div class="container gallery-grid">
				<?php foreach ( $kgallery as $gi => $gid ) : ?>
					<figure class="gallery-grid__item<?php echo 2 === $gi % 3 ? ' is-wide' : ''; ?>">
						<?php echo wp_get_attachment_image( $gid, 'large', false, array( 'loading' => 'lazy' ) ); ?>
						<?php if ( wp_get_attachment_caption( $gid ) ) : ?><figcaption><?php echo esc_html( wp_get_attachment_caption( $gid ) ); ?></figcaption><?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="section">
		<div class="container">
			<a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'recherche' ) ); ?>"><?php echo kraak_arrow( 'left' ); // phpcs:ignore ?> <?php esc_html_e( 'Retour au laboratoire', 'kraak' ); ?></a>
		</div>
	</section>
	<?php
endwhile;
get_footer();
