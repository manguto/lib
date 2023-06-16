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
	 * retorna o html de um grafico PIZZA
	 *
	 * @param string $title
	 *        	- 'pieChart'
	 * @param array $data
	 *        	- [2, 10, 16, 30, 42]
	 * @param array $legend
	 *        	- ['Work', 'Eat', 'Sleep', 'Listen to music', 'Code']
	 * @param bool $displayLegend
	 * @return array - chart,legend
	 */
	static function pie(string $title, array $data, array $legend): array{
		{
			$Chart = new Chart('pie', $title);
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
		return [
				$chart,
				$legend
		];
	}
	// ####################################################################################################
	/**
	 * grafico de barras
	 *
	 * @param string $title
	 * @param array $sets
	 * @param array $setsLegens
	 * @param array $setsCategories
	 * @return array
	 */
	static function bar(string $title, array $sets, array $setsLegends, array $setsCategories): array{
		{
			if(false) // ////////////////// MODELO DE PARAMETROS //////////////////////// //
			{
				$sets = [
						[
								2,
								10,
								16
						],
						[
								42,
								30,
								16
						]
				];
				$setsLegends = [
						'01/01',
						'01/02',
						'01/03'
				];
				$setsCategories = [
						'Inputs',
						'Outputs'
				];
			} // /////////////////////////////////////////////////////////////////////// //
			{
				/*
				 * // An example of a bar chart with multiple datasets
				 */
				$Chart = new Chart('bar', $title);
				$Chart->set('data', $sets);
				$Chart->set('legend', $setsLegends);
				// We don't use the x-axis for the legend so we specify the name of each dataset
				$Chart->set('legendData', $setsCategories);
				// $barChart->set('displayLegend', false);
			}
			{
				// just chart
				$chart = $Chart->returnFullHTML();
				// just legend
				if(sizeof($setsCategories) > 0){
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

