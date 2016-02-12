<?php
include 'classes/Database.class.php';
include 'classes/Caze.class.php';
include 'classes/Core.class.php';
define ( "EOL", "\n" );
define ( "TAB", "\t" );
$cazeArray = array ();
$nearestValue = 100000000;
$id = - 1;
$db = new Database ();
$showDetails = ! Caze::$showDetails;
$cazes = $db->query ( "SELECT 
carer_experience,
carer_similar_projects,
carer_success_rate,
carer_team_familarity,
customer_id,
development_process,
id,
internal_flag,
priority,
project,
project_novelty,
reference,
system_architecture,
system_criticality,
system_dependency,
system_operating_mode,
system_special_reliability,
team_experience,
test_kind,
time, size FROM caze where id <= 30" );

$sampleCase = getSampleCase ( 33 );
while ( $row = mysqli_fetch_array ( $cazes ) ) {
	$caze = new Caze ();
	$caze->caseId = $row ['id'];
	$caze->caseReference = $row ['reference'];
	$caze->projectLeaderExperience = $row ['carer_experience'];
	$caze->projectLeaderSimilarProjects = $row ['carer_similar_projects'];
	$caze->projectLeaderSuccessRate = $row ['carer_success_rate'];
	$caze->projectLeaderTeamFamilarity = $row ['carer_team_familarity'];
	$caze->customerId = $row ['customer_id'];
	$caze->developmentProcess = $row ['development_process'];
	$caze->internalFlag = $row ['internal_flag'];
	$caze->priority = $row ['priority'];
	$caze->project = $row ['project'];
	$caze->projectNovelty = $row ['project_novelty'];
	$caze->systemArchitecture = $row ['system_architecture'];
	$caze->systemCriticality = $row ['system_criticality'];
	$caze->systemDependency = $row ['system_dependency'];
	$caze->systemOperatingMode = $row ['system_operating_mode'];
	$caze->systemSpecialReliability = $row ['system_special_reliability'];
	$caze->teamExperience = $row ['team_experience'];
	$caze->testKind = $row ['test_kind'];
	$caze->time = $row ['time'];
	$caze->size = $row ['size'];
	$caze->productivity = $caze->size / $caze->time;
	$avgProductivity = getAverageProductivity ( $caze->caseId );
	$caze->productivityCoefficient = $caze->productivity / $avgProductivity;
	// 	print_message ( "sim(" . $caze->caseId . "): " . $abs );
	// 	print_message ( "PC(" . $caze->caseId . "): " . $caze->productivityCoefficient );
	// 	print_message ( "productivity(" . $caze->caseId . "): " . $caze->productivity );
	// 	if ($abs < $nearestValue) {
	// 		$nearestValue = $abs;
	// 		$id = $caze->caseId;
	// 		$productivityCoefficient = $caze->productivityCoefficient;
	// 	}
	$cazeArray [] = $caze;
}
// print_message ( "nearest Case: " . $id );
// print_message ( "productivity Coefficient: " . $productivityCoefficient );
// $averageProductivity = getAverageProductivity ();
// print_message ( "avergae Productivity: " . $averageProductivity );
// print_message ( "Productivity Forecast: " . $averageProductivity * $productivityCoefficient );
$core = new Core ( $cazeArray, $sampleCase );
$core->getSimilarity ();
function getSampleCase($id) {
	if (trim ( $id ) == "" || ! is_numeric ( $id )) {
		return false;
	}
	global $db;
	$caze = new Caze ();
	$query = "select * from caze where id = " . $id;
	$result = $db->query ( $query );
	while ( $row = mysqli_fetch_array ( $result ) ) {
		$caze->caseId = $row ['id'];
		$caze->caseReference = $row ['reference'];
		$caze->projectLeaderExperience = $row ['carer_experience'];
		$caze->projectLeaderSimilarProjects = $row ['carer_similar_projects'];
		$caze->projectLeaderSuccessRate = $row ['carer_success_rate'];
		$caze->projectLeaderTeamFamilarity = $row ['carer_team_familarity'];
		$caze->customerId = $row ['customer_id'];
		$caze->developmentProcess = $row ['development_process'];
		$caze->internalFlag = $row ['internal_flag'];
		$caze->priority = $row ['priority'];
		$caze->project = $row ['project'];
		$caze->projectNovelty = $row ['project_novelty'];
		$caze->systemArchitecture = $row ['system_architecture'];
		$caze->systemCriticality = $row ['system_criticality'];
		$caze->systemDependency = $row ['system_dependency'];
		$caze->systemOperatingMode = $row ['system_operating_mode'];
		$caze->systemSpecialReliability = $row ['system_special_reliability'];
		$caze->teamExperience = $row ['team_experience'];
		$caze->teamProjectExperience = $row ['team_project_experience'];
		$caze->testKind = $row ['test_kind'];
		$caze->time = $row ['time'];
		$caze->size = $row ['size'];
		$caze->productivity = $caze->size / $caze->time;
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
	$result = $db->query ( $query );
	$avgProductivity = - 1;
	while ( $row = mysqli_fetch_row ( $result ) ) {
		$avgProductivity = $row [0];
	}
	return $avgProductivity;
}
function print_message($message) {
	global $showDetails;
	if ($showDetails) {
		echo $message . EOL;
	}
}

?>