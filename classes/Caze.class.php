<?php
class Caze {
	//Attribute
	public $caseId;
	public $caseReference;
	//Attribute, die fuer CBR notwendig sind
	public $projectLeaderExperience;
	public $projectLeaderSimilarProjects;
	public $projectLeaderSuccessRate;
	public $projectLeaderTeamFamilarity;
	public $customerId;
	public $developmentProcess;
	public $internalFlag;
	public $priority;
	public $project;
	public $projectNovelty;
	public $systemArchitecture;
	public $systemCriticality;
	public $systemDependency;
	public $systemOperatingMode;
	public $systemSpecialReliability;
	public $teamExperience;
	public $testKind;
	//productivity coefficient
	public $productivity;
	public $time;
	public $size;
	public $productivityCoefficient;
	//Gewichte 
	public $w_projectLeaderExperience;
	public $w_projectLeaderSimilarProjects;
	public $w_projectLeaderSuccessRate;
	public $w_projectLeaderTeamFamilarity;
	public $w_customerId;
	public $w_developmentProcess;
	public $w_internalFlag;
	public $w_priority;
	public $w_project;
	public $w_projectNovelty;
	public $w_systemArchitecture;
	public $w_systemCriticality;
	public $w_systemDependency;
	public $w_systemOperatingMode;
	public $w_systemSpecialReliability;
	public $w_teamExperience;
	public $w_testKind;
	function __construct() {
		//GEWICHTE 3
		$this->w_projectLeaderExperience = (3 / 33);
		$this->w_projectLeaderSimilarProjects = (3 / 33);
		$this->w_projectLeaderSuccessRate = (3 / 33);
		$this->w_projectLeaderTeamFamilarity = (3 / 33);
		$this->w_teamExperience = (3 / 33);
		//GEWICHTE 2		
		$this->w_customerId = (2 / 33);
		$this->w_project = (2 / 33);
		$this->w_internalFlag = (2 / 33);
		$this->w_priority = (2 / 33);
		$this->w_testKind = (2 / 33);
		$this->w_projectNovelty = (2 / 33);
		//GEWICHTE 1
		$this->w_systemArchitecture = (1 / 33);
		$this->w_systemCriticality = (1 / 33);
		$this->w_systemDependency = (1 / 33);
		$this->w_systemOperatingMode = (1 / 33);
		$this->w_systemSpecialReliability = (1 / 33);
		$this->w_developmentProcess = (1 / 33);
	}
	public function getSumOfWeights() {
		$sum = $this->w_projectLeaderExperience + $this->w_projectLeaderSimilarProjects + $this->w_projectLeaderSuccessRate + $this->w_projectLeaderTeamFamilarity + $this->w_customerId + $this->w_developmentProcess + $this->w_internalFlag + $this->w_priority + $this->w_project + $this->w_projectNovelty + $this->w_systemArchitecture + $this->w_systemCriticality + $this->w_systemDependency + $this->w_systemOperatingMode + $this->w_systemSpecialReliability + $this->w_teamExperience + $this->w_testKind;
		return $sum;
	}
	function __toString() {
		return $this->caseId;
	}
}