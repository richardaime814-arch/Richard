<?php
/**
 * Fonctions d'affichage réutilisables.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

/**
 * Logo KRAAK Associates (SVG, couleur héritée) ou logo personnalisé.
 *
 * @param string $class Classe CSS supplémentaire.
 */
function kraak_logo( $class = '' ) {
	$name = get_bloginfo( 'name' );
	echo '<a class="logo ' . esc_attr( $class ) . '" href="' . esc_url( home_url( '/' ) ) . '" rel="home" aria-label="' . esc_attr( $name ) . '">';
	if ( has_custom_logo() ) {
		echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'logo__img', 'alt' => $name ) );
	} else {
		?>
		<svg class="logo__mark" viewBox="0 0 104 100" aria-hidden="true" focusable="false">
			<polygon points="58,0 86,0 22,64 7,79 7,43" fill="currentColor"/>
			<polygon points="2,98 41,44 102,98 80,98 41,66 23,98" fill="currentColor"/>
			<g fill="var(--accent)"><rect x="45" y="89" width="4" height="4"/><rect x="50" y="89" width="4" height="4"/><rect x="45" y="94" width="4" height="4"/><rect x="50" y="94" width="4" height="4"/></g>
		</svg>
		<span class="logo__text"><span class="logo__name">KRAAK</span><span class="logo__sub">ASSOCIATES</span></span>
		<?php
	}
	echo '</a>';
}

/**
 * Petit carré rouge (fenêtre du logo), utilisé comme repère graphique.
 */
function kraak_square() {
	return '<span class="sq" aria-hidden="true"><i></i><i></i><i></i><i></i></span>';
}

/**
 * Étiquette de section : ■ 01 —— LE STUDIO.
 *
 * @param string $num  Numéro / lettre.
 * @param string $text Libellé.
 * @param string $tag  Balise.
 */
function kraak_label( $num, $text, $tag = 'p' ) {
	printf(
		'<%1$s class="label">%2$s<span class="label__num">%3$s</span><span class="label__line"></span><span class="label__text">%4$s</span></%1$s>',
		tag_escape( $tag ),
		kraak_square(), // phpcs:ignore WordPress.Security.EscapeOutput
		esc_html( $num ),
		esc_html( $text )
	);
}

/**
 * Flèche →.
 */
function kraak_arrow( $dir = 'right' ) {
	$paths = array(
		'right' => 'M1 8h13M9 3l5 5-5 5',
		'down'  => 'M8 1v13M3 9l5 5 5-5',
		'ne'    => 'M3 13L13 3M5 3h8v8',
		'left'  => 'M15 8H2M7 3L2 8l5 5',
	);
	return '<svg class="arrow arrow--' . esc_attr( $dir ) . '" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path d="' . $paths[ $dir ] . '" fill="none" stroke="currentColor" stroke-width="1.4"/></svg>';
}

/**
 * Image : pièce jointe WordPress, ou image de démonstration du thème.
 *
 * @param int    $id       ID de la pièce jointe (0 = aucune).
 * @param string $fallback Fichier dans assets/demo (sans extension) ou ''.
 * @param string $size     Taille.
 * @param array  $attr     Attributs.
 * @return string
 */
