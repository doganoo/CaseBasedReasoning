<?php
class Core {
	private $cazeArray = null;
	private $weightsArray = null;
	private $query = null;
	private $alpha = 1;
	private $simArray = array ();
	private static $SHOW_DETAILS = true;
	private $pltfSimArray = array ();
	private $prioSimArray = array ();
	private $projectSimArray = array ();
	private $pnSimArray = array ();
	private $saSimArray = array ();
	private $scSimArray = array ();
	private $sopSimArray = array ();
	private $tkSimArray = array ();
	function __construct($cazeArray, $query) {
		if (is_array ( $cazeArray )) {
			foreach ( $cazeArray as $singleCaze ) {
				if (! is_a ( $singleCaze, "Caze" )) {
					die ( "Class Core: paramter caze has to be object type Caze" );
				}
			}
		}
		if (! is_a ( $query, "Caze" )) {
			die ( "Class Core: paramter query has to be object type Caze" );
		}
		$this->cazeArray = $cazeArray;
		$this->query = $query;
		
		//4. PLTF Similarity Array
		$this->pltfSimArray = $this->generateSimilarityArray ( 3 );
		
		//8. Priority Similarity Array
		$this->prioSimArray = $this->generateSimilarityArray ( 3 );
		
		//9. Project Similarity Array
		$this->projectSimArray = $this->generateSimilarityArray ( 10 );
		
		//10. PN Similarity Array
		$this->pnSimArray = $this->generateSimilarityArray ( 3 );
		
		//11. SA Similarity Array
		$this->saSimArray = $this->generateSimilarityArray ( 3 );
		
		//12. SC Similarity Array
		$this->scSimArray = $this->generateSimilarityArray ( 3 );
		
		//14. SC Similarity Array
		$this->sopSimArray = $this->generateSimilarityArray ( 3 );
		
		//17. TK Similarity Array
		$this->tkSimArray = $this->generateSimilarityArray ( 3 );
	}
	private function generateSimilarityArray($number) {
		$simArray = array ();
		for($i = 1; $i <= $number; $i ++) {
			for($j = 1; $j <= $number; $j ++) {
				$simArray ['q' . $i . 'c' . $j] = 1 - (abs ( $i - $j ) / $number);
			}
		}
		return $simArray;
	}
	public function getSimilarity() {
		$i = 1;
		$this->print_message ( "Query Description:" );
		$this->print_message ( $this->query );
		foreach ( $this->cazeArray as $caze ) {
			$sum = 0;
			$this->print_message ( $i . ". Case" );
			$this->print_message ( "============" );
			$this->print_message ( "Case Description:" );
			$this->print_message ( $caze );
			
			//1. Project Leader
			$this->print_message ( "1. attribute: Project Leader Experience" );
			$pleCaze = $caze->projectLeaderExperience;
			$pleQuery = $this->query->projectLeaderExperience;
			$weight = $caze->w_projectLeaderExperience;
			$res = - 1;
			$x = $pleQuery - $pleCaze;
			$s = - 1;
			//TODO richtigen Namen fuer threshold
			$threshold = 20;
			$this->print_message ( TAB . "if f(" . $pleQuery . "-" . $pleCaze . ") is in the interval of [" . $threshold . "," . ($threshold * - 1) . "], then y will be 1" );
			$this->print_message ( TAB . "otherwise e^(f(" . $pleQuery . "-" . $pleCaze . ")*(2/5)) will be operated" );
			//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
			//bei dem gilt: f(x) = 1
			if ($x <= $threshold && $x >= ($threshold * - 1)) {
				$s = 1;
			} else {
				//ansonsten nehme eine exponentiale Funktion: e^((x+5) * 2/5) bzw. e^((-x+5) * 2/5)
				if ($x > 0) {
					$s = $this->exponential ( (- 1 * $x) + $threshold, 2 / 5 );
				} else if ($x < 0) {
					$s = $this->exponential ( $x + $threshold, 2 / 5 );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "f(" . $pleQuery . " - " . $pleCaze . ") = f(" . $x . ") = s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//2. Project Leader Similar Projects
			$this->print_message ( "2. attribute: Project Leader Similar Projects" );
			$plspCaze = $caze->projectLeaderSimilarProjects;
			$plspQuery = $this->query->projectLeaderSimilarProjects;
			$x = $plspQuery - $plspCaze;
			$weight = $caze->w_projectLeaderSimilarProjects;
			$res = - 1;
			$s = - 1;
			$threshold = 10;
			$this->print_message ( TAB . "if f(" . $plspQuery . "-" . $plspCaze . ") is in the interval of [" . $threshold . ", " . ($threshold * - 1) . "], then y will be 1" );
			$this->print_message ( TAB . "otherwise e^(f(" . $pleQuery . "-" . $pleCaze . ")*(2/5)) will be operated" );
			//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
			//bei dem gilt: f(x) = 1
			if ($x <= $threshold && $x >= ($threshold * - 1)) {
				$s = 1;
			} else {
				//ansonsten nehme eine exponentiale Funktion: e^((x+threshold) * 2/5) bzw. e^((-x+threshold) * 2/5)
				if ($x > 0) {
					$s = $this->exponential ( (- 1 * $x) + $threshold, 2 / 5 );
				} else if ($x < 0) {
					$s = $this->exponential ( $x + $threshold, 2 / 5 );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "f(" . $plspQuery . " - " . $plspCaze . ") = f(" . $x . ") = s =" . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//3. Project Leader Success Rate
			$this->print_message ( "3. attribute: Project Leader Success Rate" );
			$plsrCaze = $caze->projectLeaderSuccessRate;
			$plsrQuery = $this->query->projectLeaderSuccessRate;
			$weight = $caze->w_projectLeaderSuccessRate;
			$res = - 1;
			$x = $plsrQuery - $plsrCaze;
			$s = - 1;
			$threshold = 20;
			$this->print_message ( TAB . "if f(" . $plsrQuery . "-" . $plsrCaze . ") is in the interval of [" . $threshold . ", " . ($threshold * - 1) . "], then y will be 1" );
			$this->print_message ( TAB . "otherwise e^(f(" . $pleQuery . "-" . $pleCaze . ")*(2/5)) will be operated" );
			//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
			//bei dem gilt: f(x) = 1
			if ($x <= $threshold && $x >= ($threshold * - 1)) {
				$s = 1;
			} else {
				//ansonsten nehme eine exponentiale Funktion: e^((x+threshold) * 2/5) bzw. e^((-x+5) * 2/5)
				if ($x > 0) {
					$s = $this->exponential ( (- 1 * $x) + $threshold, 2 / 5 );
				} else if ($x < 0) {
					$s = $this->exponential ( $x + $threshold, 2 / 5 );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "f(" . $plsrQuery . " - " . $plsrCaze . ") = f(" . $x . ") = s =" . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//4. Project Leader Team Familarity
			$this->print_message ( "4. attribute: Project Leader Team Familarity" );
			$pltfCaze = $caze->projectLeaderTeamFamilarity;
			$pltfQuery = $this->query->projectLeaderTeamFamilarity;
			$weight = $caze->w_projectLeaderTeamFamilarity;
			$res = - 1;
			$s = - 1;
			$s = $this->pltfSimArray ['q' . $pltfQuery . 'c' . $pltfCaze];
			$this->print_message ( TAB . "q = " . $pltfQuery );
			$this->print_message ( TAB . "c = " . $pltfCaze );
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->pltfSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$this->print_message ( EOL );
			$this->print_message ( TAB . "used similarity measure: similarity table" );
			
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//5. Customer ID
			$this->print_message ( "5. attribute: Customer ID" );
			$cidCaze = $caze->customerId;
			$cidQuery = $this->query->customerId;
			$weight = $caze->w_customerId;
			$this->print_message ( TAB . "if " . $cidQuery . " == " . $cidCaze . ", then y will be 1" );
			$this->print_message ( TAB . "otherwise y will be 0" );
			$s = 0;
			if ($cidCaze == $cidQuery) {
				$s = 1;
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//6. Development Process
			$this->print_message ( "6. attribute: Development Process" );
			$dpCaze = $caze->developmentProcess;
			$dpQuery = $this->query->developmentProcess;
			$weight = $caze->w_developmentProcess;
			$res = - 1;
			$this->print_message ( TAB . "if " . $dpQuery . " == " . $dpCaze . ", then y will be 1" );
			$this->print_message ( TAB . "otherwise y will be 0" );
			$s = 0;
			if ($dpCaze == $dpQuery) {
				$s = 1;
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//7. Internal Flag
			$this->print_message ( "7. attribute: Internal Flag" );
			$ifCaze = $caze->internalFlag;
			$ifQuery = $this->query->internalFlag;
			$weight = $caze->w_internalFlag;
			$res = - 1;
			$this->print_message ( TAB . "if " . $ifQuery . " == " . $ifCaze . ", then y will be 1" );
			$this->print_message ( TAB . "otherwise y will be 0" );
			$s = 0;
			if ($ifCaze == $ifQuery) {
				$s = 1;
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//8. Priority
			$this->print_message ( "8. attribute: Priority" );
			$pCaze = $caze->priority;
			$pQuery = $this->query->priority;
			$weight = $caze->w_priority;
			$res = - 1;
			$s = - 1;
			$s = $this->prioSimArray ['q' . $pQuery . 'c' . $pCaze];
			$this->print_message ( TAB . "q = " . $pQuery );
			$this->print_message ( TAB . "c = " . $pCaze );
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->prioSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$this->print_message ( EOL );
			$this->print_message ( TAB . "used similarity measure: similarity table" );
			
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//9. Project
			$this->print_message ( "9. attribute: Project" );
			$pCaze = $caze->project;
			$pQuery = $this->query->project;
			$weight = $caze->w_project;
			$res = - 1;
			$s = - 1;
			$s = $this->projectSimArray ['q' . $pQuery . 'c' . $pCaze];
			$this->print_message ( TAB . "q = " . $pQuery );
			$this->print_message ( TAB . "c = " . $pCaze );
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->projectSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//10. Project Novelty
			$this->print_message ( "10. attribute: Project Novelty" );
			$pnCaze = $caze->projectNovelty;
			$pnQuery = $this->query->projectNovelty;
			$weight = $caze->w_projectNovelty;
			$res = - 1;
			$s = - 1;
			$s = $this->pnSimArray ['q' . $pnQuery . 'c' . $pnCaze];
			$this->print_message ( TAB . "q = " . $pnQuery );
			$this->print_message ( TAB . "c = " . $pnCaze );
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->pnSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//11. System Architecture
			$this->print_message ( "11. attribute: System Architecture" );
			$saCaze = $caze->systemArchitecture;
			$saQuery = $this->query->systemArchitecture;
			$x = abs ( $saQuery - $saCaze );
			$weight = $caze->w_systemArchitecture;
			$res = - 1;
			$s = - 1;
			$this->print_message ( TAB . "q = " . $saQuery );
			$this->print_message ( TAB . "c = " . $saCaze );
			$s = $this->saSimArray ['q' . $saQuery . 'c' . $saCaze];
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->saSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//12. System Criticality
			$this->print_message ( "12. attribute: System Criticality" );
			$scCaze = $caze->systemCriticality;
			$scQuery = $this->query->systemCriticality;
			$weight = $caze->w_systemCriticality;
			$res = - 1;
			$s = - 1;
			$this->print_message ( TAB . "q = " . $scQuery );
			$this->print_message ( TAB . "c = " . $scCaze );
			$s = $this->scSimArray ['q' . $scQuery . 'c' . $scCaze];
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->scSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//13. System Dependency
			$this->print_message ( "13. attribute: System Dependency" );
			$sdCaze = $caze->systemDependency;
			$sdQuery = $this->query->systemDependency;
			$weight = $caze->w_systemDependency;
			$res = - 1;
			$this->print_message ( TAB . "if " . $sdQuery . " == " . $sdCaze . ", then y will be 1" );
			$this->print_message ( TAB . "otherwise y will be 0" );
			
			$s = 0;
			if ($plspCaze == $plspQuery) {
				$s = 1;
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//14. System Operating Mode
			$this->print_message ( "14. attribute: System Operating Mode" );
			$sopCaze = $caze->systemOperatingMode;
			$sopQuery = $this->query->systemOperatingMode;
			$weight = $caze->w_systemOperatingMode;
			$res = - 1;
			$s = - 1;
			$s = $this->sopSimArray ['q' . $sopQuery . 'c' . $sopCaze];
			$this->print_message ( TAB . "q = " . $sopQuery );
			$this->print_message ( TAB . "c = " . $sopCaze );
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->sopSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//15. System Special Reliability
			$this->print_message ( "15. attribute: System Special Reliability" );
			$sspCaze = $caze->systemSpecialReliability;
			$sspQuery = $this->query->systemSpecialReliability;
			$weight = $caze->w_systemSpecialReliability;
			$res = - 1;
			$this->print_message ( TAB . "if " . $sspQuery . " == " . $sspCaze . ", then y will be 1" );
			$s = 0;
			if ($sspCaze == $sspQuery) {
				$s = 1;
			}
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//16. Team Experience
			$this->print_message ( "16. attribute: Team Experience" );
			$teCaze = $caze->teamExperience;
			$teQuery = $this->query->teamExperience;
			$weight = $caze->w_teamExperience;
			$res = - 1;
			$x = $teQuery - $teCaze;
			$s = - 1;
			$threshold = 20;
			$this->print_message ( TAB . "if f(" . $teQuery . "-" . $teCaze . ") is in the interval of [" . $threshold . ", " . ($threshold * - 1) . "], then y will be 1" );
			$this->print_message ( TAB . "otherwise e^(f(" . $teQuery . "-" . $teCaze . ")*(2/5)) will be operated" );
			//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
			//bei dem gilt: f(x) = 1
			if ($x <= $threshold && $x >= ($threshold * - 1)) {
				$s = 1;
			} else {
				//ansonsten nehme eine exponentiale Funktion: e^((x+5) * 2/5) bzw. e^((-x+5) * 2/5)
				if ($x > 0) {
					$s = $this->exponential ( (- 1 * $x) + $threshold, 2 / 5 );
				} else if ($x < 0) {
					$s = $this->exponential ( $x + $threshold, 2 / 5 );
				}
			}
			$this->print_message ( "s = " . $s );
			$res = $weight * (pow ( $s, $this->alpha ));
			$sum = $sum + $res;
			$this->print_message ( "f(" . $plsrQuery . " - " . $plsrCaze . ") = f(" . $x . ") = s = " . $s );
			$this->print_message ( "w = " . $weight );
			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			$this->print_message ( EOL );
			
			//17. Test Kind
			$this->print_message ( "17. attribute: Test Kind" );
			$tkCaze = $caze->testKind;
			$tkQuery = $this->query->testKind;
			$s = $this->tkSimArray ['q' . $tkQuery . 'c' . $tkCaze];
			$res = $this->buildSingleSim ( $tkQuery, $tkCaze, $caze->w_testKind, $s );
			$sum = $sum + $res;
			// 			$tkCaze = $caze->testKind;
			// 			$tkQuery = $this->query->testKind;
			// 			$s = $this->tkSimArray ['q' . $tkQuery . 'c' . $tkCaze];
			// 			$this->print_message ( "17. attribute: Test Kind" );
			// 			$weight = $caze->w_testKind;
			// 			$res = - 1;
			// 			$this->print_message ( TAB . "q = " . $tkQuery );
			// 			$this->print_message ( TAB . "c = " . $tkCaze );
			

			// 			for($i = 1; $i <= 3; $i ++) {
			// 				for($j = 1; $j <= 3; $j ++) {
			// 					$this->print_message ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->tkSimArray ['q' . $i . 'c' . $j] );
			// 				}
			// 			}
			// 			$res = $weight * (pow ( $s, $this->alpha ));
			// 			$sum = $sum + $res;
			// 			$this->print_message ( "s = " . $s );
			// 			$this->print_message ( "w = " . $weight );
			// 			$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
			// 			$this->print_message ( EOL );
			$this->print_message ( "sum: " . $sum );
			$sim = pow ( $sum, 1 / $this->alpha );
			$this->print_message ( "sum^" . $this->alpha . ": " . $sim );
			$this->simArray [$caze->caseId] = $sim;
			$i ++;
		}
		print_r ( $this->simArray );
	}
	private function buildSingleSim($q, $c, $w, $s) {
		$res = - 1;
		$this->print_message ( TAB . "q = " . $q );
		$this->print_message ( TAB . "c = " . $c );
		$res = $w * (pow ( $s, $this->alpha ));
		$this->print_message ( "s = " . $s );
		$this->print_message ( "w = " . $w );
		$this->print_message ( "s * w^" . $this->alpha . " = " . $res );
		$this->print_message ( EOL );
		return $res;
	}
	private function threshold($x) {
		if ($x < 0) {
			return 1;
		} else if ($x >= 0) {
			return 0;
		} else {
			return false;
		}
	}
	private function linear($min, $max, $x) {
		if ($x < $min) {
			return 1;
		} else if ($x > $max) {
			return 0;
		} else if ($x >= $max && $x <= $min) {
			$return = ($max - $x) / ($max - $min);
		} else {
			return false;
		}
	}
	private function exponential($x, $a = 1) {
		return exp ( ($x * $a) );
	}
	private function sigmoid($x, $a) {
		$exp = $this->exponential ( ($x - 0 / $a) );
		$i = $exp + 1;
		return 1 / $i;
	}
	private function checkNumeric($value) {
		if ($value == "") {
			return false;
		} else if (empty ( $value )) {
			return false;
		} else if (! is_numeric ( $value )) {
			return false;
		} else {
			return true;
		}
	}
	private function print_message($message) {
		if (Core::$SHOW_DETAILS) {
			echo $message . EOL;
		}
	}
}