<?php

namespace manguto\lib;

/**
 *
 * @author Manguto
 *        
 */
class LibDirectory {
	private $directory;
	private $handle = false;
	//
	//
	//
	// ####################################################################################################
	public function __construct(string $directory){
		$this->directory = $directory;
		$this->directoryExists();
		$this->setHandle();
	}
	// ####################################################################################################
	public function getFiles(bool $recursive = false){
		$return = [];		
		{
			while ( ($file = readdir($this->handle)) !== false ){
				{ // evita informacoes desnecessarias
					if($file == '.' || $file == '..'){
						continue;
					}
				}
				$filename = $this->directory . '/' . $file;
				{ // verificacao se o caminho eh um diretorio
					if(is_file($filename)){						
						{ // registra o arquivo							
							$return[] = $filename;
						}
					}else{
						{ // verifica e implementa a busca recursica
							if($recursive){
								{ // carregar os arquivos
									$subDirectory = new self($this->directory . '/' . $file);
									$subDirectoryFiles = $subDirectory->getFiles($recursive);
									//deb($subDirectoryFiles);
								}
								{ // repasse de arquivos
									foreach($subDirectoryFiles as $subDirectoryFile){
										$return[] = $subDirectoryFile;
									}
								}
							}else{
								continue;
							}
						}
					}
				}
			}
		}
		return $return;
	}
	// ####################################################################################################
	/**
	 * verifica se o diretorio existe
	 *
	 * @return boolean
	 */
	private function directoryExists(bool $throwException = true){
		return self::_directoryExists($this->directory, $throwException);
	}
	/**
	 * carrega o 'handle' do diretorio
	 *
	 * @return
	 */
	private function setHandle(){
		$this->handle = self::_getHandle($this->directory);
	}
	/**
	 * destroy o 'handle'
	 */
	public function __destruct(){
		self::_closeHandle($this->handle);
	}
	// ####################################################################################################
	/**
	 * obtem o diretorio para manipulacao
	 *
	 * @param string $directory
	 * @throws \Exception
	 * @return resource|boolean
	 */
	static function _getHandle(string $directory){
		if(is_dir($directory)){
			$return = opendir($directory);
			if($return === false){
				throw new \Exception("Não foi possível abrir o caminho solicitado!");
			}else{
				return $return;
			}
		}else{
			throw new \Exception("Não foi possível abrir o caminho solicitado! Este pode não existir ou não ser um diretório!");
		}
	}
	/**
	 * verifica se o caminho informado é de um diretorio
	 *
	 * @param string $directory
	 * @throws \Exception
	 */
	static function _directoryExists(string $directory, bool $throwException = true){
		// verificacao de existencia do diretório
		if(! is_dir($directory)){
			if($throwException){
				throw new \Exception("Não foi possível encontrar o diretório solicitado! [$directory]");
			}else{
				return false;
			}
		}
		return true;
	}
	/**
	 * fecha o diretório em manipulacao
	 *
	 * @param
	 *        	$handle
	 * @throws \Exception
	 */
	static function _closeHandle($handle){
		$return = closedir($handle);
		if($return === false){
			throw new \Exception("Não foi possível 'fechar' o diretório solicitado!");
		}
	}
	// ####################################################################################################
}