function kraak_image( $id, $fallback = '', $size = 'large', $attr = array() ) {
	$attr = wp_parse_args( $attr, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	if ( $id && wp_attachment_is_image( $id ) ) {
		return wp_get_attachment_image( $id, $size, false, $attr );
	}
	if ( $fallback ) {
		$html = '<img src="' . esc_url( KRAAK_URI . '/assets/demo/' . $fallback . '.jpg' ) . '"';
		foreach ( $attr as $k => $v ) {
			$html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
		}
		if ( ! isset( $attr['alt'] ) ) {
			$html .= ' alt=""';
		}
		return $html . '>';
	}
	return '';
}

/**
 * Image d'une option du Customizer, avec repli.
 */
function kraak_opt_image( $key, $fallback, $size = 'large', $attr = array() ) {
	return kraak_image( absint( kraak_opt( $key ) ), $fallback, $size, $attr );
}

/**
 * Référence d'un projet / d'une recherche : P–01.
 *
 * @param int|WP_Post $post Contenu.
 * @return string
 */
function kraak_code( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$code = get_post_meta( $post->ID, 'kraak_code', true );
	if ( $code ) {
		return $code;
	}
	$prefix = 'recherche' === $post->post_type ? 'R' : 'P';
	return $post->menu_order ? sprintf( '%s–%02d', $prefix, $post->menu_order ) : '';
}

/**
 * Premier terme d'une taxonomie.
 */
function kraak_term( $post, $tax ) {
	$terms = get_the_terms( $post, $tax );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
}

/**
 * Valeur de champ avec texte de substitution entre crochets si vide.
 */
function kraak_meta( $post_id, $key, $placeholder = '' ) {
	$v = get_post_meta( $post_id, $key, true );
	return '' !== $v ? $v : $placeholder;
}

/**
 * Ligne « TYPE  [LIEU]  [ANNÉE] » d'une carte projet.
 */
function kraak_project_meta_line( $post = null ) {
	$post = get_post( $post );
	$term = kraak_term( $post, 'recherche' === $post->post_type ? 'type_recherche' : 'type_projet' );
	$parts = array(
		$term ? $term->name : '',
		kraak_meta( $post->ID, 'kraak_lieu', __( '[Lieu]', 'kraak' ) ),
		kraak_meta( $post->ID, 'kraak_annee', __( '[Année]', 'kraak' ) ),
	);
	echo '<p class="meta-line">';
	foreach ( array_filter( $parts ) as $p ) {
		echo '<span>' . esc_html( $p ) . '</span>';
	}
	echo '</p>';
}

/**
 * IDs d'une galerie.
 */
function kraak_gallery( $post_id, $key = 'kraak_gallery' ) {
	return array_values( array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, $key, true ) ) ) ) );
}

/**
 * Éléments de navigation principaux (menu WordPress « Menu principal » ou liste par défaut).
 *
 * @return array[] [ 'title', 'url', 'current' ]
 */
function kraak_nav_items() {
	$items     = array();
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['primary'] ) ) {
		foreach ( (array) wp_get_nav_menu_items( $locations['primary'] ) as $item ) {
			if ( ! $item->menu_item_parent ) {
				$items[] = array( 'title' => $item->title, 'url' => $item->url );
			}
		}
	}
	if ( ! $items ) {
		$studio  = get_page_by_path( 'studio' );
		$contact = get_page_by_path( 'contact' );
		$items   = array(
			array( 'title' => __( 'Projets', 'kraak' ), 'url' => get_post_type_archive_link( 'projet' ) ),
			array( 'title' => __( 'Laboratoire', 'kraak' ), 'url' => get_post_type_archive_link( 'recherche' ) ),
			array( 'title' => __( 'Studio', 'kraak' ), 'url' => $studio ? get_permalink( $studio ) : home_url( '/studio/' ) ),
			array( 'title' => __( 'Distinctions', 'kraak' ), 'url' => get_post_type_archive_link( 'distinction' ) ),
			array( 'title' => __( 'Contact', 'kraak' ), 'url' => $contact ? get_permalink( $contact ) : home_url( '/contact/' ) ),
		);
	}
	$scheme  = is_ssl() ? 'https://' : 'http://';
	$current = isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ? $scheme . wp_unslash( $_SERVER['HTTP_HOST'] ) . strtok( wp_unslash( $_SERVER['REQUEST_URI'] ), '?' ) : ''; // phpcs:ignore
	$current = trailingslashit( $current );
	$home    = trailingslashit( home_url( '/' ) );
	foreach ( $items as &$it ) {
		$u             = trailingslashit( strtok( (string) $it['url'], '?#' ) );
		$it['current'] = $u !== $home && 0 === strpos( $current, $u );
	}
	return $items;
}

