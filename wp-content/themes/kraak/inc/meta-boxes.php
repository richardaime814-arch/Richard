<?php
/**
 * Champs personnalisés (sans extension) pour Projets, Recherches et Distinctions.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

/**
 * Définition des champs par type de contenu.
 *
 * Types : text, textarea, url, checkbox, gallery, post (sélecteur de projet).
 */
function kraak_meta_fields() {
	return array(
		'projet'      => array(
			'kraak_details' => array(
				'title'  => __( 'Fiche du projet', 'kraak' ),
				'fields' => array(
					'kraak_code'       => array( 'label' => __( 'Référence', 'kraak' ), 'type' => 'text', 'help' => __( 'Ex. P–01. Laisser vide pour la numérotation automatique (ordre).', 'kraak' ) ),
					'kraak_annee'      => array( 'label' => __( 'Année', 'kraak' ), 'type' => 'text' ),
					'kraak_lieu'       => array( 'label' => __( 'Lieu (ville, pays)', 'kraak' ), 'type' => 'text' ),
					'kraak_programme'  => array( 'label' => __( 'Programme', 'kraak' ), 'type' => 'text' ),
					'kraak_surface'    => array( 'label' => __( 'Surface', 'kraak' ), 'type' => 'text', 'help' => __( 'Ex. 240 m²', 'kraak' ) ),
					'kraak_statut'     => array( 'label' => __( 'Statut', 'kraak' ), 'type' => 'text', 'help' => __( 'Ex. Lauréat, Livré, En cours…', 'kraak' ) ),
					'kraak_equipe'     => array( 'label' => __( 'Équipe', 'kraak' ), 'type' => 'text' ),
					'kraak_distinction' => array( 'label' => __( 'Distinction', 'kraak' ), 'type' => 'text' ),
					'kraak_intention'  => array( 'label' => __( 'Intention (texte d’accroche)', 'kraak' ), 'type' => 'textarea', 'help' => __( 'L’idée directrice en deux ou trois phrases. Le développement se rédige dans l’éditeur principal.', 'kraak' ) ),
					'kraak_featured'   => array( 'label' => __( 'À la une (diaporama de l’accueil)', 'kraak' ), 'type' => 'checkbox' ),
				),
			),
			'kraak_medias'  => array(
				'title'  => __( 'Images, plans, coupes et schémas', 'kraak' ),
				'fields' => array(
					'kraak_gallery'    => array( 'label' => __( 'Galerie d’images (rendus, photos, maquettes)', 'kraak' ), 'type' => 'gallery', 'help' => __( 'Les deux premières s’affichent côte à côte, la troisième en pleine largeur, etc.', 'kraak' ) ),
					'kraak_plans'      => array( 'label' => __( 'Plans', 'kraak' ), 'type' => 'gallery' ),
					'kraak_coupes'     => array( 'label' => __( 'Coupes', 'kraak' ), 'type' => 'gallery' ),
					'kraak_schemas'    => array( 'label' => __( 'Schémas conceptuels', 'kraak' ), 'type' => 'gallery' ),
					'kraak_diagrammes' => array( 'label' => __( 'Diagrammes', 'kraak' ), 'type' => 'gallery' ),
				),
			),
		),
		'recherche'   => array(
			'kraak_details' => array(
				'title'  => __( 'Fiche de la recherche', 'kraak' ),
				'fields' => array(
					'kraak_code'     => array( 'label' => __( 'Référence', 'kraak' ), 'type' => 'text', 'help' => __( 'Ex. R–01. Vide = automatique.', 'kraak' ) ),
					'kraak_annee'    => array( 'label' => __( 'Année', 'kraak' ), 'type' => 'text' ),
					'kraak_featured' => array( 'label' => __( 'Recherche à la une (page Laboratoire)', 'kraak' ), 'type' => 'checkbox' ),
					'kraak_gallery'  => array( 'label' => __( 'Images, diagrammes, expérimentations', 'kraak' ), 'type' => 'gallery' ),
				),
			),
		),
		'distinction' => array(
			'kraak_details' => array(
				'title'  => __( 'Détails', 'kraak' ),
				'fields' => array(
					'kraak_annee'    => array( 'label' => __( 'Année', 'kraak' ), 'type' => 'text' ),
					'kraak_mention'  => array( 'label' => __( 'Mention à droite', 'kraak' ), 'type' => 'text', 'help' => __( 'Ex. International, Japon, ville, éditeur, résultat…', 'kraak' ) ),
					'kraak_link'     => array( 'label' => __( 'Lien externe', 'kraak' ), 'type' => 'url' ),
					'kraak_projet'   => array( 'label' => __( 'Projet lié', 'kraak' ), 'type' => 'post', 'post_type' => 'projet' ),
					'kraak_featured' => array( 'label' => __( 'Mettre en avant (haut de la page Distinctions)', 'kraak' ), 'type' => 'checkbox' ),
				),
			),
		),
	);
}

