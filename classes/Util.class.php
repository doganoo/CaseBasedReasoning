<?php
class Util {
	private static $SHOW_DETAILS = true;
	private static $SHOW_RESULTS = true;
	public static function printMessage($message) {
		if (Util::$SHOW_DETAILS) {
			echo $message . EOL;
		}
	}
	public static function printMezzage($message) {
		if (Util::$SHOW_RESULTS) {
			echo $message . EOL;
		}
	}
	public static function getNearestValue($array) {
		$myNumber = - 1;
		$idx = false;
		foreach ( $array as $key => $value ) {
			if ($value > $myNumber) {
				$myNumber = $value;
				$idx = $key;
			}
		}
		return $idx;
	}
}