<?php
namespace manguto\lib;
/**
 * @author Manguto
 *
 */
class LibStringStatic {
	
	/**
	 */
	static function strtoupper(string $string):string{
		return strtoupper($string);
	}
	
	/**
	 */
	static function strtolower(string $string):string{
		return strtolower($string);
	}
	
	/**
	 * charset encode (change)
	 * @param string $string
	 * @param string $input_encoding
	 * @param string $output_encoding
	 * @return string
	 */
	static function charsetEncode(string $string,string $input_encoding='ISO-8859-1', string $output_encoding='UTF-8'): string{
		return mb_convert_encoding($string, $output_encoding, $input_encoding);
	}
}


