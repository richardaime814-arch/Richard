<?php
/**
 * Page Contact : coordonnées, réseaux, carte et formulaire « Écrire au studio ».
 *
 * Utilisée automatiquement pour la page dont l'identifiant (slug) est « contact ».
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();
$kraak_notice = kraak_contact_notice();
?>

<section class="section page-head page-head--contact">
	<div class="container">
		<?php kraak_label( 'C', __( 'Contact', 'kraak' ) ); ?>
		<h1 class="h-hero h-hero--contact"><?php echo esc_html( kraak_opt( 'contact_title' ) ); ?></h1>
		<a class="contact-mail" href="mailto:<?php echo esc_attr( kraak_opt( 'email' ) ); ?>"><?php echo esc_html( kraak_opt( 'email' ) ); ?></a>
	</div>
</section>

<section class="section section--flush-top">
	<div class="container contact-grid">
		<div class="contact-info">
			<dl>
				<div>
					<dt class="mono-label"><?php esc_html_e( 'Localisation', 'kraak' ); ?></dt>
					<dd><?php echo esc_html( kraak_opt( 'address' ) ); ?><span class="hide-mobile"><br></span><span class="only-mobile"> · </span><span class="hide-mobile"><?php echo esc_html( kraak_opt( 'city' ) ); ?></span><span class="only-mobile"><?php echo esc_html( kraak_opt( 'city_short' ) ); ?></span></dd>
				</div>
				<div>
					<dt class="mono-label"><?php esc_html_e( 'Téléphone', 'kraak' ); ?></dt>
					<dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', kraak_opt( 'phone' ) ) ); ?>"><?php echo esc_html( kraak_opt( 'phone' ) ); ?></a></dd>
				</div>
				<div class="hide-mobile">
					<dt class="mono-label"><?php esc_html_e( 'Réseaux', 'kraak' ); ?></dt>
					<dd class="inline-links">
						<?php if ( kraak_opt( 'instagram' ) ) : ?><a href="<?php echo esc_url( kraak_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a><?php endif; ?>
						<?php if ( kraak_opt( 'linkedin' ) ) : ?><a href="<?php echo esc_url( kraak_opt( 'linkedin' ) ); ?>" target="_blank" rel="noopener">LinkedIn <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a><?php endif; ?>
					</dd>
				</div>
				<div class="hide-mobile">
					<dt class="mono-label"><?php esc_html_e( 'Rejoindre le studio', 'kraak' ); ?></dt>
					<dd class="text-muted text-small-plus"><?php echo esc_html( kraak_opt( 'contact_join' ) ); ?></dd>
				</div>
			</dl>
			<?php $kraak_map = kraak_opt( 'map_url' ); ?>
			<<?php echo $kraak_map ? 'a href="' . esc_url( $kraak_map ) . '" target="_blank" rel="noopener"' : 'div'; ?> class="map-card hide-mobile">
				<?php kraak_topo_map(); ?>
				<span class="mono-label"><?php esc_html_e( 'Carte · localisation du studio', 'kraak' ); ?></span>
			</<?php echo $kraak_map ? 'a' : 'div'; ?>>
		</div>

		<div class="form-card" id="formulaire">
			<h2 class="h-sm hide-mobile"><?php esc_html_e( 'Écrire au studio', 'kraak' ); ?></h2>
			<?php if ( $kraak_notice ) : ?>
				<p class="notice notice--<?php echo esc_attr( $kraak_notice[0] ); ?>" role="status"><?php echo esc_html( $kraak_notice[1] ); ?></p>
			<?php endif; ?>
			<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="kraak_contact">
				<?php wp_nonce_field( 'kraak_contact', 'kraak_nonce' ); ?>
				<p class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>

				<div class="field">
					<label for="objet" class="mono-label"><?php esc_html_e( 'Objet', 'kraak' ); ?></label>
					<select id="objet" name="objet">
						<?php foreach ( kraak_opt_lines( 'contact_subjects' ) as $s ) : ?>
							<option><?php echo esc_html( $s ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="field-row">
					<div class="field">
						<label for="nom" class="mono-label"><?php esc_html_e( 'Nom', 'kraak' ); ?></label>
						<input id="nom" name="nom" type="text" required autocomplete="name" placeholder="<?php esc_attr_e( 'Votre nom', 'kraak' ); ?>">
					</div>
					<div class="field hide-mobile-field">
						<label for="organisation" class="mono-label"><?php esc_html_e( 'Organisation', 'kraak' ); ?></label>
						<input id="organisation" name="organisation" type="text" autocomplete="organization" placeholder="<?php esc_attr_e( 'Facultatif', 'kraak' ); ?>">
					</div>
				</div>
				<div class="field">
					<label for="email" class="mono-label"><?php esc_html_e( 'E-mail', 'kraak' ); ?></label>
					<input id="email" name="email" type="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'vous@exemple.com', 'kraak' ); ?>">
				</div>
				<div class="field">
					<label for="message" class="mono-label"><?php esc_html_e( 'Message', 'kraak' ); ?></label>
					<textarea id="message" name="message" rows="6" required placeholder="<?php esc_attr_e( 'Décrivez votre projet, le lieu, le calendrier…', 'kraak' ); ?>"></textarea>
				</div>
				<button class="btn btn--dark btn--submit" type="submit"><?php esc_html_e( 'Envoyer le message', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></button>
			</form>
		</div>
	</div>
</section>

<?php
get_footer();
