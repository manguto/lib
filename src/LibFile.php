<?php

namespace manguto\lib;
/**
 * @author Manguto
 *
 */
class LibFile{
	
	private $filename;
	private $handle;
	//
	//
	//
	// ####################################################################################################
	public function __construct(string $filename){
		$this->filename = $filename;		
	}
	
	/**
	 * verifica se o arquivo existe
	 * @return boolean
	 */
	public function fileExists(){
		return LibFileStatic::fileExists($this->filename);
	}
	/**
	 * retorna o 'handle' do arquivo
	 * @return 
	 */
	public function getHandle(){		
		$this->handle = LibFileStatic::getHandle($this->filename);
		return $this->handle;
	}
	/**
	 * fecha o handle do arquivo
	 */
	public function closeHandle(){
		LibFileStatic::closeHandle($this->handle);	
	}
	
	// ####################################################################################################
}

