<?php
/**
 * En-tête du site.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;
$kraak_nav = kraak_nav_items();
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenu"><?php esc_html_e( 'Aller au contenu', 'kraak' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<?php kraak_logo(); ?>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'kraak' ); ?>">
			<ul>
				<?php foreach ( $kraak_nav as $item ) : ?>
					<li><a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $item['current'] ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html( $item['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<span class="primary-nav__sep" aria-hidden="true"></span>
			<?php kraak_lang_switcher(); ?>
		</nav>

		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-overlay">
			<span><?php esc_html_e( 'Menu', 'kraak' ); ?></span><?php echo kraak_square(); // phpcs:ignore ?>
		</button>
	</div>
</header>

<div class="menu-overlay" id="menu-overlay" hidden>
	<div class="menu-overlay__top container">
		<?php kraak_logo(); ?>
		<button class="menu-close" type="button"><span><?php esc_html_e( 'Fermer', 'kraak' ); ?></span><?php echo kraak_square(); // phpcs:ignore ?></button>
	</div>
	<nav class="container menu-overlay__nav" aria-label="<?php esc_attr_e( 'Navigation mobile', 'kraak' ); ?>">
		<ol>
			<?php foreach ( $kraak_nav as $i => $item ) : ?>
				<li><a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $item['current'] ? ' aria-current="page"' : ''; ?>><span><?php echo esc_html( $item['title'] ); ?></span><small><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></small></a></li>
			<?php endforeach; ?>
		</ol>
		<?php kraak_lang_switcher(); ?>
		<a class="menu-overlay__mail" href="mailto:<?php echo esc_attr( kraak_opt( 'email' ) ); ?>"><?php echo esc_html( kraak_opt( 'email' ) ); ?></a>
		<p class="menu-overlay__social">
			<?php if ( kraak_opt( 'instagram' ) ) : ?><a href="<?php echo esc_url( kraak_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a><?php endif; ?>
			<?php if ( kraak_opt( 'linkedin' ) ) : ?><a href="<?php echo esc_url( kraak_opt( 'linkedin' ) ); ?>" target="_blank" rel="noopener">LinkedIn <?php echo kraak_arrow( 'ne' ); // phpcs:ignore ?></a><?php endif; ?>
		</p>
	</nav>
</div>

<main id="contenu" class="site-main">
