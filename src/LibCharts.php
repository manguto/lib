<?php

namespace manguto\lib;

use Antoineaugusti\EasyPHPCharts\Chart;

/**
 *
 * @author Manguto
 *        
 */
class LibCharts {
	// ####################################################################################################
	/**
	 * remove caracteres nao permitidos para um titulo da bibilioteca em questao
	 *
	 * @param string $title
	 */
	private static function titleFix(string $title){
		{
			$title = str_replace('/', '_', $title);
		}
		return $title;
	}
	// ####################################################################################################
	/**
	 * retorna o html de um grafico PIZZA
	 *
	 * @param string $title
	 * @param array $data
	 * @param array $legend
	 * @return array - chart,legend
	 */
	static function pie(string $title, array $data, array $legend): array{
		{
			if(false) // ////////////////// MODELO DE PARAMETROS //////////////////////// //
			{
				$title = self::titleFix('pie_chart');
				$data = [
						2,
						10,
						16,
						30,
						42
				];
				$legend = [
						'Work',
						'Eat',
						'Sleep',
						'Listen to music',
						'Code'
				];
			} // /////////////////////////////////////////////////////////////////////// //
		}
		{
			{
				$title = self::titleFix($title);
			}
			$Chart = new Chart('pie', $title . '_' . uniqid());
			$Chart->set('data', $data);
			$Chart->set('legend', $legend);
			$Chart->set('displayLegend', false);
			{
				// just chart
				$chart = $Chart->returnFullHTML();
				// just legend
				$legend = $Chart->returnLegend();
			}
		}
		// debc($chart);
		return [
				$chart,
				$legend
		];
	}
	// ####################################################################################################
	/**
	 * GRAFICO DE BARRAS EMERGENCIAL (22/08)
	 *
	 * @param string $title
	 * @param array $sets
	 * @param array $setsLegens
	 * @param array $setsCategories
	 * @return array
	 */
	static function bar(array $data_array, array $legend_array): array{
		{//parameters
			$id = uniqid('chart_bar_');
			
			{//especificar o valor maximo de caracteres
				$legendMax = 0;
				foreach ($legend_array as $legend){
					$q = strlen($legend);
					if($legendMax<$q){
						$legendMax = $q;
					}
				}
			}
			{//especificacao da largura da coluna das legendas
				$legendWidthMin = $legendMax*10;
			}
			{//especificar o valor maximo para os dados
				$dataMax = 0;
				foreach ($data_array as $data){
					if($dataMax<$data){
						$dataMax = $data;
					}
				}
			}
			{//especificacao da largura da coluna de exibicao da barra
				//$showWidthMin = $dataMax+50;
				$showWidthMin = 20;
			}
			
		}
		{//css
			{//colors
				//$c1 = "#A9CCEE";
				$c1 = "#800";
				//$c2 = "#092C4E";
				$c2 = "#f00";
			}
			$css = "<style>

				table#{$id} 					{  }
				table#{$id} *					{ text-shadow:0 1px 2px #ccc; }
				table#{$id} td					{ }
				table#{$id} td.legend			{ font-size:16px; letter-spacing:1px; min-width:{$legendWidthMin}px; }
				table#{$id} td.show				{  }				
				table#{$id} td.show *					{ font-size: 16px;}
				table#{$id} td.show .draw 				{ display:none; float:left; cursor:pointer; min-width:{$showWidthMin}px; color:#fff; margin-right:10px; text-align:right; background:linear-gradient(to bottom right, $c2,$c1); padding:5px 10px; border-radius:3px;}
				table#{$id} td.show .draw:hover 		{ color:#fff; background:linear-gradient(to right, $c1,$c2); }

			</style>";
		}
		{//javascript
			$js = "<script type='text/javascript'>
						$(document).ready(function(){
							$('table#{$id} td.legend').css({'width':'30%'});
							$('table#{$id} td.show').css({'width':'70%'});							
							$('table#{$id} .draw').fadeIn(1000);
						});
					</script>";
		}
		{//html	
			$chart = "";
			$chart .= "<table id='$id' border='0' class='table table-striped table-hover'>";
			{
				foreach ($legend_array as $key=>$legend){
					$chart .= "<tr>";
					{//legenda
						$chart .= "<td class='legend'>$legend</td>";					
					}
					{//valor
						{
							$value = $data_array[$key];
							$drawWidth = $value + $showWidthMin; 
						}
						{
							$chart .= "<td class='show' title='$value'>";
							$chart .= "<div class='draw' style='width:".$drawWidth."px'>$value</div>";
							$chart .= "</div>";							
						}
						$chart .= "</td>";					
					}
					$chart .= "</tr>";					
				}				
			}			
			$chart .= "</table>";
		}
		return [
				$css.$chart.$js,
				FALSE
		];
	}
	// ####################################################################################################
	/**
	 * grafico de barras 
	 * => APRESENTANDO ERRO QUANDO DE 5 OU MAIS COLUNAS!
	 * => APRESENTANDO ERRO QUANDO DE 5 OU MAIS COLUNAS!
	 * => APRESENTANDO ERRO QUANDO DE 5 OU MAIS COLUNAS!
	 * => APRESENTANDO ERRO QUANDO DE 5 OU MAIS COLUNAS!
	 * @param string $title
	 * @param array $sets
	 * @param array $setsLegens
	 * @param array $setsCategories
	 * @return array
	 */
	static function bar_ERRO(string $title, array $data, array $legend, array $categories = []): array{
		{
			if(false) // ////////////////// MODELO DE PARAMETROS //////////////////////// //
			{
				$data = [
						[
								50,
								60,
								70
						],
						[
								20,
								25,
								30
						]
				];
				$legend = [
						'jan/01',
						'feb/02',
						'mar/03'
				];
				$categories = [
						'Input',
						'Output'
				];
			} // /////////////////////////////////////////////////////////////////////// //
			{
				/*
				 * // An example of a bar chart with multiple datasets
				 */
				{
					$title = self::titleFix($title);
				}
				$Chart = new Chart('bar', $title . '_' . uniqid());
				$Chart->set('data', $data);
				$Chart->set('legend', $legend);
				// We don't use the x-axis for the legend so we specify the name of each dataset
				$Chart->set('legendData', $categories);
				// $barChart->set('displayLegend', false);
			}
			{
				// just chart
				$chart = $Chart->returnFullHTML();
				// just legend
				if(sizeof($categories) > 0){
					$legend = $Chart->returnLegend();
				}else{
					$legend = '';
				}
			}
		}
		return [
				$chart,
				$legend
		];
	}
	// ####################################################################################################
	// ####################################################################################################
	// ####################################################################################################
	// ####################################################################################################
	/**
	 * chart samples
	 *
	 * @return string[]
	 */
	static function charts(){
		$return = [];
		{
			{
				// ####################################################################################################
				/*
				 * // A basic example of a pie chart
				 */
				$pieChart = new Chart('pie', 'examplePie');
				$pieChart->set('data', array(
						2,
						10,
						16,
						30,
						42
				));
				$pieChart->set('legend', array(
						'Work',
						'Eat',
						'Sleep',
						'Listen to music',
						'Code'
				));
				$pieChart->set('displayLegend', true);
				$return[] = $pieChart->returnFullHTML();
				// ####################################################################################################
				/*
				 * // An example of a doughnut chart with legend in percentages
				 */
				$doughnutChart = new Chart('doughnut', 'exampleDoughnut');
				$doughnutChart->set('data', array(
						2,
						10,
						16,
						30,
						42
				));
				$doughnutChart->set('legend', array(
						'Work',
						'Eat',
						'Sleep',
						'Listen to music',
						'Code'
				));
				$doughnutChart->set('displayLegend', true);
				$doughnutChart->set('legendIsPercentage', true);
				$return[] = $doughnutChart->returnFullHTML();
				// ####################################################################################################
				/*
				 * // An example of a bar chart with multiple datasets
				 */
				$barChart = new Chart('bar', 'examplebar');
				$barChart->set('data', array(
						array(
								2,
								10,
								16,
								30,
								42
						),
						array(
								42,
								30,
								16,
								10,
								2
						)
				));
				$barChart->set('legend', array(
						'01/01',
						'01/02',
						'01/03',
						'01/04',
						'01/05'
				));
				// We don't to use the x-axis for the legend so we specify the name of each dataset
				$barChart->set('legendData', array(
						'Annie',
						'Marc'
				));
				$barChart->set('displayLegend', true);
				$return[] = $barChart->returnFullHTML();
				// ####################################################################################################
				/*
				 * // An example of a radar chart
				 */
				$radarChart = new Chart('radar', 'exampleradar');
				$radarChart->set('data', array(
						20,
						55,
						16,
						30,
						42
				));
				$radarChart->set('legend', array(
						'A',
						'B',
						'C',
						'D',
						'E'
				));
				$return[] = $radarChart->returnFullHTML();
				// ####################################################################################################
				/*
				 * // An example of a polar chart
				 */
				$polarChart = new Chart('polar', 'examplepolar');
				$polarChart->set('data', array(
						20,
						55,
						16,
						30,
						42
				));
				$polarChart->set('legend', array(
						'A',
						'B',
						'C',
						'D',
						'E'
				));
				$return[] = $polarChart->returnFullHTML();
				// ####################################################################################################
			}
		}
		return implode('<hr/>', $return);
	}
}

