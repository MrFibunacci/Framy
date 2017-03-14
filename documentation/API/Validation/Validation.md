# \app\framework\Component\Validation\Validation
class **Validation**

Validation component main class

## Traits
SingletonTrait

## Methods Overview
   
```
void  | validate(mixed $data, string|array $validators, bool|true $throw = true)
$this | addValidator(ValidatorInterface $validator)
void  | init()
Array | getValidators(String|Array $validators)
```

## Details

### -void validate(mixed $data, string|array $validators, bool|true $throw = true)

#### Parameters
```
mixed        | $data       | The data to Validate 
string|array | $validators | The name or a list of names, in an array, of the validator/s 
bool|true    | $throw      | If false the message gets returned as String, true it gets Thrown
```

#### Return Value
```
throw  | ValidationException if Validator doesn't exist
String | Message if validation failed and we are not throwing, return error message string
throw  | ValidationException if validation failed
```

### -addValidator(ValidatorInterface $validator)

#### Parameters
```
ValidatorInterface | $validator | 
```