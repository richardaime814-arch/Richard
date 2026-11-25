<?php
/**
 * Options du thème (Apparence › Personnaliser › KRAAK Associates).
 *
 * Tous les textes fixes de la maquette sont modifiables ici, avec aperçu en direct.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

/**
 * Définition des options : section => [ clé => [ libellé, type, défaut ] ].
 * Types : text, textarea, lines (une entrée par ligne), url, email, image.
 */
function kraak_options_schema() {
	return array(
		'kraak_contact' => array(
			'title'  => __( 'Coordonnées et réseaux', 'kraak' ),
			'fields' => array(
				'email'     => array( __( 'E-mail', 'kraak' ), 'email', 'kiendfernando@gmail.com' ),
				'phone'     => array( __( 'Téléphone', 'kraak' ), 'text', '+226 67 90 30 94' ),
				'address'   => array( __( 'Adresse', 'kraak' ), 'text', '[Adresse du studio]' ),
				'city'      => array( __( 'Ville, pays', 'kraak' ), 'text', 'Ouagadougou, Burkina Faso' ),
				'city_short' => array( __( 'Ville (version courte)', 'kraak' ), 'text', 'Ouagadougou' ),
				'coords'    => array( __( 'Coordonnées GPS (pied de page)', 'kraak' ), 'text', '12.37° N · 1.52° W' ),
				'instagram' => array( __( 'Lien Instagram', 'kraak' ), 'url', 'https://www.instagram.com/' ),
				'linkedin'  => array( __( 'Lien LinkedIn', 'kraak' ), 'url', 'https://www.linkedin.com/' ),
				'tagline'   => array( __( 'Signature (pied de page)', 'kraak' ), 'text', 'Bureau d’architecture et de recherche spatiale.' ),
				'map_url'   => array( __( 'Lien carte (Google Maps, optionnel)', 'kraak' ), 'url', '' ),
			),
		),
		'kraak_home'    => array(
			'title'  => __( 'Page d’accueil', 'kraak' ),
			'fields' => array(
				'hero_title'    => array( __( 'Slogan (diaporama)', 'kraak' ), 'textarea', 'L’architecture comme outil d’exploration spatiale.' ),
				'studio_title'  => array( __( 'Le studio — titre', 'kraak' ), 'textarea', 'Un bureau d’architecture et de recherche entre espace, territoire et société.' ),
				'studio_text'   => array( __( 'Le studio — texte', 'kraak' ), 'textarea', 'KRAAK Associates développe une architecture expérimentale et contextuelle, nourrie par une expérience professionnelle au Japon : précision constructive, minimalisme et attention à la matière. Le studio s’intéresse aux situations complexes ou improbables, là où l’architecture devient un outil de recherche.' ),
				'lab_title'     => array( __( 'Laboratoire — titre', 'kraak' ), 'textarea', 'La recherche comme méthode de projet.' ),
				'lab_text'      => array( __( 'Laboratoire — texte', 'kraak' ), 'textarea', 'Concepts, expérimentations, diagrammes, études paramétriques et installations : le laboratoire documente les explorations qui nourrissent chaque projet.' ),
				'lab_lines'     => array( __( 'Laboratoire — thèmes listés (un par ligne)', 'kraak' ), 'lines', "Espace et incertitude\nTerritoire et matière\nArchitecture et société" ),
				'lab_image'     => array( __( 'Laboratoire — image', 'kraak' ), 'image', '' ),
				'cta_title'     => array( __( 'Appel à l’action — titre', 'kraak' ), 'textarea', 'Un projet, une collaboration, un concours ?' ),
			),
		),
		'kraak_pages'   => array(
			'title'  => __( 'Introductions des pages', 'kraak' ),
			'fields' => array(
				'projects_intro'     => array( __( 'Projets — introduction', 'kraak' ), 'textarea', 'Architecture, recherche, concours et projets expérimentaux. Chaque projet présente images, plans, schémas conceptuels et texte explicatif.' ),
				'lab_intro'          => array( __( 'Laboratoire — introduction', 'kraak' ), 'textarea', 'Le laboratoire affirme la dimension intellectuelle du studio : explorer les architectures capables de répondre aux conditions contemporaines d’incertitude et de transformation.' ),
				'lab_img_1'          => array( __( 'Laboratoire — image 1', 'kraak' ), 'image', '' ),
				'lab_img_2'          => array( __( 'Laboratoire — image 2', 'kraak' ), 'image', '' ),
				'lab_img_3'          => array( __( 'Laboratoire — image 3', 'kraak' ), 'image', '' ),
				'lab_axes'           => array( __( 'Axes de recherche (Titre | texte, un par ligne)', 'kraak' ), 'lines', "Espace | L’architecture comme outil d’exploration spatiale et de situations improbables.\nTerritoire | Le dialogue entre contextes locaux et influences internationales.\nSociété | Les usages, les communautés et les transformations qui fondent le projet." ),
				'distinctions_intro' => array( __( 'Distinctions — introduction', 'kraak' ), 'textarea', 'Prix, concours, expositions, publications et conférences.' ),
				'contact_title'      => array( __( 'Contact — titre', 'kraak' ), 'textarea', 'Parlons de votre projet.' ),
				'contact_join'       => array( __( 'Contact — rejoindre le studio', 'kraak' ), 'textarea', 'Candidatures spontanées, stages et collaborations : précisez l’objet « Opportunité professionnelle ».' ),
				'contact_subjects'   => array( __( 'Contact — objets du formulaire (un par ligne)', 'kraak' ), 'lines', "Un projet d’architecture\nUne collaboration\nUn concours\nOpportunité professionnelle\nPresse / publication\nAutre" ),
			),
		),
		'kraak_studio'  => array(
			'title'  => __( 'Page Studio', 'kraak' ),
			'fields' => array(
				'studio_intro'     => array( __( 'Texte manifeste', 'kraak' ), 'textarea', 'KRAAK Associates est un bureau d’architecture émergent explorant les relations entre espace, territoire et société. Fondé par un architecte ayant acquis une expérience professionnelle au Japon, le studio développe une approche rigoureuse inspirée de la précision constructive et de la sensibilité contextuelle propres à l’architecture japonaise.' ),
				'founder_name'     => array( __( 'Nom du fondateur', 'kraak' ), 'text', 'Fernand Kiendrebeogo' ),
				'founder_role'     => array( __( 'Fonction', 'kraak' ), 'text', 'Architecte · Fondateur' ),
				'founder_photo'    => array( __( 'Portrait', 'kraak' ), 'image', '' ),
				'founder_bio'      => array( __( 'Biographie', 'kraak' ), 'textarea', '[Biographie — formation, expérience professionnelle au Japon, projets marquants et centres d’intérêt. 120 à 180 mots.]' ),
				'parcours'         => array( __( 'Parcours (Année | étape, une par ligne)', 'kraak' ), 'lines', "2019 | Premier au concours d’entrée de l’EAMAU\n[Année] | [Diplôme d’architecte]\n[Année] | Expérience professionnelle au Japon — [agence, ville]\n2025 | Sakura Science Laureate\n2025 | Lauréat du concours international ATX Design\n[Année] | Fondation de KRAAK Associates" ),
				'philo_title'      => array( __( 'Philosophie — titre', 'kraak' ), 'textarea', 'Précision, minimalisme, matérialité.' ),
				'philo_items'      => array( __( 'Philosophie — principes (un par ligne)', 'kraak' ), 'lines', "L’architecture comme outil d’exploration spatiale\nDialogue entre contextes locaux et influences internationales\nPrécision, minimalisme et matérialité, hérités de l’expérience japonaise\nIntérêt pour les situations complexes ou improbables\nRecherche entre architecture, territoire et société" ),
				'values'           => array( __( 'Valeurs (une par ligne)', 'kraak' ), 'lines', "Innovation\nRigueur conceptuelle\nArchitecture contextuelle\nRecherche et expérimentation\nApproche internationale" ),
				'join_title'       => array( __( 'Bandeau final — titre', 'kraak' ), 'textarea', 'Rejoindre ou collaborer avec le studio.' ),
			),
		),
	);
}

