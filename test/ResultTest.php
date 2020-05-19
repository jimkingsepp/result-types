<?php

use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
	/** @test */
	public function test_BooleanResult()
	{
		$true_result = BooleanResult::success(true);
		$this->assertTrue($true_result->isSuccess());
		$this->assertFalse($true_result->isFailure());
		$this->assertSame($true_result->value(), true);

		$false_result = BooleanResult::failure();
		$this->assertTrue($false_result->isFailure());
		$this->assertFalse($false_result->isSuccess());
		$this->assertSame($false_result->value(), '');
		$this->assertNotSame($false_result->value(), false);
	}

	public function test_ArrayResult()
	{
		$test_array = [1, 2, 3, 4, 5];
		$result = ArrayResult::success($test_array);

		$this->assertFalse($result->isFailure());
		$this->assertNotSame($result->value(), '');
		$this->assertTrue($result->isSuccess());
		$this->assertSame($result->value(), $test_array);
	}

	public function test_StringResult()
	{
		$test_string = "the quick brown fox";
		$result = StringResult::success($test_string);

		$this->assertFalse($result->isFailure());
		$this->assertNotSame($result->value(), '');
		$this->assertTrue($result->isSuccess());
		$this->assertSame($result->value(), $test_string);
	}

	public function test_IntegerResult()
	{
		$incr = function (int $i = 0, int $limit): IntegerResult {
			if ($i >= $limit) return IntegerResult::failure("$i is greater than $limit");
			return IntegerResult::success(++$i);
		};

		$idx = 0;
		while (true) {
			$result = $incr($idx, $limit = 200);
			if ($result->isFailure()) break;
			$idx = $result->value();
		}
		$this->assertSame($idx, $limit);
		$this->assertSame($result->isFailure(), true);
	}

	public function test_all()
	{
		$result = BooleanResult::success(true);
		$this->assertSame(true, $result->isSuccess());
		$this->assertSame(false, $result->isFailure());
		$this->assertSame(true, $result->value());
		$result = BooleanResult::failure("boolean test failed");
		$this->assertSame(false, $result->isSuccess());
		$this->assertSame(true, $result->isFailure());
		$this->assertSame("boolean test failed", $result->value());

		$int = 5;
		$result = IntegerResult::success(++$int);
		$this->assertSame(true, $result->isSuccess());
		$this->assertSame(false, $result->isFailure());
		$this->assertSame(6, $result->value());
		$result = IntegerResult::failure("integer test failed");
		$this->assertSame(false, $result->isSuccess());
		$this->assertSame(true, $result->isFailure());
		$this->assertSame("integer test failed", $result->value());

		$result = ArrayResult::success([1, 2, 3, 4]);
		$this->assertSame(true, $result->isSuccess());
		$this->assertSame(false, $result->isFailure());
		$this->assertSame([1, 2, 3, 4], $result->value());
		$result = ArrayResult::failure("array test failed");
		$this->assertSame(false, $result->isSuccess());
		$this->assertSame(true, $result->isFailure());
		$this->assertSame("array test failed", $result->value());

		$result = StringResult::success("success");
		$this->assertSame(true, $result->isSuccess());
		$this->assertSame(false, $result->isFailure());
		$this->assertSame("success", $result->value());
		$result = StringResult::failure("string test failed");
		$this->assertSame(false, $result->isSuccess());
		$this->assertSame(true, $result->isFailure());
		$this->assertSame("string test failed", $result->value());


		$this->expectExceptionMessage("Type mismatch: expected Boolean; found string");
		BooleanResult::success("true");
		
		$object = new TestObject();
		$result = TestObjectResult::success($object);
		$this->assertSame("I am a Test Object!", $result->value());
		
		$this->expectExceptionMessage("Type mismatch: expected TestObject; found string");
		TestObjectResult::success("Test Object");
	}
}

class TestObject {
	public function declareMyself()
	{
		return "I am a Test Object!";
	}
}

class TestObjectResult extends Result {}
