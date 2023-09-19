<?php

namespace manguto\lib;

/**
 *
 * @author Manguto
 *        
 */
class LibString {
	//
	private $content;
	//
	//
	const lowerCases = [
			'a',
			'as',
			'e',			
			'o',
			'os',
			'da',
			'das',
			'de',
			'do',
			'dos',
			'na',
			'nas',
			'no',
			'nos',
			'seu',
			'seus',
			'sua',
			'suas',
			'meu',
			'meus',
			'minha',
			'minhas',
			'nossa',
			'nossas',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
	];
	//
	//
	public function __construct(string $content){
		$this->content = $content;
	}
	/**
	 * uppercase
	 */
	public function strtoupper(){
		$this->content = LibString::_strtoupper($this->content);
	}
	/**
	 * lowercase
	 */
	public function strtolower(){
		$this->content = LibString::_strtolower($this->content);
	}
	/**
	 * charset change
	 *
	 * @param string $param
	 * @param string $input_encoding
	 * @param string $output_encoding
	 */
	public function charsetEncode(string $param, string $input_encoding = 'ISO-8859-1', string $output_encoding = 'UTF-8'){
		$this->content = LibString::_charsetEncode($this->content, $input_encoding, $output_encoding);
	}
	// ####################################################################################################
	// ########################################################################################### STATICs
	// ####################################################################################################
	/**
	 */
	static function _strtoupper(string $string): string{
		// return strtoupper($string);
		return mb_strtoupper($string);
	}
	/**
	 */
	static function _strtolower(string $string): string{
		// return strtolower($string);
		return mb_strtolower($string);
	}
	/**
	 * charset encode (change)
	 *
	 * @param string $string
	 * @param string $input_encoding
	 * @param string $output_encoding
	 * @return string
	 */
	static function _charsetEncode(string $string, string $input_encoding = 'ISO-8859-1', string $output_encoding = 'UTF-8'): string{
		return mb_convert_encoding($string, $output_encoding, $input_encoding);
	}
	static function _ucfirst(string $string){
		return ucfirst(self::_strtolower($string));
	}
	static function _ucwords(string $string){
		return ucwords(self::_strtolower($string));
	}
	/**
	 * retorna uma string com todas as palavras com a primeira letra maiuscula, exceto excecoes pre determinadas (preposicoes, etc)
	 *
	 * @param string $string
	 * @return string
	 */
	static function _ucwordsPTBR(string $string){
		$return = self::_ucwords($string);
		
		//deb($return,0);
		{
			foreach(self::lowerCases as $lowerCase){
				
				{//evita registros vazios
					if(trim($lowerCase)=='') continue;
				}
				{
					$s = self::_ucfirst($lowerCase);
					$r = $lowerCase;
				}
				$return = str_replace(' '.$s.' ', ' '.$r.' ', $return);								
			}
		}
		//deb($return, 0);
		return $return;
	}
	/**
	 * substitui caracteres especiais
	 *
	 * @param string $string
	 * @return string|array|NULL
	 */
	static function _replaceSpecialChars(string $string){
		$utf8 = array(
				'/[áàâãªä]/u' => 'a',
				'/[ÁÀÂÃÄ]/u' => 'A',
				'/[ÍÌÎÏ]/u' => 'I',
				'/[íìîï]/u' => 'i',
				'/[éèêë]/u' => 'e',
				'/[ÉÈÊË]/u' => 'E',
				'/[óòôõºö]/u' => 'o',
				'/[ÓÒÔÕÖ]/u' => 'O',
				'/[úùûü]/u' => 'u',
				'/[ÚÙÛÜ]/u' => 'U',
				'/ç/' => 'c',
				'/Ç/' => 'C',
				'/ñ/' => 'n',
				'/Ñ/' => 'N',
				'/–/' => '_', // UTF-8 hyphen to "normal" hyphen
				'/[’‘‹›‚]/u' => '_', // Literally a single quote
				'/[“”«»„]/u' => '_', // Double quote
				'/ /' => '_' // nonbreaking space (equiv. to 0x160)
		);
		return preg_replace(array_keys($utf8), array_values($utf8), $string);
	}
	/**
	 * remove todos os caracteres não alfanuméricos
	 *
	 * @param string $string
	 * @param string $replace
	 * @return string|array|NULL
	 */
	static function _replaceNotAlphaNumericChars(string $string, string $replace = '_'){
		return preg_replace('/[^a-z\d ]/i', $replace, $string);
	}
	/**
	 * converte a string em caracateres basicos (alfanumericos e minusculos)
	 *
	 * @param string $string
	 * @return string
	 */
	static function _clean(string $string):string{
		$return = self::_replaceSpecialChars($string);
		$return = self::_replaceNotAlphaNumericChars($return);
		$return = self::_strtolower($return);
		return $return;
	}
	
	static function replaceAllNonPrintableCharacters(string $string,string $replace=' '):string{
		//return preg_replace('/[^\r\n[:print:]]/', $replace, $string);
		return preg_replace('/[\x00-\x1F\x7F]/u', $replace, $string);
	}
}

