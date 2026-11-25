<?php
/**
 * Carte projet (grilles de l'accueil et de la page Projets).
 *
 * Arguments : size (taille d'image), class (classe CSS).
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

$args  = wp_parse_args( $args ?? array(), array( 'size' => 'kraak-card', 'class' => '' ) );
$kterm = kraak_term( get_post(), 'type_projet' );
?>
<article class="card <?php echo esc_attr( $args['class'] ); ?>" data-type="<?php echo esc_attr( $kterm ? $kterm->slug : '' ); ?>">
	<a class="card__link" href="<?php the_permalink(); ?>">
		<figure class="card__media">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( $args['size'], array( 'loading' => 'lazy', 'alt' => '' ) );
			} else {
				echo '<span class="card__placeholder"></span>';
			}
			?>
		</figure>
		<div class="card__head">
			<h3 class="card__title"><?php the_title(); ?></h3>
			<span class="card__code"><?php echo esc_html( kraak_code() ); ?></span>
		</div>
		<?php kraak_project_meta_line(); ?>
	</a>
</article>
