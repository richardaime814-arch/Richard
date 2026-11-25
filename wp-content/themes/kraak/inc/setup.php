<?php
/**
 * Configuration du thème : supports, menus, tailles d'images, scripts.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'kraak', KRAAK_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => __( 'Menu principal', 'kraak' ),
		'footer'  => __( 'Navigation pied de page', 'kraak' ),
	) );

	// Tailles d'images : grandes et immersives (cahier des charges §6).
	add_image_size( 'kraak-hero', 2400, 1350, true );
	add_image_size( 'kraak-card', 1200, 900, true );
	add_image_size( 'kraak-tall', 900, 1200, true );
	add_image_size( 'kraak-large', 2000, 0, false );
} );

/**
 * Feuilles de style et scripts.
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'kraak-main', KRAAK_URI . '/assets/css/main.css', array(), KRAAK_VERSION );
	wp_enqueue_script( 'kraak-main', KRAAK_URI . '/assets/js/main.js', array(), KRAAK_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script( 'kraak-main', 'KRAAK', array(
		'i18n' => array(
			'loading' => __( 'Chargement…', 'kraak' ),
			'more'    => __( 'Charger plus de projets', 'kraak' ),
		),
	) );
} );

/**
 * Précharge les polices principales.
 */
add_action( 'wp_head', function () {
	foreach ( array( 'poppins-latin-300-normal', 'poppins-latin-400-normal' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( KRAAK_URI . '/assets/fonts/' . $font . '.woff2' ) );
	}
}, 1 );

/**
 * Classes du <body> : thème d'en-tête (clair / sombre / transparent).
 */
add_filter( 'body_class', function ( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'header-overlay';
	} elseif ( is_post_type_archive( 'recherche' ) || is_tax( 'type_recherche' ) ) {
		$classes[] = 'header-dark';
	}
	return $classes;
} );

/**
 * Pas de taille de crop « scaled » qui dégrade les grandes images.
 */
add_filter( 'big_image_size_threshold', function () {
	return 2560;
} );

/**
 * Extrait plus court.
 */
add_filter( 'excerpt_length', function () {
	return 30;
} );
add_filter( 'excerpt_more', function () {
	return '…';
} );
