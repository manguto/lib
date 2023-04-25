<?php

/** 
 * @author Marcos
 * 
 */
class MangutoStrings extends MangutoStrings{

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
	
	
	 
}

