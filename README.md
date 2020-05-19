# Result-types
Implementation of a result monad for php

The abstract Result class is the parent for all type-specific results. The following Result classes are provided:

- ArrayResult
- BooleanResult
- IntegerResult
- StringResult

### `success(<parameter of expected type>)`

Static method used in lieu of constructor to create a Result object that represents a success.

Use a `Result` object in place of the primitive type to indicate success. The type of the parameter to the `success()` method is always corresponding primitive type. For example, 

- `ArrayResult::success($arr)` always takes an array.
- `StringResult::success($str)` always takes a string.

Passing an unexpected value to the `success()` method will throw an exception:

> Type mismatch: expected [name of expected type]; found [name of actual type]

### `failure(string $str)` 

Static method used in lieu of constructor to create a Result object that represents a failure.

The parameter of the `failure()` method is always an error string. 

### `value()`

If the Result object represents a success, `value()` returns the type-specific value. 

If the Result object represents a failure, `value()` returns an error string.

### `isSuccess()`

Returns `true` if the Result object represents a success. Returns `false` if the Result object represents a failure.

### `isFailure()`

Returns `true` if the Result object represents a failure. Returns `false` if the Result object represents a success.

## Example

```
$result = trySomething("Some Thing");
if ($result->isSuccess()) {
  echo "Happy day! " . $result->value(); 
} else {
  echo "Alas. " . $result->value();
}

function trySomething(string $something): StringResult
{
  if ($something !== '') {
    return StringResult::success($something . " was tried!");
  } else {
    return StringResult::failure("There was nothing to try, alas.");
  }
}
```

## Nonprimitive objects

Other Result classes can be declared for nonprimitive, user-defined classes. 

Given an arbitrary class, TestObject, defined as

```
class TestObject {
  private $value;
  
  public function declareMyself()
  {
    return "I am a test object!";
  }
}
```

An object-specific Result class is created by simply inheriting from the parent Result class.

```
class TestObjectResult extends Result {}
```

### Example

```
$test_object = new TestObject();
$result = TestObjectResult::success($test_object);

if ($result->isSuccess()) {
  $returned_object = $result->value();
  echo "$returned_object->declareMyself();
}
```

## Inspiration for this implementation was found here:

- [Railway Oriented Programming](https://fsharpforfunandprofit.com/rop/)
- [The power of Result types in Swift](https://www.swiftbysundell.com/articles/the-power-of-result-types-in-swift/)
- [kotlin-result](https://github.com/michaelbull/kotlin-result)
