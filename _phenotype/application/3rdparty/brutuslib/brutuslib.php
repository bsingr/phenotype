<?php

// FUNCTION - Check if key is <4, 65 500> bytes and divided by 4
function brutus_check_key($key_length){

($key_length < 4 || $key_length > 65500) ? $error = -1: (($key_length % 4 != 0) ? $error = -2: $error = 0);
return $error;
}


// FUNCTION - Generate random string
function brutus_generate_rand_str($str_length){

	$string = '';

	for($a = 0; $a < $str_length; $a++){
		$string .= chr(rand(0, 255));
	}

	return $string;
}

// FUNCTION - encode
function brutus_encode($string, $key){

	// Check key paramaters
	$key_length = strlen($key);
	$error = brutus_check_key($key_length);
	if($error == -1 || $error == -2){
		return $error;
	}

	// If message is not divided by key_length fill random chars
	$string_length = strlen($string) + 2;
	$no_blank_chars = $key_length - ($string_length % $key_length);

	($no_blank_chars != 0) ? $string .= brutus_generate_rand_str($no_blank_chars):
	$string_length += $no_blank_chars;

	// Add 2 bytes random string length info
	$string = chr($fb = $no_blank_chars >> 8).chr($no_blank_chars - ($fb << 8)).$string;

	// Divide message to blocks based on key length
	for($a = 0; $a < $string_length; $a += $key_length){
		$block[] = substr($string, $a, $key_length);
	}

	// Main encoding
	$no_block = count($block);
	$string = '';
	$original_key = $key;

	for($a = 0; $a < $no_block; $a++){

		// Key definition
		if($a < $no_block - 1){
			$key = $block[$a + 1];
		}
		else{
			$key = $original_key;
		}

		// STEP 1 - Byte substitution
		$reverse_key = strrev($key);

		for($b = 0; $b < $key_length; $b++){
			$main_value = (ord($key[$b]) << 8) + ord($reverse_key[$b]);
			$need_value = ($main_value % $key_length);

			$temp = $block[$a][$need_value];
			$block[$a][$need_value] = $block[$a][$b];
			$block[$a][$b] = $temp;
		}

		// STEP 2 - XOR
		$block[$a] = $block[$a] ^ $key;

		// STEP 3 - Number transformation
		for($b = 0; $b < $key_length; $b++){
			$temp = ord($block[$a][$b]) + ord($key[$b]);

			($temp < 10) ? $string .= "00".$temp: ($temp < 100 ? $string .= "0".$temp: $string .= $temp);
		}
	}

	// Code comprimation
	$newstring = '';

	for($a = 0; $a < strlen($string); $a++){
		$newstring .= chr($string[$a].$string[++$a]);
	}

	return $newstring;

}

// FUNCTION - decode
function brutus_decode($string, $key){

	// Check key paramaters
	$key_length = strlen($key);
	brutus_check_key($key_length);

	// Code decomprimation
	$newstring = '';

	for($a = 0; $a < strlen($string); $a++){
		$temp = ord($string[$a]);
		($temp < 10) ? $newstring .= "0".$temp : $newstring .= $temp;
	}

	// Divide message to blocks based on key
	$newstring_length = strlen($newstring);
	$real_length = $key_length * 3;

	for($a = 0; $a < $newstring_length; $a += $real_length){
		$block[] = substr($newstring, $a,  $real_length);
	}

	// Main decoding
	$no_block = count($block);
	$string = '';

	for($a = $no_block-1; $a > -1; $a--){

		// STEP 3 - Number transformation
		$temp = '';

		for($b = 0; $b < $real_length; $b++){

			$temp .= chr(($block[$a][$b].$block[$a][++$b].$block[$a][++$b]) - ord($key[$b/3]));
		}
		$block[$a] = $temp;

		// STEP 2 - XOR
		$block[$a] = $block[$a] ^ $key;

		// STEP 1 - Byte substitution
		$reverse_key = strrev($key);

		for($b = $key_length-1; $b > -1; $b--){

			$main_value = (ord($key[$b]) << 8) + ord($reverse_key[$b]);
			$need_value = ($main_value % $key_length);

			$temp = $block[$a][$need_value];
			$block[$a][$need_value] = $block[$a][$b];
			$block[$a][$b] = $temp;
		}

		$string = $block[$a].$string;

		$key = $block[$a];
	}

	// Calculate blank chars
	$no_blank_chars = (ord($string[0]) << 8) + ord($string[1]);

	return substr($string, 2, strlen($string) - $no_blank_chars - 2);

}

// ------- END OF LIBRARY -------- following code is example


// User defined errors
define('ERROR_OUT_INTERVAL', 'Key is out of interval <4, 65 536>');
define('ERROR_NOT_DIVIDED', 'Key is not be divided by 4');

// Key generation - must be divide by 4
$key = brutus_generate_rand_str(8000);

// Define message to encode (may be file content)
$message = 'This is message for encoding';


// Encode message
$encoded = brutus_encode($message, $key);

// Handle errors
if($encoded == -1){
	die(ERROR_OUT_INTERVAL);
}
elseif($encoded == -2){
	die(ERROR_NOT_DIVIDED);
}

// Decode message
$decoded = brutus_decode($encoded, $key);
?>