/**
 * Sélecteur de langue FR / EN (Polylang ou WPML s'ils sont installés).
 */
function kraak_lang_switcher() {
	echo '<ul class="lang">';
	if ( function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
		foreach ( (array) $langs as $l ) {
			printf(
				'<li><a href="%1$s" hreflang="%2$s" %3$s>%4$s</a></li>',
				esc_url( $l['url'] ),
				esc_attr( $l['locale'] ),
				$l['current_lang'] ? 'aria-current="true" class="is-current"' : '',
				esc_html( strtoupper( $l['slug'] ) )
			);
		}
	} elseif ( has_filter( 'wpml_active_languages' ) ) {
		$langs = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
		foreach ( (array) $langs as $l ) {
			printf(
				'<li><a href="%1$s" %2$s>%3$s</a></li>',
				esc_url( $l['url'] ),
				$l['active'] ? 'aria-current="true" class="is-current"' : '',
				esc_html( strtoupper( $l['language_code'] ) )
			);
		}
	} else {
		echo '<li><span class="is-current" aria-current="true">FR</span></li><li><span title="' . esc_attr__( 'Version anglaise : installer Polylang', 'kraak' ) . '">EN</span></li>';
	}
	echo '</ul>';
}

/**
 * URL de la page Contact.
 */
function kraak_contact_url() {
	$p = get_page_by_path( 'contact' );
	return $p ? get_permalink( $p ) : home_url( '/contact/' );
}

/**
 * URL de la page Studio.
 */
function kraak_studio_url() {
	$p = get_page_by_path( 'studio' );
	return $p ? get_permalink( $p ) : home_url( '/studio/' );
}

/**
 * Carte topographique (SVG décoratif) avec le repère du studio.
 */
function kraak_topo_map( $class = '' ) {
	?>
	<svg class="topo <?php echo esc_attr( $class ); ?>" viewBox="0 0 600 360" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
		<g fill="none" stroke="currentColor" stroke-width="1">
			<path d="M40 60 C140 40 260 70 360 50 S540 30 580 70" opacity=".35"/>
			<path d="M30 90 C120 70 250 100 360 80 S520 70 575 110" opacity=".45"/>
			<path d="M50 130 C60 100 160 90 280 95 S480 90 560 150 C520 170 470 190 450 240 S330 330 240 320 S90 260 50 130Z" opacity=".55"/>
			<path d="M90 140 C110 115 200 115 290 118 S440 120 500 165 C460 185 420 210 400 250 S310 300 240 292 S120 230 90 140Z" stroke-width="1.6"/>
			<path d="M140 150 C160 130 230 133 300 136 S410 145 440 175 C410 190 380 210 360 240 S300 268 250 262 S170 220 140 150Z" opacity=".6"/>
			<path d="M195 160 C205 150 250 150 300 155 S380 165 395 185 C365 195 345 215 320 230 S270 240 250 232 S205 200 195 160Z"/>
			<path d="M240 172 C260 165 300 168 330 175 S360 188 350 196 C330 205 300 215 275 212 S240 195 240 172Z" opacity=".7"/>
			<path d="M275 182 C290 178 315 182 322 190 S300 202 285 199 S268 188 275 182Z"/>
			<path d="M20 200 C40 260 90 300 150 330" opacity=".35"/>
			<path d="M560 200 C520 240 500 300 470 350" opacity=".35"/>
			<path d="M10 240 C50 300 100 330 170 355" opacity=".3"/>
		</g>
		<g fill="var(--accent)" transform="translate(292 184)"><rect width="5" height="5"/><rect x="6" width="5" height="5"/><rect y="6" width="5" height="5"/><rect x="6" y="6" width="5" height="5"/></g>
	</svg>
	<?php
}
