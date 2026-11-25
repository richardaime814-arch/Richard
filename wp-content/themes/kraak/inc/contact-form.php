<?php
/**
 * Formulaire « Écrire au studio » : envoi par e-mail (wp_mail), sans extension.
 *
 * Conseil : installer une extension SMTP (ex. WP Mail SMTP) pour une bonne délivrabilité.
 *
 * @package kraak
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_nopriv_kraak_contact', 'kraak_handle_contact' );
add_action( 'admin_post_kraak_contact', 'kraak_handle_contact' );

/**
 * Traite l'envoi du formulaire.
 */
function kraak_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : kraak_contact_url();
	$back = remove_query_arg( array( 'envoye', 'erreur' ), $back );

	$fail = function ( $code ) use ( $back ) {
		wp_safe_redirect( add_query_arg( 'erreur', $code, $back ) . '#formulaire' );
		exit;
	};

	if ( ! isset( $_POST['kraak_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['kraak_nonce'] ), 'kraak_contact' ) ) {
		$fail( 'session' );
	}

	// Pot de miel anti-robots : ce champ caché doit rester vide.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'envoye', '1', $back ) . '#formulaire' );
		exit;
	}

	$subject = isset( $_POST['objet'] ) ? sanitize_text_field( wp_unslash( $_POST['objet'] ) ) : '';
	$name    = isset( $_POST['nom'] ) ? sanitize_text_field( wp_unslash( $_POST['nom'] ) ) : '';
	$org     = isset( $_POST['organisation'] ) ? sanitize_text_field( wp_unslash( $_POST['organisation'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		$fail( 'champs' );
	}

	// Limite : un envoi par minute et par adresse IP.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'kraak_contact_' . md5( $ip );
	if ( get_transient( $key ) ) {
		$fail( 'attente' );
	}
	set_transient( $key, 1, MINUTE_IN_SECONDS );

	$to   = kraak_opt( 'email' ) ? kraak_opt( 'email' ) : get_option( 'admin_email' );
	$body = sprintf(
		"%s : %s\n%s : %s\n%s : %s\n%s : %s\n\n%s\n\n—\n%s",
		__( 'Objet', 'kraak' ), $subject,
		__( 'Nom', 'kraak' ), $name,
		__( 'Organisation', 'kraak' ), $org ? $org : '—',
		__( 'E-mail', 'kraak' ), $email,
		$message,
		home_url( '/' )
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	$sent    = wp_mail( $to, '[KRAAK] ' . $subject . ' — ' . $name, $body, $headers );

	if ( ! $sent ) {
		$fail( 'envoi' );
	}
	wp_safe_redirect( add_query_arg( 'envoye', '1', $back ) . '#formulaire' );
	exit;
}

/**
 * Message de retour à afficher au-dessus du formulaire.
 */
function kraak_contact_notice() {
	// phpcs:disable WordPress.Security.NonceVerification
	if ( isset( $_GET['envoye'] ) ) {
		return array( 'ok', __( 'Merci, votre message a bien été envoyé. Le studio vous répondra rapidement.', 'kraak' ) );
	}
	if ( isset( $_GET['erreur'] ) ) {
		$code = sanitize_key( $_GET['erreur'] );
		$msgs = array(
			'champs'  => __( 'Merci de renseigner votre nom, une adresse e-mail valide et un message.', 'kraak' ),
			'attente' => __( 'Merci de patienter une minute avant un nouvel envoi.', 'kraak' ),
			'session' => __( 'La session a expiré, merci de réessayer.', 'kraak' ),
			'envoi'   => __( 'L’envoi a échoué. Écrivez-nous directement par e-mail.', 'kraak' ),
		);
		return array( 'error', isset( $msgs[ $code ] ) ? $msgs[ $code ] : $msgs['envoi'] );
	}
	// phpcs:enable
	return null;
}
