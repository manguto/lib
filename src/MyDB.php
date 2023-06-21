<?php

namespace manguto\lib;

use Lazer\Classes\Helpers\Validate;
use Lazer\Classes\Database as Database;

class MyDB {
	// ####################################################################################################
	/**
	 * verifica se uma tabela existe
	 *
	 * @param string $tableName
	 * @return boolean
	 */
	static function tableExists(string $tableName, bool $throwException = true){
		try{
			Validate::table($tableName)->exists();
			return true;
		}catch ( \Throwable $e ){
			if($throwException){
				throw new \Exception("Tabela '$tableName' não encontrada!");
			}else{
				return false;
			}
		}
	}
	// ####################################################################################################
	/**
	 * cria uma tabela (caso nao exista)
	 *
	 * @param string $tableName
	 * @param array $tableFields
	 * @param bool $throwException
	 * @throws \Exception
	 * @return bool
	 */
	static function createTable(string $tableName, array $tableFields, bool $throwException = true): bool{ // verifica se a tabela existe
		if(! self::tableExists($tableName, false)){
			Database::create($tableName, $tableFields);
			return true;
		}else{
			if($throwException){
				throw new \Exception("Não foi possível criar a tabela '$tableName', pois esta já se encontra cadastrada!");
			}else{
				return false;
			}
		}
	}
	// ####################################################################################################
	/**
	 * retorna o ultimo id inserido da tabela ou '0' caso esta encontre-se vazia
	 *
	 * @param string $tableName
	 * @return int
	 */
	static function getLastId(string $tableName): int{ // obtem as configuracoes
		$config = Database::table($tableName)->config();
		return intval($config->last_id);
	}
	// ####################################################################################################
	/**
	 * obtem o registro (array) da tabela/identificador informados caso exista
	 *
	 * @param string $tablename
	 * @param int $id
	 * @param bool $throwException
	 * @throws \Exception
	 * @return array
	 */
	static function find(string $tablename, int $id, bool $throwException = true){
		$return = [];
		if($id > 0){
			// obtem o objeto em questao
			$row = Database::table($tablename)->find($id);
			// obtencao dos campos do registro
			$fields = $row->fields();
			// percorre os campos do registro definindo-os no retorno
			foreach($fields as $field){
				$return[$field] = $row->$field;
			}
		}else{
			if($throwException){
				throw new \Exception("Não foi possível obter o registro da tabela ($tablename) com o identificador informado ($id)!");
			}
		}
		return $return;
	}
	// ####################################################################################################
	/**
	 * pesquisa por um registro em uma tabela
	 *
	 * @param string $tableName
	 * @param string $parameterName
	 * @param string $parameterValue
	 * @param string $operator:
	 *        	- Standard operators (=, !=, >, <, >=, <=)
	 *        	- IN (only for array value)
	 *        	- NOT IN (only for array value)
	 * @return array|array[]|NULL[]|mixed[]|mixed|object
	 */
	static function search(string $tableName, string $parameterName = 'id', string $parameterValue = '0', string $operator = '>'){
		{ // busca o registro informado
			$return = Database::table($tableName)->where($parameterName, $operator, $parameterValue)->findAll()->asArray();
		}
		return $return;
	}
	// ####################################################################################################
	static function save(string $tablename, MyDBTable $object){
		{ // obtencao do identificador do objeto
			$id = $object->id ?? false;
			// deb($id);
		}
		if($id !== false && $id != 0){
			// obtencao do registro no bd
			$row = Database::table($tablename)->find($object->id);
		}else{
			// criacao de registro para insercao no bd
			$row = Database::table($tablename);
		}
		// obtencao dos campos da tabela
		$schema = $row->schema();
		// deb($schema);
		// redefinicao dos parametros
		foreach($schema as $field => $fieldType){
			if(isset($object->$field)){
				// definicao do valor no objeto Database
				$row->$field = self::convertType($object->$field, $fieldType);
			}
		}
		// save
		$row->save();
	}
	// ####################################################################################################
	/**
	 * converte o tipo do valor para o tipo do formato informado
	 *
	 * @param
	 *        	$value
	 * @param string $type
	 * @return string|int|double
	 */
	static private function convertType($value, string $type){
		switch ($type) {
			case 'boolean' :
				$value = boolval($value);
				break;
			case 'integer' :
				$value = intval($value);
				break;
			case 'double' :
				$value = floatval($value);
				break;
			case 'string' :
				$value = strval($value);
				break;
			default :
				throw new \Exception("Não foi possível converter o parâmetro informado (" . strval($value) . ") para o tipo solicitado ($type)!");
				break;
		}
		
		return $value;
	}
	// ####################################################################################################
	/**
	 * retorna o tipo compativel com os tipos da classe Database,
	 * conforme o tipo informado da variavel da classe
	 * (ex.: int => integer, float => double, string => string)
	 *
	 * @param
	 *        	$value
	 * @param string $type
	 * @return string|int|double
	 */
	static function convertClassVariableTypeToDatabaseFieldType(string $classVariableType): string{
		/**
		 * - boolean
		 * - integer
		 * - string
		 * - double (also for float type)
		 */
		switch ($classVariableType) {
			case 'bool' :
				return 'boolean';
				break;
			case 'int' :
				return 'integer';
				break;
			case 'float' :
				return 'double';
				break;
			case 'string' :
				return 'string';
				break;
			default :
				return 'string';
				break;
		}
	}
	// ####################################################################################################
}
