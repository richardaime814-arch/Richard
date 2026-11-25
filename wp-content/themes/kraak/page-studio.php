<?php
/**
 * Page Studio (À propos) : manifeste, fondateur, parcours, philosophie, valeurs.
 *
 * Utilisée automatiquement pour la page dont l'identifiant (slug) est « studio ».
 * Les textes se modifient dans Apparence › Personnaliser › KRAAK Associates › Page Studio.
 * Le contenu saisi dans l'éditeur de la page (facultatif) s'affiche sous la biographie.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<section class="section page-head page-head--studio">
	<div class="container split split--center">
		<div>
			<?php kraak_label( 'S', __( 'À propos', 'kraak' ) ); ?>
			<h1 class="h-hero"><?php the_title(); ?></h1>
		</div>
		<p class="manifesto"><?php echo esc_html( kraak_opt( 'studio_intro' ) ); ?></p>
	</div>
</section>

<section class="section section--flush-top">
	<div class="container split split--top founder">
		<figure class="founder__portrait">
			<?php echo kraak_opt_image( 'founder_photo', 'portrait-fondateur', 'large', array( 'alt' => kraak_opt( 'founder_name' ) ) ); // phpcs:ignore ?>
			<figcaption class="mono-label"><span><?php echo esc_html( kraak_opt( 'founder_name' ) ); ?></span> <span><?php echo esc_html( kraak_opt( 'founder_role' ) ); ?></span></figcaption>
		</figure>
		<div>
			<?php kraak_label( '01', __( 'Le fondateur', 'kraak' ) ); ?>
			<h2 class="h-xl"><?php echo esc_html( kraak_opt( 'founder_name' ) ); ?></h2>
			<p class="text-muted"><?php echo nl2br( esc_html( kraak_opt( 'founder_bio' ) ) ); ?></p>
			<?php
			while ( have_posts() ) :
				the_post();
				if ( get_the_content() ) {
					echo '<div class="prose text-muted">';
					the_content();
					echo '</div>';
				}
			endwhile;
			?>

			<?php kraak_label( '02', __( 'Parcours', 'kraak' ) ); ?>
			<ul class="timeline">
				<?php foreach ( kraak_opt_lines( 'parcours', true ) as $step ) : ?>
					<li><span class="mono-label"><?php echo esc_html( $step[0] ); ?></span><span><?php echo esc_html( $step[1] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<section class="section section--dark philosophy">
	<div class="container">
		<?php kraak_label( '03', __( 'Philosophie', 'kraak' ) ); ?>
		<h2 class="h-xl philosophy__title"><?php echo esc_html( kraak_opt( 'philo_title' ) ); ?></h2>
		<ol class="philosophy__items">
			<?php foreach ( kraak_opt_lines( 'philo_items' ) as $i => $item ) : ?>
				<li><span class="mono-label"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><p><?php echo esc_html( $item ); ?></p></li>
			<?php endforeach; ?>
		</ol>
		<ul class="values">
			<?php foreach ( kraak_opt_lines( 'values' ) as $v ) : ?>
				<li class="mono-label"><?php echo esc_html( $v ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section join-band">
	<div class="container join-band__inner">
		<h2 class="h-lg"><?php echo esc_html( kraak_opt( 'join_title' ) ); ?></h2>
		<a class="btn btn--dark" href="<?php echo esc_url( kraak_contact_url() ); ?>"><?php esc_html_e( 'Nous contacter', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
	</div>
</section>

<?php
get_footer();
