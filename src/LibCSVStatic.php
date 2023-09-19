<?php

namespace manguto\lib;

use PHP_CodeSniffer\Reports\Csv;

/**
 *
 * @author Manguto
 *        
 */
class LibCSVStatic {
	static function csvToArray(string $filename){
		if(($handle = fopen($filename, 'r')) === false){
			Throw new \Exception("Não foi possível abrir/acessar o arquivo (*.CSV) solicitado!");
		}
		$headers = fgetcsv($handle, 256, ';');
		$headers = array_map("\manguto\lib\LibString::_charsetEncode", $headers);
		$return = [];
		while ( $row = fgetcsv($handle, 256, ';') ){
			$row = array_map("\manguto\lib\LibString::_charsetEncode", $row);
			$return[] = array_combine($headers, $row);
		}
		return $return;
		fclose($handle);
	}
	/**
	 * carrega o arquivo e verifica corretude de dados
	 *
	 * @param string $filename
	 * @throws \Exception
	 * @return array
	 */
	static function getArrayFromCSVFile(string $filename,bool $utf8_encode=true): array{
		// obter arquivo
		$file = new LibFile($filename);
		$handle = $file->getHandle();
		{
			$fields = [];
			$fieldsLength = 0;
			$rowNumber = 0;
			$return = [];
		}
		while ( ($data = fgetcsv($handle, null, ",")) !== FALSE ){
			{ // obtencao da quantidade de campos da linha
				$fieldsLength_temp = count($data);
			}
			{ // percorrimento dos campos
				for($c = 0; $c < $fieldsLength_temp; $c++){
					if($rowNumber == 0){
						{ // definicao dos titulos dos campos
							$fields[$c] = trim($data[$c]);
							$fieldsLength++;
						}
					}else{
						{ // verificacao de alteracao do numero de campos padrao
							if($fieldsLength_temp != $fieldsLength){
								throw new \Exception("Não foi possível carregar o arquivo (CSV) solicitado, pois foi encontrada uma linha ($rowNumber) que esté com a quantidade de campos incompatível!");
							}
						}
						{ // registro dos valores da linha e campo
						  //
							{ // definicao do nome do campo
								$fieldName = $fields[$c] ?? 'INDEFINIDO';
							}
							{ // dado
								$dado = $data[$c];
								
								//UTF-8 encode?
								if($utf8_encode){
									$dado = LibString::_charsetEncode($dado);									
								}
							}
							$return[$rowNumber][$fieldName] = $dado;
						}
					}
				}
				// incremento do numero da linha
				$rowNumber++;
			}
		}
		$file->closeHandle();
		return $return;
	}	
	// ####################################################################################################
	static function getCSVStringLineFromArray(array $array, string $valueDelimiter=','){
		$lineDelimiter = chr(10);
		foreach ($array as $key=>$value) {
			
			{//tratamento quando da existencia de delimitadores no conteudo
				{//value
					if(strpos($value, $valueDelimiter)!==false || strpos($value, $lineDelimiter)!==false){
						$array[$key] = '"'.$value.'"';
					}
				}
				{//line
					if(strpos($value, $lineDelimiter)!==false){
						$array[$key] = str_replace($lineDelimiter, ' ', $value);						
					}
				}
				
			}
			
		}
		return implode($valueDelimiter,$array);		
	}
	// ####################################################################################################
	// ####################################################################################################
	// ####################################################################################################
}

