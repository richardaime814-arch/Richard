<?php
/**
 * Import du contenu de démonstration (reprend la maquette).
 *
 * Apparence › Contenu de démo KRAAK › « Importer ». Crée les pages, menus, projets,
 * recherches et distinctions, avec les images de la maquette dans la médiathèque.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
	add_theme_page( __( 'Contenu de démo KRAAK', 'kraak' ), __( 'Contenu de démo KRAAK', 'kraak' ), 'manage_options', 'kraak-demo', 'kraak_demo_page' );
} );

/**
 * Page d'administration de l'import.
 */
function kraak_demo_page() {
	$done = get_option( 'kraak_demo_imported' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Contenu de démonstration KRAAK Associates', 'kraak' ); ?></h1>
		<p><?php esc_html_e( 'Crée les pages (Accueil, Studio, Contact, Mentions légales), le menu, 9 projets, 5 recherches et les distinctions de la maquette, avec leurs images. Les textes entre [crochets] sont à remplacer.', 'kraak' ); ?></p>
		<?php if ( isset( $_GET['imported'] ) ) : // phpcs:ignore ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Import terminé.', 'kraak' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Voir le site', 'kraak' ); ?></a></p></div>
		<?php elseif ( $done ) : ?>
			<div class="notice notice-info"><p><?php esc_html_e( 'Le contenu de démo a déjà été importé.', 'kraak' ); ?></p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="kraak_demo_import">
			<?php wp_nonce_field( 'kraak_demo_import' ); ?>
			<?php submit_button( $done ? __( 'Réimporter (ajoute des doublons)', 'kraak' ) : __( 'Importer le contenu de démo', 'kraak' ), $done ? 'secondary' : 'primary' ); ?>
		</form>
	</div>
	<?php
}

add_action( 'admin_post_kraak_demo_import', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Accès refusé.', 'kraak' ) );
	}
	check_admin_referer( 'kraak_demo_import' );
	kraak_demo_import();
	wp_safe_redirect( admin_url( 'themes.php?page=kraak-demo&imported=1' ) );
	exit;
} );

/**
 * Copie une image de démo dans la médiathèque (une seule fois).
 *
 * @param string $file Nom sans extension.
 * @return int ID.
 */
function kraak_demo_attachment( $file ) {
	static $cache = array();
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}
	$existing = get_posts( array(
		'post_type'   => 'attachment',
		'meta_key'    => '_kraak_demo', // phpcs:ignore
		'meta_value'  => $file, // phpcs:ignore
		'numberposts' => 1,
		'fields'      => 'ids',
	) );
	if ( $existing ) {
		return $cache[ $file ] = (int) $existing[0];
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$src    = KRAAK_DIR . '/assets/demo/' . $file . '.jpg';
	$upload = wp_upload_bits( 'kraak-' . $file . '.jpg', null, file_get_contents( $src ) ); // phpcs:ignore
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => ucfirst( str_replace( '-', ' ', $file ) ),
		'post_status'    => 'inherit',
	), $upload['file'] );
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_kraak_demo', $file );
	return $cache[ $file ] = (int) $id;
}

/**
 * Lance l'import.
 */
