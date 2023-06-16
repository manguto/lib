<?php

namespace manguto\lib;
/**
 * @author Manguto
 *
 */
class LibCSVStatic{
	
	static function csvToArray(string $filename){
		if(($handle = fopen($filename, 'r')) === false){
			Throw new \Exception("Não foi possível abrir o arquivo CSV solicitado para realizar a conversão em lista!");
		}
		$headers = fgetcsv($handle, 256, ';');
		$headers = array_map("\manguto\lib\LibStringStatic::charsetEncode", $headers);
		$return = [];
		while ( $row = fgetcsv($handle, 256, ';') ){
			$row = array_map("\manguto\lib\LibStringStatic::charsetEncode", $row);
			$return[] = array_combine($headers, $row);
		}
		return $return;
		fclose($handle);
	}
		
	
}

