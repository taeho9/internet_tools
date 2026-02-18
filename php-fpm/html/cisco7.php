<?php
class Cisco7 {

	// fixed vigenere used in Cisco Type 7 passwords
	static protected $vigenere=[
		0x64, 0x73, 0x66, 0x64, 0x3b, 0x6b, 0x66, 0x6f, 0x41, 0x2c, 0x2e,
		0x69, 0x79, 0x65, 0x77, 0x72, 0x6b, 0x6c, 0x64, 0x4a, 0x4b, 0x44,
		0x48, 0x53, 0x55, 0x42, 0x73, 0x67, 0x76, 0x63, 0x61, 0x36, 0x39,
		0x38, 0x33, 0x34, 0x6e, 0x63, 0x78, 0x76, 0x39, 0x38, 0x37, 0x33,
		0x32, 0x35, 0x34, 0x6b, 0x3b, 0x66, 0x67, 0x38, 0x37
	];

	// decrypt string
	static public function decrypt($string) {
		// variable to hold cleartext password
		$r="";
		// seed to Vigenere translation table
		$i=hexdec(substr($string, 0, 2));
		// initial pointer
		$c=2;
		// process each pair of hex values
		while ($c < strlen($string)) {
			$r.=chr(hexdec(substr($string, $c, 2)) ^ self::$vigenere[$i++]); // Vigenere reverse translation
			$i%=count(self::$vigenere); // Vigenere table wrap around
			$c+=2; // move pointer to next hex pair
		}
		// return decrypted string
		return $r;
	}

	// encrypt a string using a seed
	static public function encrypt($string, $seed=2) {
		// prepare seed
		$s=str_pad(strtoupper(dechex($seed)), 2, "0", STR_PAD_LEFT);
		// iterate for each byte and encrypt using Vigenere translation table
		for ($i=0; $i<strlen($string); $i++)
			$s.=str_pad(strtoupper(dechex(ord($string[$i])^self::$vigenere[($i+$seed)%count(self::$vigenere)])), 2, "0", STR_PAD_LEFT);
		// return encrypted string
		return $s;
	}

}
?>