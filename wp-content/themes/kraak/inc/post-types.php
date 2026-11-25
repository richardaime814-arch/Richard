<?php
/**
 * Types de contenus : Projets, Recherches (Laboratoire), Distinctions.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'kraak_register_content_types' );

/**
 * Enregistre les types de contenus et taxonomies.
 */
function kraak_register_content_types() {

	/* ---------- Projets ---------- */
	register_post_type( 'projet', array(
		'labels'        => array(
			'name'          => __( 'Projets', 'kraak' ),
			'singular_name' => __( 'Projet', 'kraak' ),
			'add_new'       => __( 'Ajouter un projet', 'kraak' ),
			'add_new_item'  => __( 'Ajouter un projet', 'kraak' ),
			'edit_item'     => __( 'Modifier le projet', 'kraak' ),
			'all_items'     => __( 'Tous les projets', 'kraak' ),
		),
		'public'        => true,
		'has_archive'   => 'projets',
		'rewrite'       => array( 'slug' => 'projets', 'with_front' => false ),
		'menu_icon'     => 'dashicons-building',
		'menu_position' => 5,
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
	) );

	register_taxonomy( 'type_projet', 'projet', array(
		'labels'            => array(
			'name'          => __( 'Types de projet', 'kraak' ),
			'singular_name' => __( 'Type de projet', 'kraak' ),
		),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'projets/type', 'with_front' => false ),
	) );

	/* ---------- Recherches / Laboratoire ---------- */
	register_post_type( 'recherche', array(
		'labels'        => array(
			'name'          => __( 'Laboratoire', 'kraak' ),
			'singular_name' => __( 'Recherche', 'kraak' ),
			'add_new'       => __( 'Ajouter une recherche', 'kraak' ),
			'add_new_item'  => __( 'Ajouter une recherche', 'kraak' ),
			'edit_item'     => __( 'Modifier la recherche', 'kraak' ),
			'all_items'     => __( 'Toutes les recherches', 'kraak' ),
		),
		'public'        => true,
		'has_archive'   => 'laboratoire',
		'rewrite'       => array( 'slug' => 'laboratoire', 'with_front' => false ),
		'menu_icon'     => 'dashicons-lightbulb',
		'menu_position' => 6,
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
	) );

	register_taxonomy( 'type_recherche', 'recherche', array(
		'labels'            => array(
			'name'          => __( 'Types de recherche', 'kraak' ),
			'singular_name' => __( 'Type de recherche', 'kraak' ),
		),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'laboratoire/type', 'with_front' => false ),
	) );

	/* ---------- Distinctions / Publications ---------- */
	register_post_type( 'distinction', array(
		'labels'        => array(
			'name'          => __( 'Distinctions', 'kraak' ),
			'singular_name' => __( 'Distinction', 'kraak' ),
			'add_new'       => __( 'Ajouter une distinction', 'kraak' ),
			'add_new_item'  => __( 'Ajouter une distinction', 'kraak' ),
			'edit_item'     => __( 'Modifier la distinction', 'kraak' ),
			'all_items'     => __( 'Toutes les distinctions', 'kraak' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'has_archive'        => 'distinctions',
		'rewrite'            => array( 'slug' => 'distinctions', 'with_front' => false ),
		'menu_icon'          => 'dashicons-awards',
		'menu_position'      => 7,
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
	) );

	register_taxonomy( 'categorie_distinction', 'distinction', array(
		'labels'            => array(
			'name'          => __( 'Catégories', 'kraak' ),
			'singular_name' => __( 'Catégorie', 'kraak' ),
		),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'public'            => false,
		'show_ui'           => true,
	) );
}

/**
 * Termes par défaut (créés à l'activation du thème).
 */
function kraak_default_terms() {
	return array(
		'type_projet'           => array(
			'architecture' => __( 'Architecture', 'kraak' ),
			'recherche'    => __( 'Recherche', 'kraak' ),
			'concours'     => __( 'Concours', 'kraak' ),
			'experimental' => __( 'Expérimental', 'kraak' ),
		),
		'type_recherche'        => array(
			'concept'            => __( 'Concept', 'kraak' ),
			'experimentation'    => __( 'Expérimentation', 'kraak' ),
			'diagramme'          => __( 'Diagramme', 'kraak' ),
			'etude-parametrique' => __( 'Étude paramétrique', 'kraak' ),
			'installation'       => __( 'Installation', 'kraak' ),
		),
		'categorie_distinction' => array(
			'prix'         => __( 'Prix', 'kraak' ),
			'concours'     => __( 'Concours', 'kraak' ),
			'expositions'  => __( 'Expositions', 'kraak' ),
			'publications' => __( 'Publications', 'kraak' ),
			'conferences'  => __( 'Conférences', 'kraak' ),
		),
	);
}

add_action( 'after_switch_theme', function () {
	kraak_register_content_types();
	foreach ( kraak_default_terms() as $tax => $terms ) {
		$order = 0;
		foreach ( $terms as $slug => $name ) {
			$order++;
			if ( ! term_exists( $slug, $tax ) ) {
				$t = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );
				if ( ! is_wp_error( $t ) ) {
					update_term_meta( $t['term_id'], 'kraak_order', $order );
				}
			}
		}
	}
	flush_rewrite_rules();
} );
