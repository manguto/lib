<?php

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
	
	
	
}