add_action( 'add_meta_boxes', function ( $post_type ) {
	$all = kraak_meta_fields();
	if ( empty( $all[ $post_type ] ) ) {
		return;
	}
	foreach ( $all[ $post_type ] as $id => $box ) {
		add_meta_box( $id, $box['title'], 'kraak_render_meta_box', $post_type, 'normal', 'high', array( 'fields' => $box['fields'] ) );
	}
} );

/**
 * Affiche une meta box.
 *
 * @param WP_Post $post Contenu.
 * @param array   $box  Arguments.
 */
function kraak_render_meta_box( $post, $box ) {
	wp_nonce_field( 'kraak_meta', 'kraak_meta_nonce' );
	echo '<div class="kraak-fields">';
	foreach ( $box['args']['fields'] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<div class="kraak-field kraak-field--' . esc_attr( $field['type'] ) . '">';
		if ( 'checkbox' === $field['type'] ) {
			printf(
				'<label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label>',
				esc_attr( $key ),
				checked( $value, '1', false ),
				esc_html( $field['label'] )
			);
		} else {
			printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $key ), esc_html( $field['label'] ) );
			switch ( $field['type'] ) {
				case 'textarea':
					printf( '<textarea id="%1$s" name="%1$s" rows="3" class="widefat">%2$s</textarea>', esc_attr( $key ), esc_textarea( $value ) );
					break;
				case 'gallery':
					$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
					printf( '<input type="hidden" id="%1$s" name="%1$s" value="%2$s" class="kraak-gallery-input">', esc_attr( $key ), esc_attr( implode( ',', $ids ) ) );
					echo '<ul class="kraak-gallery-preview">';
					foreach ( $ids as $id ) {
						echo '<li data-id="' . esc_attr( $id ) . '">' . wp_get_attachment_image( $id, 'thumbnail' ) . '</li>';
					}
					echo '</ul>';
					printf(
						'<p><button type="button" class="button kraak-gallery-pick" data-target="%1$s">%2$s</button> <button type="button" class="button-link kraak-gallery-clear" data-target="%1$s">%3$s</button></p>',
						esc_attr( $key ),
						esc_html__( 'Choisir / ordonner les images', 'kraak' ),
						esc_html__( 'Vider', 'kraak' )
					);
					break;
				case 'post':
					$items = get_posts( array( 'post_type' => $field['post_type'], 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
					printf( '<select id="%1$s" name="%1$s" class="widefat"><option value="">—</option>', esc_attr( $key ) );
					foreach ( $items as $item ) {
						printf( '<option value="%1$d" %2$s>%3$s</option>', (int) $item->ID, selected( (int) $value, $item->ID, false ), esc_html( get_the_title( $item ) ) );
					}
					echo '</select>';
					break;
				default:
					printf( '<input type="%3$s" id="%1$s" name="%1$s" value="%2$s" class="widefat">', esc_attr( $key ), esc_attr( $value ), 'url' === $field['type'] ? 'url' : 'text' );
			}
		}
		if ( ! empty( $field['help'] ) ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}
		echo '</div>';
	}
	echo '</div>';
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['kraak_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['kraak_meta_nonce'] ), 'kraak_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$all = kraak_meta_fields();
	if ( empty( $all[ $post->post_type ] ) ) {
		return;
	}
	foreach ( $all[ $post->post_type ] as $box ) {
		foreach ( $box['fields'] as $key => $field ) {
			$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			switch ( $field['type'] ) {
				case 'checkbox':
					$val = $raw ? '1' : '';
					break;
				case 'textarea':
					$val = sanitize_textarea_field( $raw );
					break;
				case 'url':
					$val = esc_url_raw( $raw );
					break;
				case 'gallery':
					$val = implode( ',', array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) );
					break;
				case 'post':
					$val = absint( $raw ) ?: '';
					break;
				default:
					$val = sanitize_text_field( $raw );
			}
			if ( '' === $val ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $val );
			}
		}
	}
}, 10, 2 );

/**
 * Médiathèque et script admin pour les champs galerie.
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'projet', 'recherche', 'distinction' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'kraak-admin', KRAAK_URI . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), KRAAK_VERSION, true );
	wp_add_inline_style( 'wp-admin', '
		.kraak-field{margin:0 0 16px}
		.kraak-field label strong{display:block;margin-bottom:4px}
		.kraak-gallery-preview{display:flex;flex-wrap:wrap;gap:6px;margin:6px 0}
		.kraak-gallery-preview li{margin:0;cursor:move}
		.kraak-gallery-preview img{width:72px;height:72px;object-fit:cover;display:block}
	' );
} );
