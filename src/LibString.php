<?php

namespace manguto\lib;
/**
 * @author Manguto
 *
 */
class LibString{
	
	private $content;
	
	public function __construct(string $content){
		$this->content = $content;
	}
	
	/**
	 * uppercase
	 */
	public function strtoupper() { 
		$this->content = LibStringStatic::strtoupper($this->content);
	}
	
	/**
	 * lowercase
	 */
	public function strtolower() {
		$this->content = LibStringStatic::strtolower($this->content);
	}
	
	/**
	 * charset change
	 * @param string $param
	 * @param string $input_encoding
	 * @param string $output_encoding
	 */
	public function charsetEncode(string $param,string $input_encoding='ISO-8859-1', string $output_encoding='UTF-8') {
		$this->content = LibStringStatic::charsetEncode($this->content,$input_encoding,$output_encoding);
	}
	
	
}

