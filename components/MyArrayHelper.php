<?php

namespace app\components;

use yii\base\Component;

class MyArrayHelper extends Component
{
	public static function map($array, $from, $to, $delimiter = ' ')
	{
		$result = [];
		foreach ($array as $element) {
			$key = static::getValue($element, $from);
			$value = '';
			foreach ($to as $item) {
				$value .= static::getValue($element, $item) . $delimiter;
			}
			$result[$key] = $value;
		}

		return $result;
	}

	public static function getValue($array, $key, $default = null)
	{
		if ($key instanceof \Closure) {
			return $key($array, $default);
		}

		if (is_array($key)) {
			$lastKey = array_pop($key);
			foreach ($key as $keyPart) {
				$array = static::getValue($array, $keyPart);
			}
			$key = $lastKey;
		}

		if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
			return $array[$key];
		}

		if (($pos = strrpos($key, '.')) !== false) {
			$array = static::getValue($array, substr($key, 0, $pos), $default);
			$key = substr($key, $pos + 1);
		}

		if (is_object($array)) {
			// this is expected to fail if the property does not exist, or __get() is not implemented
			// it is not reliably possible to check whether a property is accessible beforehand
			return $array->$key;
		} elseif (is_array($array)) {
			return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
		}

		return $default;
	}
}