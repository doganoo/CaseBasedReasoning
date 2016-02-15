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
					die ( "Class Core: paramter cazearray has to be object type array/Caze" );
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
		Util::printMessage ( "Query Description - Case ID: " . $this->query->caseId );
		foreach ( $this->cazeArray as $caze ) {
			$sum = 0;
			Util::printMessage ( "Case Description - Case ID: " . $caze->caseId );
			Util::printMessage ( EOL );
			
			//1. Project Leader
			Util::printMessage ( "1. Attribut: Project Leader Experience" );
			Util::printMessage ( "======================================" );
			$c = $caze->projectLeaderExperience;
			$q = $this->query->projectLeaderExperience;
			$w = $caze->w_projectLeaderExperience;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "======================================" );
			Util::printMessage ( EOL );
			
			//2. Project Leader Similar Projects
			Util::printMessage ( "2. Attribut: Project Leader Similar Projects" );
			Util::printMessage ( "============================================" );
			$c = $caze->projectLeaderSimilarProjects;
			$q = $this->query->projectLeaderSimilarProjects;
			$w = $caze->w_projectLeaderSimilarProjects;
			$s = $this->buildExponentialSim ( $q, $c, 10 );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "============================================" );
			Util::printMessage ( EOL );
			
			//3. Project Leader Success Rate
			Util::printMessage ( "3. Attribut: Project Leader Success Rate" );
			Util::printMessage ( "============================================" );
			$c = $caze->projectLeaderSuccessRate;
			$q = $this->query->projectLeaderSuccessRate;
			$w = $caze->w_projectLeaderSuccessRate;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "============================================" );
			Util::printMessage ( EOL );
			
			//4. Project Leader Team Familarity
			Util::printMessage ( "4. Attribut: Project Leader Team Familarity" );
			Util::printMessage ( "============================================" );
			$c = $caze->projectLeaderTeamFamilarity;
			$q = $this->query->projectLeaderTeamFamilarity;
			$w = $caze->w_projectLeaderTeamFamilarity;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->pltfSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "============================================" );
			Util::printMessage ( EOL );
			
			//5. Customer ID
			Util::printMessage ( "5. Attribut: Customer ID" );
			Util::printMessage ( "==========================" );
			$c = $caze->customerId;
			$q = $this->query->customerId;
			$w = $caze->w_customerId;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "==========================" );
			Util::printMessage ( EOL );
			
			//6. Development Process
			Util::printMessage ( "6. Attribut: Development Process" );
			Util::printMessage ( "================================" );
			$c = $caze->developmentProcess;
			$q = $this->query->developmentProcess;
			$w = $caze->w_developmentProcess;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "================================" );
			Util::printMessage ( EOL );
			
			//7. Internal Flag
			Util::printMessage ( "7. Attribut: Internal Flag" );
			Util::printMessage ( "===========================" );
			$c = $caze->internalFlag;
			$q = $this->query->internalFlag;
			$w = $caze->w_internalFlag;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "===========================" );
			Util::printMessage ( EOL );
			
			//8. Priority
			Util::printMessage ( "8. Attribut: Priority" );
			Util::printMessage ( "=====================" );
			$c = $caze->priority;
			$q = $this->query->priority;
			$w = $caze->w_priority;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->prioSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=====================" );
			Util::printMessage ( EOL );
			
			//9. Project
			Util::printMessage ( "9. Attribut: Project" );
			Util::printMessage ( "=====================" );
			$c = $caze->project;
			$q = $this->query->project;
			$w = $caze->w_project;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->projectSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=====================" );
			Util::printMessage ( EOL );
			
			//10. Project Novelty
			Util::printMessage ( "10. Attribut: Project Novelty" );
			Util::printMessage ( "=============================" );
			$c = $caze->projectNovelty;
			$q = $this->query->projectNovelty;
			$w = $caze->w_projectNovelty;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->pnSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=============================" );
			Util::printMessage ( EOL );
			
			//11. System Architecture
			Util::printMessage ( "11. Attribut: System Architecture" );
			Util::printMessage ( "=================================" );
			$c = $caze->systemArchitecture;
			$q = $this->query->systemArchitecture;
			$w = $caze->w_systemArchitecture;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->saSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=================================" );
			Util::printMessage ( EOL );
			
			//12. System Criticality
			Util::printMessage ( "12. Attribut: System Criticality" );
			Util::printMessage ( "=================================" );
			$c = $caze->systemCriticality;
			$q = $this->query->systemCriticality;
			$w = $caze->w_systemCriticality;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->scSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=================================" );
			Util::printMessage ( EOL );
			
			//13. System Dependency
			Util::printMessage ( "13. Attribut: System Dependency" );
			Util::printMessage ( "================================" );
			$c = $caze->systemDependency;
			$q = $this->query->systemDependency;
			$w = $caze->w_systemDependency;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "================================" );
			Util::printMessage ( EOL );
			
			//14. System Operating Mode
			Util::printMessage ( "14. Attribut: System Operating Mode" );
			Util::printMessage ( "===================================" );
			$c = $caze->systemOperatingMode;
			$q = $this->query->systemOperatingMode;
			$w = $caze->w_systemOperatingMode;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->sopSimArray );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "===================================" );
			Util::printMessage ( EOL );
			
			//15. System Special Reliability
			Util::printMessage ( "15. Attribut: System Special Reliability" );
			Util::printMessage ( "========================================" );
			$c = $caze->systemSpecialReliability;
			$q = $this->query->systemSpecialReliability;
			$w = $caze->w_systemSpecialReliability;
			$s = $this->buildBinarySim ( $q, $c );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "========================================" );
			Util::printMessage ( EOL );
			
			//16. Team Experience
			Util::printMessage ( "16. Attribut: Team Experience" );
			Util::printMessage ( "=============================" );
			$c = $caze->teamExperience;
			$q = $this->query->teamExperience;
			$w = $caze->w_teamExperience;
			$s = $this->buildExponentialSim ( $q, $c, 20 );
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=============================" );
			Util::printMessage ( EOL );
			//17. Test Kind
			Util::printMessage ( "17. Attribut: Test Kind" );
			Util::printMessage ( "=======================" );
			$c = $caze->testKind;
			$q = $this->query->testKind;
			$s = $this->buildSimilarityTableSim ( $q, $c, $this->tkSimArray );
			$w = $caze->w_testKind;
			$res = $this->weightSimiliarity ( $w, $s );
			$sum = $sum + $res;
			Util::printMessage ( "=======================" );
			Util::printMessage ( EOL );
			
			//Ende
			Util::printMessage ( "==============================================" );
			Util::printMessage ( "+" . TAB . "Summe aller s_i * w_i: " . TAB . TAB . $sum );
			$sim = pow ( $sum, 1 / $this->alpha );
			Util::printMessage ( "+" . TAB . "Summe aller (s_i * w_i)^1/" . $this->alpha . ": " . TAB . $sim );
			Util::printMessage ( "==============================================" );
			$this->simArray [$caze->caseId] = $sim;
		}
		return $this->simArray;
	}
	private function buildBinarySim($q, $c) {
		Util::printMessage ( TAB . "q = " . $q );
		Util::printMessage ( TAB . "c = " . $c );
		Util::printMessage ( TAB . "wenn q == c, dann y = 1, andernfalls y = 0" );
		if ($q == $c) {
			return 1;
		} else {
			return 0;
		}
	}
	private function buildSimilarityTableSim($q, $c, $array) {
		$d = sizeof ( $array );
		$sizeof = - 1;
		if ($d == 9) {
			$sizeof = 3;
		} else if ($d == 16) {
			$sizeof = 4;
		} else if ($d == 100) {
			$sizeof = 10;
		} else {
			echo "Fehler: kann Groesse von array nicht ermitteln!!";
			return false;
		}
		Util::printMessage ( TAB . "q = " . $q );
		Util::printMessage ( TAB . "c = " . $c );
		$s = $array ['q' . $q . 'c' . $c];
		for($i = 1; $i <= $sizeof; $i ++) {
			for($j = 1; $j <= $sizeof; $j ++) {
				Util::printMessage ( TAB . "wenn q == " . $i . " und  c == " . $j . ", dann y = " . $array ['q' . $i . 'c' . $j] );
			}
		}
		return $s;
	}
	private function buildExponentialSim($q, $c, $t, $a = 0.4) {
		$x = $q - $c;
		$s = - 1;
		Util::printMessage ( TAB . "wenn f(" . $q . "-" . $c . ") in dem Intervall [" . $t . ", " . ($t * - 1) . "] ist, dann ist y = 1" );
		Util::printMessage ( TAB . "andernfalls wird die Formel: e^((f(" . $q . "-" . $c . ") + " . $t . ") * " . $a . ") ausgefuehrt" );
		//zunaechst pruefen, ob x in einem akzeptablen bereich ist,
		//bei dem gilt: f(q-c) = 1
		if ($x <= $t && $x >= ($t * - 1)) {
			$s = 1;
		} else {
			//ansonsten nehme eine exponentiale Funktion: e^(((q-c)+t) * a) bzw. e^((-(q-c)+t) * a)
			$m = 1;
			if ($x > 0) {
				$m = - 1;
			}
			Util::printMessage ( TAB . "m = " . $m . ": falls m negativ, dann wird die Exponentialfunktion an der y-Achse gespiegelt" );
			Util::printMessage ( TAB . "(f(" . $q . " - " . $c . ") * " . $m . ") = " . $x * $m );
			$tmp = ($m * $x) + $t;
			Util::printMessage ( TAB . "((f(" . $q . " - " . $c . ") * " . $m . ") + " . $t . ") = " . $tmp );
			$s = $this->exponential ( $tmp, $a );
			Util::printMessage ( TAB . "e^(((f(" . $q . " - " . $c . ") * " . $m . ") + " . $t . ") * " . $a . ") = " . $s );
		}
		return $s;
	}
	private function weightSimiliarity($w, $s) {
		$res = - 1;
		$res = $w * (pow ( $s, $this->alpha ));
		Util::printMessage ( TAB . "s = " . $s );
		Util::printMessage ( TAB . "w = " . $w );
		Util::printMessage ( TAB . "w * s^" . $this->alpha . " = " . $res );
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