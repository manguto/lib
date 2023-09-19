<?php

namespace manguto\lib;

/**
 *
 * @author Manguto
 *        
 */
class LibDBCSVReference {
	//
	//
	const reference_type = [
			'_id' => 'Simples',
			'_ids' => 'Composto'
	];
	//
	const multipleReferenceDelimiter = ',';
	//
	public $fieldName;
	public $fieldContent;
	public $referenceType;
	public $objectClassName;
	public $target;
	//
	// ----------------------------------------------------------------------------------------------------
	public function __construct(string $fieldName, string $fieldContent, bool $throwException = false){
		$this->fieldName = $fieldName;
		{	
			$this->fieldContent = trim($fieldContent);
			if($this->fieldContent==''){
				if($throwException){
					throw new \Exception("Não é possível carregar um objeto sem a devida referência! [$fieldName,$fieldContent]");
				}else{
					return false;
				}
			}
		}
		$this->referenceType = self::getReferenceType($fieldName);
		$this->objectClassName = $this->getObjectClassName();
		$this->loadReferences();
	}
	// ----------------------------------------------------------------------------------------------------
	private function getObjectClassName(){
		{
			// nome bruto
			$return = str_replace($this->referenceType, '', $this->fieldName);
			$return = ucfirst($return);
			$return = '\\App\\Domain\\' . $return;
		}
		// deb($return);
		return $return;
	}
	// ----------------------------------------------------------------------------------------------------
	private function loadReferences(){
		{
			$objectClassName = $this->objectClassName;
			// deb($objectClassName);
			$rt = $this->referenceType;
			// deb($rt);
			$fieldContent = trim($this->fieldContent);			
		}
		{
			if($rt == '_id'){
				{ // REFERENCIA SIMPLES
					{
						$id = intval($fieldContent);
						if($id>0){
							$target = new $objectClassName($id);
							// deb($target);
						}else{
							throw new \Exception("Não é possível carregar o '$objectClassName' com o identificador informado! [$fieldContent] ");
						}
					}
					
				}
			}else if($rt == '_ids' && $fieldContent!=''){
				{ // REFERENCIA COMPOSTA
					$target = [];
					$ids = explode(self::multipleReferenceDelimiter, $fieldContent);
					// deb($content_array);
					foreach($ids as $id){
						
						$id = intval($id);
						if($id>0){
							$target[] = new $objectClassName($id);
							// deb($target);
						}else{
							throw new \Exception("Não é possível carregar um dos(as) '$objectClassName'(s) com o identificador informado! [$fieldContent] ");
						}
					}
				}
			}else{
				throw new \Exception("Não foi possível carregar o objeto referenciado através do tipo de refência e/ou conteúdos informados! [$rt,$fieldContent]");
			}
		}
		$this->target = $target;
	}
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
	//
	// ####################################################################################################
	//
	/**
	 * retorna um array com os campos referenciais do objeto
	 *
	 * @param object $object
	 * @return array
	 */
	static function getReferenceFieldArray(object $object): array{
		$return = [];
		{
			//deb($object,0);
			$vars = get_object_vars($object);
			//deb($vars,0);
			//throw new \Exception('aqui');
			foreach(array_keys($vars) as $varName){
				$referenceType = self::getReferenceType($varName);
				if($referenceType !== false){
					$return[$referenceType][] = $varName;
				}
			}
		}
		return $return;
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * Caso o nome do campo informado
	 * seja identificado como um campo de referencia,
	 * retorna o identificador do tipo do mesmo.
	 * Caso contrário, o boleano FALSE.
	 *
	 * @param string $fieldName
	 * @throws \Exception
	 * @return boolean|string
	 */
	private static function getReferenceType(string $fieldName){
		$return = [];
		{
			foreach(array_keys(self::reference_type) as $reference_str){
				{
					$length = strlen($reference_str);
					$offset = (- 1) * $length;
				}
				if(substr($fieldName, $offset, $length) == $reference_str){
					$return[] = $reference_str;
				}
			}
			{ // verificacao - apenas um tipo de referencia pode ser encontrado
				if(sizeof($return) == 0){
					return false;
				}else if(sizeof($return) == 1){
					return array_pop($return);
				}else{
					throw new \Exception("Não foi possível definiro tipo de referência do campo '$fieldName' do objeto em questão!");
				}
			}
		}
		return $return;
	}
	// ----------------------------------------------------------------------------------------------------
	/**
	 * retorna o conteudo bruto da referencia (_id => X ou _ids => X,Y,Z ou ...)
	 *
	 * @return string
	 */
	public function __toString(): string{
		return $this->fieldContent;
	}/**/
	// ----------------------------------------------------------------------------------------------------
	// ----------------------------------------------------------------------------------------------------
}

