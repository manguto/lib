<?php

namespace manguto\lib;

abstract class MyDBTable {
	public ?int $id;
	// ####################################################################################################
	public function __construct(?int $id = 0){
		// define identificador
		$this->id = $id;
		// carrega dados do registro
		$this->load();
	}
	// ####################################################################################################
	/**
	 * carrega o registro do banco de dados
	 */
	public function load(){
		{ // parametros
			[
					$tablename
			] = self::getParameters([
					'tablename'
			]);
		}
		{ // obtem os dados do registro
			$data = MyDB::find($tablename, $this->id, false);
		}
		{ // define os dados no objeto atual
			foreach($data as $k => $v){
				$this->$k = $v;
			}
		}
	}
	// ####################################################################################################
	public function save(){
		{ // parametros
			[
					$tablename
			] = self::getParameters([
					'tablename'
			]);
		}
		MyDB::save($tablename, $this);
	}
	// ####################################################################################################
	/**
	 * obtem os nomes e tipos das variaveis definidas na classe extensora
	 *
	 * @return array
	 */
	static protected function getFieldsConfiguration(): array{
		$return = [];
		{
			{ // obtencao das variaveis definidas na classe em questao
				$vars = get_class_vars(static::class);
			}
			{ // obtencao dos tipos das variaveis
				foreach(array_keys($vars) as $var){
					$classVariableType = (new \ReflectionProperty(static::class, $var))->getType()->getName();
					$DatabaseFieldType = MyDB::convertClassVariableTypeToDatabaseFieldType($classVariableType);
					$return[$var] = $DatabaseFieldType;
				}
			}
		}
		return $return;
	}
	// ####################################################################################################
	static function setup(){
		{ // parametros
			[
					$tablename,
					$extendedClassFields,
					$extendedClassDefault,
					$extendedClassnamePath
			] = self::getParameters([
					'tablename',
					'extendedClassFields',
					'extendedClassDefault',
					'extendedClassnamePath'
			]);
		}
		{ // verificacao/criacao da tabela
			MyDB::createTable($tablename, $extendedClassFields, false);
		}
		{ // verificacao/inclusao de registros base
			{ // obtencao do ultimo id inserido
				$lastId = MyDB::getLastId($tablename);
			}
			{ // verificacao de inexistencia de registros para insercao dos registros base
				if($lastId == 0){
					// preenche a tabela com os dados base
					foreach($extendedClassDefault as $b){
						// cria objeto extendido para insercao dos dados
						// deb($extendedClassnamePath);
						$object = new $extendedClassnamePath();
						foreach($b as $k => $v){
							$object->{$k} = $v;
						}
						// insere/salva novo objeto
						$object->save();
					}
				}
			}
		}
	}
	// ####################################################################################################
	/**
	 * realiza uma pesquisa no tabela em questao
	 *
	 * @param string $parameterName
	 * @param string $parameterValue
	 * @param string $operator:
	 *        	- Standard operators (=, !=, >, <, >=, <=)
	 *        	- IN (only for array value)
	 *        	- NOT IN (only for array value)
	 * @return array|NULL[]|mixed[]|mixed|object
	 */
	public function search(string $parameterName = 'id', string $parameterValue = '0', string $operator = '>'): array{
		{ // parametros
			[
					$extendedClassname
			] = self::getParameters([
					'extendedClassname'
			]);
		}
		{ // busca
			$return = MyDB::search($extendedClassname, $parameterName, $parameterValue, $operator);
		}
		return $return;
	}
	// ####################################################################################################
	/**
	 * CUIDADO!
	 * Apaga todos os dados
	 */
	static function RESET(){
		{ // parametros
			[
					$tablename
			] = self::getParameters([
					'tablename'
			]);
		}
		{
		/**
		 * CUIDADO!
		 * CUIDADO!
		 * CUIDADO!
		 * CUIDADO!
		 * CUIDADO!
		 * CUIDADO!
		 */
			// throw new \Exception("Você solicitou o 'RESET' da tabela '$tablename'. Tem certeza disto? Em caso afirmativo, comente esta linha do código.");
		}
		{ // arquivos a serem removidos
			if(LOCAL_SERVER){
				$path = 'App/Domain/' . ucfirst($tablename) . '/';
			}else{
				$path = '';
			}
			$filename = LAZER_DATA_PATH . $path . $tablename . '.config.json';
			$filename2 = LAZER_DATA_PATH . $path . $tablename . '.data.json';
		}
		{ // remocao dos arquivos
			unlink($filename);
			unlink($filename2);
		}
	}
	// ####################################################################################################
	// ####################################################################################################
	// ####################################################################################################
	/**
	 * obtem parametros basicos para diversas operacoes
	 *
	 * @return array
	 */
	static private function getParameters(array $parameters): array{
		{ // levantamento dos parametros necessarios
			{
				{ // obtencao do nome da classe extendida
					$extendedClassnamePath = static::class;
				}
				{ // obtencao do nome da classe extendida
					$extendedClassname = basename($extendedClassnamePath);
				}
				{ // parametros (fields) da tabela
				  // $extendedClassFields = $extendedClassnamePath::fields;
					$extendedClassFields = $extendedClassnamePath::getFieldsConfiguration();
				}
				{ // registros base (default)
					if(defined("{$extendedClassnamePath}::default")){
						$extendedClassDefault = $extendedClassnamePath::default;
					}else if(defined("{$extendedClassnamePath}::defaultCSVFile")){
						$extendedClassDefaultCSV_filename = $extendedClassnamePath::defaultCSVFile;
						$extendedClassDefault = LibCSVStatic::csvToArray($extendedClassDefaultCSV_filename);
					}else{
						$extendedClassDefault = [];
					}
				}
				{ // obtencao do nome da tabela
					$tablename = strtolower($extendedClassname);
				}
			}
		}
		{ // preparacao do retorno
			$return = [];
			foreach($parameters as $parameterName){
				$parameterValue = $$parameterName ?? false;
				if($parameterValue === false){
					throw new \Exception("Não foi possível obter o parâmetro solicitado ($parameterName), pois este não foi definido!");
				}
				$return[] = $parameterValue;
			}
		}
		return $return;
	}
	// ####################################################################################################
}
