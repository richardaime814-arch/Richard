<?php
/**
 * KRAAK Associates — fonctions du thème.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

define( 'KRAAK_VERSION', '1.0.0' );
define( 'KRAAK_DIR', get_template_directory() );
define( 'KRAAK_URI', get_template_directory_uri() );

require KRAAK_DIR . '/inc/setup.php';
require KRAAK_DIR . '/inc/post-types.php';
require KRAAK_DIR . '/inc/meta-boxes.php';
require KRAAK_DIR . '/inc/customizer.php';
require KRAAK_DIR . '/inc/template-tags.php';
require KRAAK_DIR . '/inc/queries.php';
require KRAAK_DIR . '/inc/contact-form.php';
require KRAAK_DIR . '/inc/demo-content.php';
