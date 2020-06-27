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

class Caze extends AbstractCase {

    public function __construct() {
        $this->setInch("13");
        $this->setManufacturer("Apple");
        $this->setPrice("1499");
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
