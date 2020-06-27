<?php
/**
 *  Copyright (C) <2016>  <Dogan Ucar>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Core {

    private $cazeArray       = null;
    private $weightsArray    = null;
    private $query           = null;
    private $alpha           = 1;
    private $simArray        = array();
    private $pltfSimArray    = array();
    private $prioSimArray    = array();
    private $projectSimArray = array();
    private $pnSimArray      = array();
    private $saSimArray      = array();
    private $scSimArray      = array();
    private $sopSimArray     = array();
    private $tkSimArray      = array();

    function __construct($cazeArray, $query) {
        if (is_array($cazeArray)) {
            foreach ($cazeArray as $singleCaze) {
                if (!$singleCaze instanceof Caze) {
                    die ("Class Core: paramter cazearray has to be object type array/Caze");
                }
            }
        }
        if (!$query instanceof Caze) {
            die ("Class Core: paramter query has to be object type Caze");
        }
        $this->cazeArray = $cazeArray;
        $this->query     = $query;

        // 4. PLTF Similarity Array
        $this->pltfSimArray = $this->generateSimilarityArray(3);

        // 8. Priority Similarity Array
        $this->prioSimArray = $this->generateSimilarityArray(4);

        // 9. Project Similarity Array
        $this->projectSimArray = $this->generateSimilarityArray(10);

        // 10. PN Similarity Array
        $this->pnSimArray = $this->generateSimilarityArray(3);

        // 11. SA Similarity Array
        $this->saSimArray = $this->generateSimilarityArray(4);

        // 12. SC Similarity Array
        $this->scSimArray = $this->generateSimilarityArray(3);

        // 14. SC Similarity Array
        $this->sopSimArray = $this->generateSimilarityArray(3);

        // 17. TK Similarity Array
        $this->tkSimArray = $this->generateSimilarityArray(4);
    }

    private function generateSimilarityArray($number) {
        $simArray = array();
        for ($i = 1; $i <= $number; $i++) {
            for ($j = 1; $j <= $number; $j++) {
                $simArray ['q' . $i . 'c' . $j] = 1 - (abs($i - $j) / $number);
            }
        }
        return $simArray;
    }

    public function getSimilarity() {
        foreach ($this->cazeArray as $caze) {
            $sum = 0;

            // 1. Project Leader
            $c   = $caze->projectLeaderExperience;
            $q   = $this->query->projectLeaderExperience;
            $w   = $caze->w_projectLeaderExperience;
            $s   = $this->buildExponentialSim($q, $c, 20);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 2. Project Leader Similar Projects
            $c   = $caze->projectLeaderSimilarProjects;
            $q   = $this->query->projectLeaderSimilarProjects;
            $w   = $caze->w_projectLeaderSimilarProjects;
            $s   = $this->buildExponentialSim($q, $c, 10);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 3. Project Leader Success Rate
            $c   = $caze->projectLeaderSuccessRate;
            $q   = $this->query->projectLeaderSuccessRate;
            $w   = $caze->w_projectLeaderSuccessRate;
            $s   = $this->buildExponentialSim($q, $c, 20);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 4. Project Leader Team Familarity
            $c   = $caze->projectLeaderTeamFamilarity;
            $q   = $this->query->projectLeaderTeamFamilarity;
            $w   = $caze->w_projectLeaderTeamFamilarity;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->pltfSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 5. Customer ID
            $c   = $caze->customerId;
            $q   = $this->query->customerId;
            $w   = $caze->w_customerId;
            $s   = $this->buildBinarySim($q, $c);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 6. Development Process
            $c   = $caze->developmentProcess;
            $q   = $this->query->developmentProcess;
            $w   = $caze->w_developmentProcess;
            $s   = $this->buildBinarySim($q, $c);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 7. Internal Flag
            $c   = $caze->internalFlag;
            $q   = $this->query->internalFlag;
            $w   = $caze->w_internalFlag;
            $s   = $this->buildBinarySim($q, $c);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 8. Priority
            $c   = $caze->priority;
            $q   = $this->query->priority;
            $w   = $caze->w_priority;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->prioSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 9. Project
            $c   = $caze->project;
            $q   = $this->query->project;
            $w   = $caze->w_project;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->projectSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 10. Project Novelty
            $c   = $caze->projectNovelty;
            $q   = $this->query->projectNovelty;
            $w   = $caze->w_projectNovelty;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->pnSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 11. System Architecture
            $c   = $caze->systemArchitecture;
            $q   = $this->query->systemArchitecture;
            $w   = $caze->w_systemArchitecture;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->saSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 12. System Criticality
            $c   = $caze->systemCriticality;
            $q   = $this->query->systemCriticality;
            $w   = $caze->w_systemCriticality;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->scSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 13. System Dependency
            $c   = $caze->systemDependency;
            $q   = $this->query->systemDependency;
            $w   = $caze->w_systemDependency;
            $s   = $this->buildBinarySim($q, $c);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 14. System Operating Mode
            $c   = $caze->systemOperatingMode;
            $q   = $this->query->systemOperatingMode;
            $w   = $caze->w_systemOperatingMode;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->sopSimArray);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 15. System Special Reliability
            $c   = $caze->systemSpecialReliability;
            $q   = $this->query->systemSpecialReliability;
            $w   = $caze->w_systemSpecialReliability;
            $s   = $this->buildBinarySim($q, $c);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 16. Team Experience
            $c   = $caze->teamExperience;
            $q   = $this->query->teamExperience;
            $w   = $caze->w_teamExperience;
            $s   = $this->buildExponentialSim($q, $c, 20);
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // 17. Test Kind
            $c   = $caze->testKind;
            $q   = $this->query->testKind;
            $s   = $this->buildSimilarityTableSim($q, $c, $this->tkSimArray);
            $w   = $caze->w_testKind;
            $res = $this->weightSimiliarity($w, $s);
            $sum = $sum + $res;

            // Ende
            $sim                            = pow($sum, 1 / $this->alpha);
            $this->simArray [$caze->caseId] = $sim;
        }
        return $this->simArray;
    }

    private function buildExponentialSim($q, $c, $t, $a = 0.4) {
        $x = $q - $c;
        $s = -1;
        // zunaechst pruefen, ob x in einem akzeptablen bereich ist,
        // bei dem gilt: f(q-c) = 1
        if ($x <= $t && $x >= ($t * -1)) {
            $s = 1;
        } else {
            // ansonsten nehme eine exponentiale Funktion: e^(((q-c)+t) * a) bzw. e^((-(q-c)+t) * a)
            $m = 1;
            if ($x > 0) {
                $m = -1;
            }
            $tmp = ($m * $x) + $t;
            $s   = $this->exponential($tmp, $a);
        }
        return $s;
    }

    private function exponential($x, $a = 1) {
        return exp(($x * $a));
    }

    private function weightSimiliarity($w, $s) {
        $res = -1;
        $res = $w * (pow($s, $this->alpha));
        return $res;
    }

    private function buildSimilarityTableSim($q, $c, $array) {
        $d      = sizeof($array);
        $sizeof = -1;
        if ($d == 9) {
            $sizeof = 3;
        } else {
            if ($d == 16) {
                $sizeof = 4;
            } else {
                if ($d == 100) {
                    $sizeof = 10;
                } else {
                    echo "Fehler: kann Groesse von array nicht ermitteln!!";
                    return false;
                }
            }
        }
        $s = $array ['q' . $q . 'c' . $c];
        return $s;
    }

    private function buildBinarySim($q, $c) {
        if ($q == $c) {
            return 1;
        } else {
            return 0;
        }
    }

    private function threshold($x) {
        if ($x < 0) {
            return 1;
        } else {
            if ($x >= 0) {
                return 0;
            } else {
                return false;
            }
        }
    }

    private function linear($min, $max, $x) {
        if ($x < $min) {
            return 1;
        } else {
            if ($x > $max) {
                return 0;
            } else {
                if ($x >= $max && $x <= $min) {
                    $return = ($max - $x) / ($max - $min);
                } else {
                    return false;
                }
            }
        }
    }

    private function sigmoid($x, $a) {
        $exp = $this->exponential(($x - 0 / $a));
        $i   = $exp + 1;
        return 1 / $i;
    }

    private function checkNumeric($value) {
        if ($value == "") {
            return false;
        } else {
            if (empty ($value)) {
                return false;
            } else {
                if (!is_numeric($value)) {
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

}
