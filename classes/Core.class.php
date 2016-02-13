<?php
class Core {
	private $cazeArray = null;
	private $weightsArray = null;
	private $query = null;
	private $alpha = 1;
	private $simArray = array ();
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
		$this->prioSimArray = $this->generateSimilarityArray ( 4 );
		
		//9. Project Similarity Array
		$this->projectSimArray = $this->generateSimilarityArray ( 10 );
		
		//10. PN Similarity Array
		$this->pnSimArray = $this->generateSimilarityArray ( 3 );
		
		//11. SA Similarity Array
		$this->saSimArray = $this->generateSimilarityArray ( 4 );
		
		//12. SC Similarity Array
		$this->scSimArray = $this->generateSimilarityArray ( 3 );
		
		//14. SC Similarity Array
		$this->sopSimArray = $this->generateSimilarityArray ( 3 );
		
		//17. TK Similarity Array
		$this->tkSimArray = $this->generateSimilarityArray ( 4 );
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
		Util::printMessage ( "Query Description:" );
		Util::printMessage ( $this->query );
		foreach ( $this->cazeArray as $caze ) {
			$sum = 0;
			Util::printMessage ( $i . ". Case" );
			Util::printMessage ( "============" );
			Util::printMessage ( "Case Description:" );
			Util::printMessage ( $caze );
			
			//1. Project Leader
			Util::printMessage ( "1. attribute: Project Leader Experience" );
			$c = $caze->projectLeaderExperience;
			$q = $this->query->projectLeaderExperience;
			$w = $caze->w_projectLeaderExperience;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//2. Project Leader Similar Projects
			Util::printMessage ( "2. attribute: Project Leader Similar Projects" );
			$c = $caze->projectLeaderSimilarProjects;
			$q = $this->query->projectLeaderSimilarProjects;
			$w = $caze->w_projectLeaderSimilarProjects;
			$s = $this->buildExponentialSim ( $q, $c, 10 );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//3. Project Leader Success Rate
			Util::printMessage ( "3. attribute: Project Leader Success Rate" );
			$c = $caze->projectLeaderSuccessRate;
			$q = $this->query->projectLeaderSuccessRate;
			$w = $caze->w_projectLeaderSuccessRate;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//4. Project Leader Team Familarity
			Util::printMessage ( "4. attribute: Project Leader Team Familarity" );
			$c = $caze->projectLeaderTeamFamilarity;
			$q = $this->query->projectLeaderTeamFamilarity;
			$w = $caze->w_projectLeaderTeamFamilarity;
			$s = $this->pltfSimArray ['q' . $q . 'c' . $c];
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->pltfSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//5. Customer ID
			Util::printMessage ( "5. attribute: Customer ID" );
			$c = $caze->customerId;
			$q = $this->query->customerId;
			$w = $caze->w_customerId;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//6. Development Process
			Util::printMessage ( "6. attribute: Development Process" );
			$c = $caze->developmentProcess;
			$q = $this->query->developmentProcess;
			$w = $caze->w_developmentProcess;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//7. Internal Flag
			Util::printMessage ( "7. attribute: Internal Flag" );
			$c = $caze->internalFlag;
			$q = $this->query->internalFlag;
			$w = $caze->w_internalFlag;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//8. Priority
			Util::printMessage ( "8. attribute: Priority" );
			$c = $caze->priority;
			$q = $this->query->priority;
			$w = $caze->w_priority;
			$s = $this->prioSimArray ['q' . $q . 'c' . $c];
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->prioSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//9. Project
			Util::printMessage ( "9. attribute: Project" );
			$c = $caze->project;
			$q = $this->query->project;
			$w = $caze->w_project;
			$s = $this->projectSimArray ['q' . $q . 'c' . $c];
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->projectSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//10. Project Novelty
			Util::printMessage ( "10. attribute: Project Novelty" );
			$c = $caze->projectNovelty;
			$q = $this->query->projectNovelty;
			$w = $caze->w_projectNovelty;
			$s = $this->pnSimArray ['q' . $q . 'c' . $c];
			
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->pnSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//11. System Architecture
			Util::printMessage ( "11. attribute: System Architecture" );
			$c = $caze->systemArchitecture;
			$q = $this->query->systemArchitecture;
			$w = $caze->w_systemArchitecture;
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->saSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$s = $this->saSimArray ['q' . $q . 'c' . $c];
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//12. System Criticality
			Util::printMessage ( "12. attribute: System Criticality" );
			$c = $caze->systemCriticality;
			$q = $this->query->systemCriticality;
			$w = $caze->w_systemCriticality;
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->scSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$s = $this->scSimArray ['q' . $q . 'c' . $c];
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//13. System Dependency
			Util::printMessage ( "13. attribute: System Dependency" );
			$c = $caze->systemDependency;
			$q = $this->query->systemDependency;
			$w = $caze->w_systemDependency;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//14. System Operating Mode
			Util::printMessage ( "14. attribute: System Operating Mode" );
			$c = $caze->systemOperatingMode;
			$q = $this->query->systemOperatingMode;
			$w = $caze->w_systemOperatingMode;
			$s = $this->sopSimArray ['q' . $q . 'c' . $c];
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->sopSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//15. System Special Reliability
			Util::printMessage ( "15. attribute: System Special Reliability" );
			$c = $caze->systemSpecialReliability;
			$q = $this->query->systemSpecialReliability;
			$w = $caze->w_systemSpecialReliability;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//16. Team Experience
			Util::printMessage ( "16. attribute: Team Experience" );
			$c = $caze->teamExperience;
			$q = $this->query->teamExperience;
			$w = $caze->w_teamExperience;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//17. Test Kind
			Util::printMessage ( "17. attribute: Test Kind" );
			$c = $caze->testKind;
			$q = $this->query->testKind;
			$s = $this->tkSimArray ['q' . $q . 'c' . $c];
			for($i = 1; $i <= 3; $i ++) {
				for($j = 1; $j <= 3; $j ++) {
					Util::printMessage ( TAB . "if q == " . $i . ",  c == " . $j . ", then y will be " . $this->tkSimArray ['q' . $i . 'c' . $j] );
				}
			}
			$w = $caze->w_testKind;
			$res = $this->weightSimiliarity ( $q, $c, $w, $s );
			$sum = $sum + $res;
			
			//Ende
			Util::printMessage ( "sum: " . $sum );
			$sim = pow ( $sum, 1 / $this->alpha );
			Util::printMessage ( "sum^" . $this->alpha . ": " . $sim );
			$this->simArray [$caze->caseId] = $sim;
			$i ++;
		}
		return $this->simArray;
	}
	private function buildBinarySim($q, $c) {
		Util::printMessage ( TAB . "if " . $q . " == " . $c . ", then y will be 1" );
		if ($q == $c) {
			return 1;
		} else {
			return 0;
		}
	}
	private function buildExponentialSim($q, $c, $t, $a = 0.4) {
		$x = $q - $c;
		$s = - 1;
		Util::printMessage ( TAB . "if f(" . $q . "-" . $c . ") is in the interval of [" . $t . ", " . ($t * - 1) . "], then y will be 1" );
		Util::printMessage ( TAB . "otherwise e^(f(" . $q . "-" . $c . ")*" . $a . ") will be operated" );
		//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
		//bei dem gilt: f(q-c) = 1
		if ($x <= $t && $x >= ($t * - 1)) {
			$s = 1;
		} else {
			//ansonsten nehme eine exponentiale Funktion: e^(((q-c)+t) * a) bzw. e^((-(q-c)+t) * a)
			if ($x > 0) {
				$s = $this->exponential ( (- 1 * $x) + $t, $a );
			} else if ($x < 0) {
				$s = $this->exponential ( $x + $t, $a );
			}
		}
		return $s;
	}
	private function weightSimiliarity($q, $c, $w, $s) {
		$res = - 1;
		Util::printMessage ( TAB . "q = " . $q );
		Util::printMessage ( TAB . "c = " . $c );
		$res = $w * (pow ( $s, $this->alpha ));
		Util::printMessage ( "s = " . $s );
		Util::printMessage ( "w = " . $w );
		Util::printMessage ( "w * s" . $this->alpha . " = " . $res );
		Util::printMessage ( EOL );
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
}