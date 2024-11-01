<?php

function vks_add_log( $event = '' ) {

	$gmt = current_time( 'timestamp', 1 );
	// local time
	$date = gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) );

	if ( false === ( $vks_log = get_transient( 'vks_log' ) ) ) {
		$vks_log = array();
	}

	$out = $date . ' ' . $event;

	if ( count( $vks_log ) > 100 ) {
		$vks_log = array_slice( $vks_log, - 99, 99 );
	}

	array_push( $vks_log, $out );
	set_transient( 'vks_log', $vks_log, YEAR_IN_SECONDS );
}

function vks_get_log( $lines = 50 ) {
	if ( false === ( $logs = get_transient( 'vks_log' ) ) ) {
		return 'No logs yet.';
	}

	if ( is_array( $logs ) ) {
		krsort( $logs );
		$logs = array_slice( $logs, 0, $lines );
	}

	return print_r( $logs, 1 );
}

function vks_the_log( $lines = 50, $separator = '<br/>' ) {
	if ( false === ( $logs = get_transient( 'vks_log' ) ) ) {
		return 'No logs yet.';
	}

	if ( is_array( $logs ) ) {
		krsort( $logs );
		$logs = array_slice( $logs, 0, $lines );
	}

	$out = array();
	$i   = 0;
	foreach ( $logs as $log ) {
		if ( $i % 10 == 0 ) {
			$out[] = '';
		}

		$out[] = $log;
		$i ++;
	}

	if ( ! empty( $out ) ) {
		$out = implode( $separator, $out );
	}

	return $out;
}


// https://drupal.org/node/2043439
function vks_remove_emoji( $text ) {
	$clean_text = "";

	// Match Emoticons
	$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clean_text     = preg_replace( $regexEmoticons, '', $text );

	// Match Miscellaneous Symbols and Pictographs
	$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clean_text   = preg_replace( $regexSymbols, '', $clean_text );

	// Match Transport And Map Symbols
	$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clean_text     = preg_replace( $regexTransport, '', $clean_text );

	// Match flags (iOS)
	$regexTransport = '/[\x{1F1E0}-\x{1F1FF}]/u';
	$clean_text     = preg_replace( $regexTransport, '', $clean_text );

	$clean_text = preg_replace( '/([0-9|#][\x{20E3}])|[\x{00ae}][\x{FE00}-\x{FEFF}]?|[\x{00a9}][\x{FE00}-\x{FEFF}]?|[\x{203C}][\x{FE00}-\x{FEFF}]?|[\x{2047}][\x{FE00}-\x{FEFF}]?|[\x{2048}][\x{FE00}-\x{FEFF}]?|[\x{2049}][\x{FE00}-\x{FEFF}]?|[\x{3030}][\x{FE00}-\x{FEFF}]?|[\x{303D}][\x{FE00}-\x{FEFF}]?|[\x{2139}][\x{FE00}-\x{FEFF}]?|[\x{2122}][\x{FE00}-\x{FEFF}]?|[\x{3297}][\x{FE00}-\x{FEFF}]?|[\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $clean_text );

	return $clean_text;
}


function vks_text_clean( $text ) {
	$text = strip_shortcodes( $text );
	$text = strip_tags( $text );
	$text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );
	$text = htmlspecialchars_decode( $text );

	return $text;
}

function vks_strlen( $text, $max_strlen, $encoding = 'UTF-8' ) {

	if ( mb_strlen( $text, $encoding ) >= $max_strlen ) {
		$text  = mb_substr( $text, 0, $max_strlen, $encoding );
		$words = explode( ' ', $text );
		array_pop( $words ); // strip last word

		$text = implode( ' ', $words );
	}

	return $text;
}


function vks_is_pro() {

	if ( function_exists( 'vks_pro_version' ) ) {
		return vks_pro_version();
	} else {
		return false;
	}
}

function vks_add_term_meta( $term_id, $meta_key, $meta_value, $unique = false ) {
	if ( function_exists( 'get_term_meta' ) ) {
		return add_term_meta( $term_id, $meta_key, $meta_value, $unique );
	} 
}


function vks_get_term_meta($term_id, $key = '', $single = false) {
	if ( function_exists( 'get_term_meta' ) ) {
		return get_term_meta($term_id, $key, $single);
	}
}


function vks_delete_term_meta( $term_id, $meta_key, $meta_value = '' ) {
	if ( function_exists( 'get_term_meta' ) ) {
		return delete_term_meta( $term_id, $meta_key, $meta_value );
	} 
}


function vks_edd_duplicate_product_exclude_meta($meta_keys){
	array_unshift($meta_keys, 'vk_item_id', 'vk_album_id', 'vks_updated');
	return $meta_keys;
}
add_filter('edd_duplicate_product_exclude_meta','vks_edd_duplicate_product_exclude_meta');