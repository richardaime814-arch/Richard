<?php
/**
 * Requêtes des archives : tri, pagination, filtres ?type=.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}

	$type = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( $q->is_post_type_archive( 'projet' ) ) {
		$q->set( 'posts_per_page', 9 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		if ( $type ) {
			$q->set( 'tax_query', array( array( 'taxonomy' => 'type_projet', 'field' => 'slug', 'terms' => $type ) ) );
		}
	}

	if ( $q->is_post_type_archive( 'recherche' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
		if ( $type ) {
			$q->set( 'tax_query', array( array( 'taxonomy' => 'type_recherche', 'field' => 'slug', 'terms' => $type ) ) );
		}
	}

	if ( $q->is_post_type_archive( 'distinction' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
} );

/**
 * Les pages de taxonomie renvoient vers l'archive filtrée (une seule mise en page).
 */
add_action( 'template_redirect', function () {
	if ( is_tax( 'type_projet' ) ) {
		wp_safe_redirect( add_query_arg( 'type', get_queried_object()->slug, get_post_type_archive_link( 'projet' ) ), 301 );
		exit;
	}
	if ( is_tax( 'type_recherche' ) ) {
		wp_safe_redirect( add_query_arg( 'type', get_queried_object()->slug, get_post_type_archive_link( 'recherche' ) ), 301 );
		exit;
	}
	// Une distinction n'a pas de page propre : on renvoie vers son lien, son projet ou la liste.
	if ( is_singular( 'distinction' ) ) {
		$id  = get_queried_object_id();
		$url = get_post_meta( $id, 'kraak_projet', true ) ? get_permalink( (int) get_post_meta( $id, 'kraak_projet', true ) ) : get_post_type_archive_link( 'distinction' );
		wp_safe_redirect( $url, 302 );
		exit;
	}
} );

/**
 * Projets pour l'accueil.
 *
 * @param bool $featured Seulement « à la une ».
 * @param int  $count    Nombre.
 * @return WP_Post[]
 */
function kraak_get_projects( $featured = false, $count = 5 ) {
	$args = array(
		'post_type'      => 'projet',
		'posts_per_page' => $count,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	);
	if ( $featured ) {
		$args['meta_key']   = 'kraak_featured'; // phpcs:ignore WordPress.DB.SlowDBQuery
		$args['meta_value'] = '1'; // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	$posts = get_posts( $args );
	if ( $featured && ! $posts ) {
		return kraak_get_projects( false, $count );
	}
	return $posts;
}
