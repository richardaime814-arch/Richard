<?php
/**
 * Pied de page.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
$kraak_legal = get_page_by_path( 'mentions-legales' );
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__grid">
			<div class="site-footer__brand">
				<?php kraak_logo(); ?>
				<p><?php echo esc_html( kraak_opt( 'tagline' ) ); ?></p>
			</div>

			<div class="site-footer__col site-footer__col--studio">
				<p class="mono-label"><?php esc_html_e( 'Studio', 'kraak' ); ?></p>
				<p><?php echo esc_html( kraak_opt( 'address' ) ); ?><br><?php echo esc_html( kraak_opt( 'city' ) ); ?></p>
			</div>

			<div class="site-footer__col">
				<p class="mono-label"><?php esc_html_e( 'Contact', 'kraak' ); ?></p>
				<ul>
					<li><a href="mailto:<?php echo esc_attr( kraak_opt( 'email' ) ); ?>"><?php echo esc_html( kraak_opt( 'email' ) ); ?></a></li>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', kraak_opt( 'phone' ) ) ); ?>"><?php echo esc_html( kraak_opt( 'phone' ) ); ?></a></li>
				</ul>
			</div>

			<div class="site-footer__col">
				<p class="mono-label"><?php esc_html_e( 'Suivre', 'kraak' ); ?></p>
				<ul>
					<?php if ( kraak_opt( 'instagram' ) ) : ?><li><a href="<?php echo esc_url( kraak_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a></li><?php endif; ?>
					<?php if ( kraak_opt( 'linkedin' ) ) : ?><li><a href="<?php echo esc_url( kraak_opt( 'linkedin' ) ); ?>" target="_blank" rel="noopener">LinkedIn <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a></li><?php endif; ?>
				</ul>
			</div>

			<nav class="site-footer__col site-footer__col--nav" aria-label="<?php esc_attr_e( 'Navigation pied de page', 'kraak' ); ?>">
				<p class="mono-label"><?php esc_html_e( 'Navigation', 'kraak' ); ?></p>
				<ul>
					<?php foreach ( kraak_nav_items() as $item ) : ?>
						<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>

		<div class="site-footer__bottom">
			<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?><span class="only-mobile"> · <?php echo esc_html( kraak_opt( 'city_short' ) ); ?></span></p>
			<p class="hide-mobile"><?php echo esc_html( kraak_opt( 'coords' ) ); ?></p>
			<?php if ( $kraak_legal ) : ?>
				<p class="hide-mobile"><a href="<?php echo esc_url( get_permalink( $kraak_legal ) ); ?>"><?php esc_html_e( 'Mentions légales', 'kraak' ); ?></a></p>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
