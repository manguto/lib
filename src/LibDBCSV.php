<?php

namespace manguto\lib;


/**
 *
 * @author Manguto
 *        
 */
class LibDBCSV {
	//
	const utf8_encode = true;
	//
	//
	/**
	 * identificador do registro
	 *
	 * @var int
	 */
	protected int $id;
	//
	// ####################################################################################################
	public function __construct(int $id = 0){
		$this->id = $id;
		if($this->id > 0){
			$this->load();
		}
	}
	// ----------------------------------------------------------------------------------------------------
	public function getId(): int{
		return $this->id;
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * busca com multiplos parametros
	 *
	 * @param array $parameters
	 */
	private function searchMultiple(array $parameters){
		
		{ // busca para cada criterio
			$results = [];			
			foreach($parameters as $parameterData){
				//deb($parameterData);
				foreach ($parameterData as $fieldName => $fieldValue){
					$results[] = $this->searchSingle($fieldName, $fieldValue);
				}				
			}
			//deb($results);
		}
		{//intersecao
			foreach ($results as $result){
				if(sizeof($result)==0){
					return [];
				}else{
					{//inicialziacao do retorno (caso necessario)
						$return = $return ?? $result;
					}
					$return = array_intersect_key($return, $result);
				}
			}
			//deb($return);
		}
		return $return;
	}
	// ----------------------------------------------------------------------------------------------------
	private function searchSingle($fieldName = null, $fieldValue = null){
		$return = [];
		{ // obtencao da base
			$base = LibCSVStatic::getArrayFromCSVFile($this->getFilename(), self::utf8_encode);
			//deb($base);
		}
		{ // verificacao do que se busca (geral ou especifico)
			$lines = [];
			if(isset($fieldName) && isset($fieldValue)){
				foreach($base as $line_data){
					$fieldValue_temp = $line_data[$fieldName] ?? false;
					if($fieldValue_temp !== false && strval($fieldValue_temp) == strval($fieldValue)){
						$lines[] = $line_data;
					}
				}
			}else{
				$lines = $base;
			}
			// deb($search);
		}
		{ // conversao em objetos
			foreach($lines as $line_data){
				// deb($register);
				{ // nome da classe
					$class = $this->getClass();
				}
				$obj = new $class();
				$obj->load($line_data);
				// deb($obj);
				$id = $line_data['id'];
				$return[$id] = $obj;
			}
		}
		// deb($return);
		return $return;
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * Busca por registros
	 *
	 * @param $fieldName 
	 * @param $fieldValue	 
	 * @return array
	 */
	public function search($fieldName = null, $fieldValue = null): array{
		//deb($fieldName,0); deb($fieldValue);		
		{ // analise de tipo de busca
			if(gettype($fieldName) != 'array'){
				{ // busca simples
					return $this->searchSingle($fieldName, $fieldValue);
				}
			}else{
				{ // busca com multiplos parametros					
					return $this->searchMultiple($fieldName);
				}
			}
		}
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * Caso o objeto possua algum campo referenciando outra tabela (_id), faz o carregamento do(s) mesmo(s)
	 * e levanta uma excecao caso nao seja possivel (por padrao => throwException).
	 */
	public function loadReferences(bool $throwException=true){
		$referenceFields = LibDBCSVReference::getReferenceFieldArray($this);
		//deb($referenceFields);
		foreach($referenceFields as $fieldNames){
			foreach($fieldNames as $fieldName){
				{
					$fieldContent = $this->$fieldName;
					//deb($fieldName);
				}
				{
					$DBCSVReference = new LibDBCSVReference($fieldName, $fieldContent, $throwException);
					//debc($DBCSVReference);
					if($DBCSVReference===false){
						//nao eh possivel carregar uma referencia inexistente
						continue;
					}
				}
				//deb($fieldName,0); deb($DBCSVReference->target);
				$target = $DBCSVReference->target;
				//deb($target);
				$this->$fieldName = $target;
				//deb($this->$fieldName);
			}
		}
		//deb($this);
	}
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	/**
	 * a impressao do objeto, retora sempre o ID do mesmo.
	 * caso seja necessaria uma impressao mais avancada, criar funcao paralela.
	 *
	 * @return string
	 */
	public function __toString(){
		return strval($this->id);
	}
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	// ####################################################################################################
	/**
	 * define o nome do arquivo csv da base em questao
	 *	 
	 */
	protected function getFilename(){
		{ // verificao de diretorio e arquivo
			if(! defined('BD_CAMINHO')){
				throw new Exception("Não foi possível obter o caminho da base de dados (diretório) pois este não foi definido (BD_CAMINHO)!");
			}else{
				if(! is_dir(BD_CAMINHO)){
					throw new Exception("O diretório informado para a base de dados, não foi encontrado (BD_CAMINHO)!");
				}
			}
		}
		{ // obtencao do nome da classe
			$class = $this->getClass();
			// deb($className);
		}
		{ // obtencao do nome do arquivo da base de dados da classe
			//deb(BD_CAMINHO);
			$class = get_class($this);
			$class = str_replace('\\', '/', $class);
			$class = basename($class);			
			$filename = BD_CAMINHO . LibString::_strtolower($class) . '.csv';
			//deb($filename);
		}
		{ // verificacao do arquivo da base de dados
			if(! file_exists($filename)){
				throw new \Exception("Não foi possível encontrar o arquivo da base de dados informada! [$class]");
			}
		}
		//deb($filename);
		return $filename;
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * obtem o nome da classe do objeto extensor
	 *
	 * @return string
	 */
	protected function getClass(){
		return get_class($this);
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * Carrega os dados do objeto.
	 * Caso tenham sido fornecidos, diretamente.
	 * Caso contrario, realizando uma busca completa.
	 *
	 * @param array $parameters
	 * @throws \Exception
	 */
	private function load(array $parameters = []){
		if(sizeof($parameters) == 0){
			$base = $this->search();
			$item = $base[$this->id] ?? false;
			if($item === false){
				throw new \Exception("Não foi possível obter o registro solicitado (#{$this->id}), pois este não foi encontrado!");
			}
			$parameters = $item;
		}
		{ // carregamento dos dados no objeto
			foreach($parameters as $k => $v){
				$this->$k = $v;
			}
		}
	}
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
}

