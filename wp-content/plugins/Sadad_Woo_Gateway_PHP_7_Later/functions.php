<?php
if ( ! function_exists( 'sadad_sign_data' ) ) {
	function sadad_sign_data( $data, $secret ) {

		$error = error_reporting( E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE );
		//Generate a key from a hash
		$key = base64_decode( $secret );
		//Pad for PKCS7
		$blockSize = mcrypt_get_block_size( 'tripledes', 'ecb' );
		$len       = strlen( $data );
		$pad       = $blockSize - ( $len % $blockSize );
		$data      .= str_repeat( chr( $pad ), $pad );
		//Encrypt data
		$encData = mcrypt_encrypt( 'tripledes', $key, $data, 'ecb' );

		error_reporting( $error );

		return base64_encode( $encData );
	}
}

if ( ! function_exists( 'sadad_curl' ) ) {
	function sadad_curl( $url, $data ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type:application/json; charset=utf-8' ) );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		$result = curl_exec( $ch );
		curl_close( $ch );

		return ! empty( $result ) ? json_decode( $result ) : false;
	}
}

function encrypt_pkcs7($str, $key)
{
    $key = base64_decode($key);
    $ciphertext = OpenSSL_encrypt($str,"DES-EDE3", $key, OPENSSL_RAW_DATA);
    return base64_encode($ciphertext);
}