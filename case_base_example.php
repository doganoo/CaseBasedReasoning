<?php
//includes
include 'classes/Database.class.php';
include 'classes/Caze.class.php';
include 'classes/Core.class.php';
include 'classes/Util.class.php';

//variables
$db = new Database ();
$core = null;

//constants
define ( "EOL", "\n" );
define ( "TAB", "\t" );

//get case base
$cazeArray = getCazes ();
//get sample case by id
$sampleCase = getSingleCaze ( 33 );
//initializing the core
$core = new Core ( $cazeArray, $sampleCase );
//calculate similarities
$simArray = $core->getSimilarity ();
//get the nearest neighbor 
$nearestId = Util::getNearestNeighbor ( $simArray );
//load neighbor case
$nCaze = getSingleCaze ( $nearestId );
$nCazeProductivityCoefficient = $nCaze->productivityCoefficient;
print_r ( $simArray );
//calculating Productivity Coefficient
//loading average productivity
$avgCBProductivity = getAverageProductivity ();
//printing the results
Util::printMezzage ( "Nearest Neighbor: " . $nCaze->caseId );
// Util::printMezzage ( "NC Productivity: " . $nCazeProductivityCoefficient );
// Util::printMezzage ( "AVG CB Productivity: " . $avgCBProductivity );
//close database
$db->close ();

//functions
function getCazes($number = 30) {
	$cazeArray = array ();
	$c = new Caze ();
	for($i = 1; $i <= $number; $i ++) {
		$c = getSingleCaze ( $i );
		$cazeArray [$i] = $c;
	}
	return $cazeArray;
}
function getSingleCaze($id) {
	if (trim ( $id ) == "" || ! is_numeric ( $id )) {
		return false;
	}
	global $db;
	$caze = new Caze ();
	$query = "select * from caze where id = " . $id;
	$result = $db->query ( $query );
	while ( $array = $db->getMysqliArray ( MYSQL_ASSOC ) ) {
		$caze->caseId = $array ['id'];
		$caze->caseReference = $array ['reference'];
		$caze->projectLeaderExperience = $array ['carer_experience'];
		$caze->projectLeaderSimilarProjects = $array ['carer_similar_projects'];
		$caze->projectLeaderSuccessRate = $array ['carer_success_rate'];
		$caze->projectLeaderTeamFamilarity = $array ['carer_team_familarity'];
		$caze->customerId = $array ['customer_id'];
		$caze->developmentProcess = $array ['development_process'];
		$caze->internalFlag = $array ['internal_flag'];
		$caze->priority = $array ['priority'];
		$caze->project = $array ['project'];
		$caze->projectNovelty = $array ['project_novelty'];
		$caze->systemArchitecture = $array ['system_architecture'];
		$caze->systemCriticality = $array ['system_criticality'];
		$caze->systemDependency = $array ['system_dependency'];
		$caze->systemOperatingMode = $array ['system_operating_mode'];
		$caze->systemSpecialReliability = $array ['system_special_reliability'];
		$caze->teamExperience = $array ['team_experience'];
		$caze->teamProjectExperience = $array ['team_project_experience'];
		$caze->testKind = $array ['test_kind'];
		$time = $array ['time'];
		$size = $array ['size'];
		$caze->time = $time;
		$caze->size = $size;
		$productivity = $size / $time;
		$caze->productivity = $productivity;
		$averageProductivity = getAverageProductivity ( $caze->caseId );
		$productivityCoefficient = $productivity / $averageProductivity;
		$caze->productivityCoefficient = $productivityCoefficient;
	}
	return $caze;
}
function getAverageProductivity($id = "") {
	global $db;
	if ($id != "" && is_numeric ( $id )) {
		$where = " where id <= " . $id;
	} else {
		$where = " where id <= 30";
	}
	$query = "select sum(size/caze.time)/count(size/caze.time) from caze " . $where;
	$db->query ( $query );
	$avgProductivity = - 1;
	while ( $row = $db->getMysqliArray () ) {
		$avgProductivity = $row [0];
	}
	return $avgProductivity;
}

?>