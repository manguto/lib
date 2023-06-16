<?php
/**
 * FUNCOES DE SUPORTE BASICO (GERAIS)
 */
/**
 * DEBUG - IMPRIME a representacao HTML da variavel informada
 *
 * @param bool $die
 * @param bool $backtrace
 */
function deb($var, $die = true, bool $backtrace = true,string $style=''){
	// backtrace show?
	if($backtrace){
		$backtrace = backtraceFix(get_backtrace(), false);
	}else{
		$backtrace = '';
	}
	// var_dump to string
	ob_start();
	var_dump($var);
	$var = ob_get_clean();
	// remove a kind of break lines
	{ // values highligth
		$var = str_replace('=>' . chr(10), ' => <span class="varContent">', $var);
		$var = str_replace(chr(10), '</span>' . chr(10), $var);
	}
	{ // remove spaces
		while ( strpos($var, '  ') ){
			$var = str_replace('  ', ' ', $var);
		}
	}
	{ // parameter name highligth
		$var = str_replace('["', '[<span class="varName">', $var);
		$var = str_replace('"]', '</span>]', $var);
	}
	{ // content highligth
		$var = str_replace('{', '<div class="varArrayContent">{', $var);
		$var = str_replace(' }', '}</div>', $var);
	}
	{ // bold values
		$var = str_replace(' "', ' "<span class="varContentValue">', $var);
		$var = str_replace('"</', '</span>"</', $var);
	}
	echo "
		<pre class='deb' title='$backtrace' style='$style'>$var</pre>
		<style>
		.deb {	line-height:17px;}
		.deb .varName {	background: #ffb;}
		.deb .varContent {}
		.deb .varContentValue {	background: #fbb;    padding: 0px 5px;    border-radius:2px;}
		.deb .varArrayContent {	border-bottom: solid 1px #ccc;	border-left: solid 1px #eee;	padding: 0px 0px 5px 5px;	margin: 0px 0px 0px 10px;    cursor:pointer;}
		.deb .varArrayContent:hover {    border-color:#555;}
		</style>";
	if($die){
		die();
	}
}
// #############################################################################################################################################
/**
 * realiza ajustes no backtrace informado para uma melhor apresentacao
 *
 * @param string $backtrace
 * @param boolean $sortAsc
 */
function backtraceFix(string $backtrace, $sortAsc = true){
	$backtrace = str_replace("'", '"', $backtrace);
	{ // revert order
		$backtrace_ = explode(chr(10), $backtrace);
		if($sortAsc == false){
			krsort($backtrace_);
		}
		$backtrace = implode(chr(10), $backtrace_);
	}
	return $backtrace;
}
// #############################################################################################################################################
/**
 * imprime a representacao STRING da variavel informada em um campo de texto
 *
 * @param mixed $var
 * @param bool $die
 * @param bool $backtrace
 * @param string $style
 */
function debc($var, bool $die = true, bool $backtrace = true, string $style = 'border:solid 1px #000; padding:5px; width:90%; height:400px;'): void{
	// backtrace show?
	if($backtrace){
		$backtrace = backtraceFix(get_backtrace(), false);
	}else{
		$backtrace = '';
	}
	// var_dump to string
	ob_start();
	var_dump($var);
	$var = ob_get_clean();
	echo "<textarea style='$style' title='$backtrace'>$var</textarea>";
	if($die){
		die();
	}
}
//
// #############################################################################################################################################
//
/**
 * Get Backtrace
 *
 * @return string
 */
function get_backtrace(): string{
	// obtem backtrace
	$trace = debug_backtrace();
	// removao da primeira linha relativa a chamada a esta mesma funcao
	array_shift($trace);
	// inversao da ordem de exibicao
	krsort($trace);
	$return = '';
	$step = 1;
	foreach($trace as $t){
		if(isset($t['file'])){
			$file = $t['file'];
			$line = $t['line'];
			$func = $t['function'];
			$return .= "#" . $step++ . " => $func() ; $file ($line)\n";
		}
	}
	// identacao
	$return = str_replace(';', '', $return);
	return $return;
}
//
// ############################################################################################################################ ERRORS FUNCTIONS
//
/**
 * funcao utilizaca para tratamento de erros fatais
 */
function fatal_error_handler(){
	$error = error_get_last();
	if($error !== NULL){
		$errno = $error["type"];
		$errfile = $error["file"];
		$errline = $error["line"];
		$errstr = $error["message"];
		echo format_fatal_error($errno, $errstr, $errfile, $errline);
		exit();
	}
}
//
// #############################################################################################################################################
//
/**
 * formatacao da exibicao do erro fatal
 *
 * @param integer $errno
 * @param string $errstr
 * @param
 *        	$errfile
 * @param integer $errline
 * @return string
 */
function format_fatal_error($errno, $errstr, $errfile, $errline){
	$trace = print_r(debug_backtrace(), true);
	$content = "<br />
    <table border='1' style='font-family:Courier New'>
        <tbody>
            <tr>
                <th>Error</th>
                <td><pre>$errstr</pre></td>
            </tr>
            <tr>
                <th>Errno</th>
                <td><pre>$errno</pre></td>
            </tr>
            <tr>
                <th>File</th>
                <td><pre>$errfile</pre></td>
            </tr>
            <tr>
                <th>Line</th>
                <td><pre>$errline</pre></td>
            </tr>
            <tr>
                <th>Trace</th>
                <td><pre>$trace</pre></td>
            </tr>
        </tbody>
    </table>";
	return $content;
}
//
// #############################################################################################################################################
//
//
?>