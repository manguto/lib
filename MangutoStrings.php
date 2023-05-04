<?php

/**
 * @author Manguto
 *
 */
class MangutoStrings extends MangutoStringsStatic{
	
	private $content;
	
	public function __construct(string $content){
		$this->content = $content;
	}
	
	/**
	 * uppercase
	 */
	public function strtoupper() {
		$this->content = MangutoStringsStatic::strtoupper($this->content);
	}
	
	/**
	 * lowercase
	 */
	public function strtolower() {
		$this->content = MangutoStringsStatic::strtolower($this->content);
	}
	
	
	
}

