<?php

class Result
{
	const key = 'resu1t';
	protected $success = null;
	protected $failure = null;

	public function __construct($success, $failure, string $key)
	{
		if ($key !== Result::key . date('Ymd')) {
			throw new Exception("Result object cannot be instantiated.");
		}
		$this->success = $success;
		if ($failure !== null) $this->failure[] = $failure;
	}

	public function isSuccess(): bool
	{
		return ($this->success !== null);
	}

	public function isFailure(): bool
	{
		return isset($this->failure) && (sizeof($this->failure) > 0);
	}

	public static function success($success): Result
	{
		//	type check
		$class = get_called_class(); // <type>Result
		$type = gettype($success);	 // primitive type
		if ($type === 'object') {
			$type = get_class($success); // class name of nonprimitive type
		}
		$typename = str_replace('Result', '', $class);
		if (strtolower($typename) !== $type && $typename !== $type) {
			throw new Exception("Type mismatch: expected $typename; found $type");
		}

		// got right type for this result object; create it
		return new $class($success, null, self::key . date('Ymd'));
	}

	public static function failure(string $failure = ''): Result
	{
		$class = get_called_class();
		return new $class(null, $failure, self::key . date('Ymd'));
	}

	public function value()
	{
		if ($this->isSuccess()) {
			return $this->success;
		} else {
			return implode("\n", $this->failure);
		}
	}
}

class ArrayResult extends Result
{
}

class BooleanResult extends Result
{
}

class IntegerResult extends Result
{
}

class StringResult extends Result
{
}