function kraak_demo_import() {
	@set_time_limit( 300 ); // phpcs:ignore
	kraak_register_content_types();
	foreach ( kraak_default_terms() as $tax => $terms ) {
		foreach ( $terms as $slug => $name ) {
			if ( ! term_exists( $slug, $tax ) ) {
				wp_insert_term( $name, $tax, array( 'slug' => $slug ) );
			}
		}
	}

	/* ---- Pages ---- */
	$pages = array(
		'accueil'          => array( 'Accueil', '' ),
		'studio'           => array( 'Studio', '' ),
		'contact'          => array( 'Contact', '' ),
		'mentions-legales' => array( 'Mentions légales', "<h2>Éditeur du site</h2>\n<p>KRAAK Associates — [forme juridique, adresse, numéro d’immatriculation].</p>\n<h2>Directeur de la publication</h2>\n<p>Fernand Kiendrebeogo.</p>\n<h2>Hébergement</h2>\n<p>[Nom et adresse de l’hébergeur].</p>\n<h2>Propriété intellectuelle</h2>\n<p>L’ensemble des contenus (textes, images, plans, diagrammes) est la propriété de KRAAK Associates, sauf mention contraire.</p>\n<h2>Données personnelles</h2>\n<p>Les informations transmises via le formulaire de contact sont utilisées uniquement pour répondre à votre demande.</p>" ),
	);
	$ids = array();
	foreach ( $pages as $slug => $p ) {
		$page = get_page_by_path( $slug );
		$ids[ $slug ] = $page ? $page->ID : wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_name'    => $slug,
			'post_content' => $p[1],
		) );
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['accueil'] );
	update_option( 'wp_page_for_privacy_policy', $ids['mentions-legales'] );

	/* ---- Projets ---- */
	$intro = 'Le projet interroge la relation entre l’édifice et son territoire : comment une architecture précise et minimale peut-elle accueillir des usages incertains et évolutifs ?';
	$dev   = "<p>[Développement — site et territoire, programme, choix constructifs et matériaux, rapport au climat et aux usages. Environ 150 à 250 mots.]</p>\n<p>Le projet s’inscrit dans une recherche sur les architectures capables de répondre aux conditions contemporaines d’incertitude et de transformation.</p>";
	$projects = array(
		array( 'Projet lauréat ATX Design 2025', 'concours', 'hero-atx', '2025', 'Lauréat', 'ATX Design 2025', true ),
		array( '[Titre du projet] — Patio', 'architecture', 'patio', '', '', '', true ),
		array( '[Titre du projet] — Territoire', 'recherche', 'territoire', '', '', '', true ),
		array( '[Titre du projet] — Plis', 'experimental', 'escalier-blanc', '', '', '', true ),
		array( '[Titre du projet] — Ombre', 'architecture', 'ombre-facade', '', '', '', true ),
		array( '[Titre du projet] — Gradins', 'concours', 'escalier-beton', '', '', '', false ),
		array( '[Titre du projet] — Brique', 'architecture', 'mur-brique', '', '', '', false ),
		array( '[Titre du projet] — Passage', 'experimental', 'escalier-graphique', '', '', '', false ),
		array( '[Titre du projet] — Trame', 'recherche', 'structure-nb', '', '', '', false ),
	);
	$project_ids = array();
	foreach ( $projects as $i => $p ) {
		$n  = $i + 1;
		$id = wp_insert_post( array(
			'post_type'    => 'projet',
			'post_status'  => 'publish',
			'post_title'   => $p[0],
			'post_content' => $dev,
			'menu_order'   => $n,
		) );
		$project_ids[] = $id;
		wp_set_object_terms( $id, $p[1], 'type_projet' );
		set_post_thumbnail( $id, kraak_demo_attachment( $p[2] ) );
		$meta = array(
			'kraak_code'      => sprintf( 'P–%02d', $n ),
			'kraak_annee'     => $p[3],
			'kraak_statut'    => $p[4],
			'kraak_distinction' => $p[5],
			'kraak_equipe'    => 'KRAAK Associates',
			'kraak_intention' => $intro,
			'kraak_featured'  => $p[6] ? '1' : '',
			'kraak_gallery'   => implode( ',', array( kraak_demo_attachment( 'gradins' ), kraak_demo_attachment( 'maquette' ), kraak_demo_attachment( 'couloir-japon' ) ) ),
			'kraak_plans'      => kraak_demo_attachment( 'dessin-plan' ),
			'kraak_coupes'     => kraak_demo_attachment( 'dessin-coupe' ),
			'kraak_schemas'    => kraak_demo_attachment( 'dessin-schema' ),
			'kraak_diagrammes' => kraak_demo_attachment( 'dessin-diagramme' ),
		);
		foreach ( array_filter( $meta ) as $k => $v ) {
			update_post_meta( $id, $k, $v );
		}
	}

	/* ---- Recherches ---- */
	$research = array(
		array( '[Titre de la recherche] — Seuils', 'concept', '2025', 'installation-lumiere', true ),
		array( '[Titre de la recherche] — Trames', 'etude-parametrique', '2025', 'table-travail', false ),
		array( '[Titre de la recherche] — Lumière', 'installation', '', 'charpente-bois', false ),
		array( '[Titre de la recherche] — Matière', 'experimentation', '', 'village', false ),
		array( '[Titre de la recherche] — Flux', 'diagramme', '', 'atelier-plans', false ),
	);
	foreach ( $research as $i => $r ) {
		$id = wp_insert_post( array(
			'post_type'    => 'recherche',
			'post_status'  => 'publish',
			'post_title'   => $r[0],
			'post_excerpt' => '[Résumé de la recherche : question posée, méthode, outils (paramétrique, maquette, diagramme) et résultats.]',
			'post_content' => "<p>[Présentation de la recherche : contexte, question posée, méthode et outils, résultats et perspectives.]</p>",
			'menu_order'   => $i + 1,
		) );
		wp_set_object_terms( $id, $r[1], 'type_recherche' );
		set_post_thumbnail( $id, kraak_demo_attachment( $r[3] ) );
		update_post_meta( $id, 'kraak_code', sprintf( 'R–%02d', $i + 1 ) );
		if ( $r[2] ) {
			update_post_meta( $id, 'kraak_annee', $r[2] );
		}
		if ( $r[4] ) {
			update_post_meta( $id, 'kraak_featured', '1' );
		}
	}

	/* ---- Distinctions ---- */
	$dist = array(
		array( 'Lauréat du concours international ATX Design', 'prix', '2025', 'International', 'Prix d’architecture, concours international.', true ),
		array( 'Sakura Science Laureate', 'prix', '2025', 'Japon', 'Programme d’échange scientifique, Japon.', false ),
		array( 'Premier au concours d’entrée de l’EAMAU', 'prix', '2019', 'Lomé', 'École africaine des métiers de l’architecture et de l’urbanisme.', false ),
		array( '[Nom du concours — projet présenté]', 'concours', '[Année]', '[Résultat]', '', false ),
		array( '[Nom du concours — projet présenté]', 'concours', '[Année]', '[Résultat]', '', false ),
		array( '[Titre de l’exposition — lieu]', 'expositions', '[Année]', '[Ville]', '', false ),
		array( '[Titre — revue ou ouvrage]', 'publications', '[Année]', '[Éditeur]', '', false ),
		array( '[Titre — institution]', 'conferences', '[Année]', '[Ville]', '', false ),
	);
	foreach ( $dist as $i => $d ) {
		$id = wp_insert_post( array(
			'post_type'    => 'distinction',
			'post_status'  => 'publish',
			'post_title'   => $d[0],
			'post_content' => $d[4],
			'menu_order'   => $i + 1,
		) );
		wp_set_object_terms( $id, $d[1], 'categorie_distinction' );
		update_post_meta( $id, 'kraak_annee', $d[2] );
		update_post_meta( $id, 'kraak_mention', $d[3] );
		if ( $d[5] ) {
			update_post_meta( $id, 'kraak_featured', '1' );
			update_post_meta( $id, 'kraak_projet', $project_ids[0] );
			wp_update_post( array( 'ID' => $id, 'post_content' => '[Description du concours, du sujet et du projet lauréat. Lien vers la page du concours.]' ) );
			set_post_thumbnail( $id, kraak_demo_attachment( 'hero-atx' ) );
		}
	}

	/* ---- Images des options ---- */
	set_theme_mod( 'kraak_lab_image', kraak_demo_attachment( 'atelier-plans' ) );
	set_theme_mod( 'kraak_lab_img_1', kraak_demo_attachment( 'village' ) );
	set_theme_mod( 'kraak_lab_img_2', kraak_demo_attachment( 'table-travail' ) );
	set_theme_mod( 'kraak_lab_img_3', kraak_demo_attachment( 'charpente-bois' ) );
	set_theme_mod( 'kraak_founder_photo', kraak_demo_attachment( 'portrait-fondateur' ) );

	/* ---- Menus ---- */
	$menu_id = wp_create_nav_menu( 'Menu principal ' . wp_rand( 100, 999 ) );
	if ( ! is_wp_error( $menu_id ) ) {
		// Liens relatifs aux contenus (et non des URL fixes) : ils suivent un changement de domaine.
		$entries = array(
			array( 'Projets', 'post_type_archive', 'projet', 0 ),
			array( 'Laboratoire', 'post_type_archive', 'recherche', 0 ),
			array( 'Studio', 'post_type', 'page', $ids['studio'] ),
			array( 'Distinctions', 'post_type_archive', 'distinction', 0 ),
			array( 'Contact', 'post_type', 'page', $ids['contact'] ),
		);
		foreach ( $entries as $pos => $e ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $e[0],
				'menu-item-type'      => $e[1],
				'menu-item-object'    => $e[2],
				'menu-item-object-id' => $e[3],
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $pos + 1,
			) );
		}
		set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id, 'footer' => $menu_id ) );
	}

	update_option( 'blogname', 'KRAAK Associates' );
	update_option( 'blogdescription', 'Bureau d’architecture et de recherche spatiale' );
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules();
	update_option( 'kraak_demo_imported', time() );
}
