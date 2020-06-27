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

abstract class AbstractCase {

    protected $manufacturerWeight;
    protected $priceWeight;
    protected $inchWeight;
    private   $manufacturer;
    private   $price;
    private   $inch;

    //factory functions

    public static function factory() {
        return new Caze ();
    }

    //functions

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer) {
        if (!is_string($manufacturer)) {
            //TODO Exception Handling
        }
        if (!Util::isEmpty($manufacturer)) {
            $this->manufacturer = $manufacturer;
        } else {
            //TODO Exception Handling
        }
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        if (!is_string($price)) {
            //TODO Exception Handling
        }
        if (!Util::isEmpty($price)) {
            $this->price = $price;
        } else {
            //TODO Exception Handling
        }
    }

    public function getInch() {
        return $this->inch;
    }

    public function setInch($inch) {
        if (!is_string($inch)) {
            //TODO Exception Handling
        }
        if (!Util::isEmpty($inch)) {
            $this->inch = $inch;
        } else {
            //TODO Exception Handling
        }
    }

    //abstract functions

    public abstract function getCaseDescription();

    protected abstract function setManufacturerWeight($weight);

    protected abstract function setPriceWeight($weight);

    protected abstract function setInchWeight($weight);

}
