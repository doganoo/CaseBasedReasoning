<?php
//TODO no hardcoded path
include 'classes/abstract/AbstractCase.class.php';
include 'classes/Caze.class.php';
include 'classes/Util.class.php';

$case = AbstractCase::factory ();
echo $case->getCaseDescription ();