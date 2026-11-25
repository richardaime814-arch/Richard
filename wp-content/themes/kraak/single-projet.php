<?php
/**
 * Fiche projet : image plein écran, fiche technique, intention, galerie,
 * plans / coupes / schémas / diagrammes, projet suivant.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$kid   = get_the_ID();
	$kterm = kraak_term( $kid, 'type_projet' );
	$kfacts = array(
		__( 'Année', 'kraak' )       => kraak_meta( $kid, 'kraak_annee', __( '[Année]', 'kraak' ) ),
		__( 'Lieu', 'kraak' )        => kraak_meta( $kid, 'kraak_lieu', __( '[Ville, pays]', 'kraak' ) ),
		__( 'Type', 'kraak' )        => $kterm ? $kterm->name : '—',
		__( 'Programme', 'kraak' )   => kraak_meta( $kid, 'kraak_programme', __( '[Programme]', 'kraak' ) ),
		__( 'Surface', 'kraak' )     => kraak_meta( $kid, 'kraak_surface', __( '[— m²]', 'kraak' ) ),
		__( 'Statut', 'kraak' )      => kraak_meta( $kid, 'kraak_statut', __( '[Statut]', 'kraak' ) ),
		__( 'Équipe', 'kraak' )      => kraak_meta( $kid, 'kraak_equipe', 'KRAAK Associates' ),
		__( 'Distinction', 'kraak' ) => kraak_meta( $kid, 'kraak_distinction', '' ),
	);
	$kfacts = array_filter( $kfacts );

	$kgallery  = kraak_gallery( $kid );
	$kdrawings = array();
	foreach ( array(
		'kraak_plans'      => __( 'Plans', 'kraak' ),
		'kraak_coupes'     => __( 'Coupes', 'kraak' ),
		'kraak_schemas'    => __( 'Schémas', 'kraak' ),
		'kraak_diagrammes' => __( 'Diagrammes', 'kraak' ),
	) as $kkey => $klabel ) {
		$kids = kraak_gallery( $kid, $kkey );
		if ( $kids ) {
			$kdrawings[ $kkey ] = array( 'label' => $klabel, 'ids' => $kids );
		}
	}
	?>

	<section class="project-hero">
		<?php the_post_thumbnail( 'kraak-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => '' ) ); ?>
	</section>

	<section class="section section--project-head">
		<div class="container">
			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d’Ariane', 'kraak' ); ?>">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>"><span class="only-mobile">← </span><?php esc_html_e( 'Projets', 'kraak' ); ?></a>
				<span>/</span>
				<span><?php echo esc_html( kraak_code() ); ?></span>
			</nav>
			<div class="split split--top">
				<h1 class="h-xl project-title"><?php the_title(); ?></h1>
				<dl class="facts">
					<?php foreach ( $kfacts as $klabel => $kval ) : ?>
						<div><dt><?php echo esc_html( $klabel ); ?></dt><dd><?php echo esc_html( $kval ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
			</div>

			<div class="split split--top intention">
				<?php kraak_label( '01', __( 'Intention', 'kraak' ) ); ?>
				<div>
					<p class="lead-xl"><?php echo esc_html( kraak_meta( $kid, 'kraak_intention', __( '[Texte explicatif — l’idée directrice du projet en deux ou trois phrases : le contexte, la question posée et la réponse spatiale.]', 'kraak' ) ) ); ?></p>
					<div class="prose text-muted"><?php the_content(); ?></div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $kgallery ) : ?>
		<section class="project-gallery">
			<div class="container gallery-grid">
				<?php foreach ( $kgallery as $gi => $gid ) : ?>
					<?php $kwide = 2 === $gi % 3; ?>
					<figure class="gallery-grid__item<?php echo $kwide ? ' is-wide' : ''; ?>">
						<?php echo wp_get_attachment_image( $gid, $kwide ? 'kraak-large' : 'large', false, array( 'loading' => 'lazy' ) ); ?>
						<?php if ( wp_get_attachment_caption( $gid ) ) : ?><figcaption><?php echo esc_html( wp_get_attachment_caption( $gid ) ); ?></figcaption><?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $kdrawings ) : ?>
		<section class="section section--grey drawings" data-drawings>
			<div class="container">
				<div class="drawings__head">
					<?php kraak_label( '02', __( 'Plans, coupes et schémas', 'kraak' ) ); ?>
					<div class="tabs" role="tablist">
						<?php $kfirst = true; foreach ( $kdrawings as $kkey => $d ) : ?>
							<button type="button" role="tab" data-tab="<?php echo esc_attr( $kkey ); ?>" aria-selected="<?php echo $kfirst ? 'true' : 'false'; ?>" class="<?php echo $kfirst ? 'is-active' : ''; ?>"><?php echo esc_html( $d['label'] ); ?></button>
						<?php $kfirst = false; endforeach; ?>
					</div>
				</div>

				<div class="drawings__stage">
					<?php $kn = 0; foreach ( $kdrawings as $kkey => $d ) : foreach ( $d['ids'] as $did ) : ?>
						<figure class="drawings__frame<?php echo 0 === $kn ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr( $kkey ); ?>" data-idx="<?php echo esc_attr( $kn ); ?>">
							<button type="button" class="drawings__zoom" data-zoom="<?php echo esc_url( wp_get_attachment_image_url( $did, 'full' ) ); ?>" aria-label="<?php esc_attr_e( 'Agrandir', 'kraak' ); ?>">
								<?php echo wp_get_attachment_image( $did, 'kraak-large', false, array( 'loading' => 'lazy' ) ); ?>
							</button>
							<?php if ( wp_get_attachment_caption( $did ) ) : ?><figcaption class="mono-label"><?php echo esc_html( wp_get_attachment_caption( $did ) ); ?></figcaption><?php endif; ?>
						</figure>
					<?php $kn++; endforeach; endforeach; ?>
				</div>
				<p class="mono-label only-mobile drawings__hint"><?php esc_html_e( 'Toucher pour agrandir · pincer pour zoomer', 'kraak' ); ?></p>

				<?php if ( $kn > 1 ) : ?>
					<div class="drawings__thumbs hide-mobile">
						<?php $kn = 0; foreach ( $kdrawings as $kkey => $d ) : foreach ( $d['ids'] as $did ) : ?>
							<button type="button" class="drawings__thumb<?php echo 0 === $kn ? ' is-hidden' : ''; ?>" data-show="<?php echo esc_attr( $kn ); ?>" data-cat="<?php echo esc_attr( $kkey ); ?>">
								<?php echo wp_get_attachment_image( $did, 'kraak-card', false, array( 'loading' => 'lazy' ) ); ?>
							</button>
						<?php $kn++; endforeach; endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<dialog class="lightbox" data-lightbox>
			<button type="button" class="lightbox__close" data-close><?php esc_html_e( 'Fermer', 'kraak' ); ?> <?php echo kraak_square(); // phpcs:ignore ?></button>
			<img src="" alt="">
		</dialog>
	<?php endif; ?>

	<?php
	// Projet suivant (ordre de la page Projets, en boucle).
	$kall  = get_posts( array( 'post_type' => 'projet', 'posts_per_page' => -1, 'fields' => 'ids', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	$kpos  = array_search( $kid, $kall, true );
	$knext = count( $kall ) > 1 ? $kall[ ( $kpos + 1 ) % count( $kall ) ] : 0;
	if ( $knext ) :
		?>
		<section class="section next-project">
			<div class="container">
				<a class="next-project__link" href="<?php echo esc_url( get_permalink( $knext ) ); ?>">
					<div>
						<?php kraak_label( __( 'Suivant', 'kraak' ), kraak_code( $knext ) ); ?>
						<p class="h-xl"><?php echo esc_html( get_the_title( $knext ) ); ?> <span aria-hidden="true">→</span></p>
					</div>
					<figure class="hide-mobile"><?php echo get_the_post_thumbnail( $knext, 'kraak-card', array( 'loading' => 'lazy', 'alt' => '' ) ); ?></figure>
				</a>
			</div>
		</section>
	<?php endif; ?>

	<?php
endwhile;
get_footer();
