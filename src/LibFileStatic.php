<?php

namespace manguto\lib;
/**
 * @author Manguto
 *
 */
class LibFileStatic{
	
	/**
	 * obtem o arquivo para manipulacao
	 * @param string $filename
	 * @throws \Exception
	 * @return resource|boolean
	 */
	static function getHandle(string $filename){
		//verificacao de existencia do arquivo
		self::fileExists($filename);
		//obtencao do recurso
		$handle = fopen($filename, "r");
		//verificacao da acessbilidade do recurso
		if($handle === FALSE){
			throw new \Exception("Não foi possível abrir o arquivo solicitado!");
		}
		return $handle;
	}
	
	/**
	 * verifica
	 * @param string $filename
	 * @throws \Exception
	 */
	static function fileExists(string $filename){
		//verificacao de existencia do arquivo
		if(!file_exists($filename)){
			throw new \Exception("Não foi possível encontrar o arquivo solicitado!");
		}
		return true;
	}
	
	/**
	 * fecha o arquivo em manipulacao
	 * @param $handle
	 * @throws \Exception
	 */
	static function closeHandle($handle){
		$return = fclose($handle);
		if($return===false){
			throw new \Exception("Não foi possível 'fechar' o arquivo solicitado!");
		}
	}
	
}

