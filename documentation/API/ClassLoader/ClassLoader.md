# \app\framework\Component\ClassLoader\ClassLoader
Class **ClassLoader** 

## Methods Overview

```
void    register()
bool    getClass(String $class)
string  loadClass(String $class)
```

## Details

### -void register()
Registers the auto loader.

### -bool getClass(String $class)
Loads the classes by using loadClass().

#### Parameters
```
String | $class | Contains the Class or better the Class namespace
```

#### Return Value
```
bool | returns true or false rather the class got included or not
```

### -string loadClass(String $class)
Uses Loader Classes to parse path by using namespace.
 
#### Parameters
```
String | $class Contains the Class or better the Class namespace
```

#### Return Value
```
String | returns the parsed file path
```