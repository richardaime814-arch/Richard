<?php
/**
 * Page introuvable.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="section page-head">
	<div class="container">
		<?php kraak_label( '404', __( 'Page introuvable', 'kraak' ) ); ?>
		<h1 class="h-hero"><?php esc_html_e( 'Cet espace n’existe pas.', 'kraak' ); ?></h1>
		<p class="lead text-muted"><?php esc_html_e( 'La page demandée a peut-être été déplacée.', 'kraak' ); ?></p>
		<div class="btn-row">
			<a class="btn btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l’accueil', 'kraak' ); ?> <?php echo kraak_arrow(); // phpcs:ignore ?></a>
			<a class="btn btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'projet' ) ); ?>"><?php esc_html_e( 'Voir les projets', 'kraak' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
