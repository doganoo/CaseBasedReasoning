<?php
class Caze extends AbstractCase {
	public function __construct() {
		$this->setInch ( "13" );
		$this->setManufacturer ( "Apple" );
		$this->setPrice ( "1499" );
	}
	public function getCaseDescription() {
		return "eine einfache Caze-Klasse";
	}
	protected function setManufacturerWeight($weight) {
		$this->manufacturerWeight = $weight;
	}
	protected function setPriceWeight($weight) {
		$this->priceWeight = $weight;
	}
	protected function setInchWeight($weight) {
		$this->inchWeight = $weight;
	}
}