/**
 * Lit une option avec sa valeur par défaut.
 *
 * @param string $key Clé.
 * @return string
 */
function kraak_opt( $key ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = array();
		foreach ( kraak_options_schema() as $section ) {
			foreach ( $section['fields'] as $k => $f ) {
				$defaults[ $k ] = $f[2];
			}
		}
	}
	return (string) get_theme_mod( 'kraak_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Option « lignes » → tableau. Avec $split, chaque ligne « a | b » devient [a, b].
 *
 * @param string $key   Clé.
 * @param bool   $split Découper sur « | ».
 * @return array
 */
function kraak_opt_lines( $key, $split = false ) {
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', kraak_opt( $key ) ) ) );
	if ( ! $split ) {
		return array_values( $lines );
	}
	return array_values( array_map( function ( $l ) {
		$parts = array_map( 'trim', explode( '|', $l, 2 ) );
		return array( $parts[0], isset( $parts[1] ) ? $parts[1] : '' );
	}, $lines ) );
}

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_panel( 'kraak', array(
		'title'    => __( 'KRAAK Associates', 'kraak' ),
		'priority' => 30,
	) );

	foreach ( kraak_options_schema() as $section_id => $section ) {
		$wp_customize->add_section( $section_id, array(
			'title' => $section['title'],
			'panel' => 'kraak',
		) );
		foreach ( $section['fields'] as $key => $f ) {
			list( $label, $type, $default ) = $f;
			$setting = 'kraak_' . $key;
			$sanitize = 'sanitize_text_field';
			if ( in_array( $type, array( 'textarea', 'lines' ), true ) ) {
				$sanitize = 'sanitize_textarea_field';
			} elseif ( 'url' === $type ) {
				$sanitize = 'esc_url_raw';
			} elseif ( 'email' === $type ) {
				$sanitize = 'sanitize_email';
			} elseif ( 'image' === $type ) {
				$sanitize = 'absint';
			}
			$wp_customize->add_setting( $setting, array(
				'default'           => $default,
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			) );
			if ( 'image' === $type ) {
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $setting, array(
					'label'     => $label,
					'section'   => $section_id,
					'mime_type' => 'image',
				) ) );
			} else {
				$wp_customize->add_control( $setting, array(
					'label'   => $label,
					'section' => $section_id,
					'type'    => in_array( $type, array( 'textarea', 'lines' ), true ) ? 'textarea' : ( 'url' === $type ? 'url' : ( 'email' === $type ? 'email' : 'text' ) ),
				) );
			}
		}
	}
} );
