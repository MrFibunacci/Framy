# Validation Component
Unter der Validation von Daten versteht man, dass überprüft wird, dass die vom Benutzer übergebenen Daten auch dem erwartetem Format entsprechend. Übergebt ihr als GET-Parameter beispielsweise die ID zu einem Artikel in eurem Onlineshop, so solltet ihr überprüfen dass diese übergebene Wert auch tatsächlich eine Zahl ist. Durch eine gute Datenvalidierung könnt ihr euren Schutz gegen SQL-Injections und Cross-Site-Scripting deutlich erhöhen.

Neben der Erhöhung der Sicherheit bekommt ihr durch eine gute Validierung der Eingabedaten auch eine gesteigerte Nutzererfahrung, da Falscheingaben so frühzeitig abgefangen werden.

Prinzipiell solltet ihr alle Eingaben eurer Benutzer überprüfen und durch geeignete Hinweise auf Falscheingaben hinweisen. Ein Vertipper bei der E-Mail-Adresse passiert schnell und kann ärgerlich sein, wenn dieses nicht auffällt.

## Usage

Basic Example usage:
```
Validation::getInstance()->validate('example@web.de', 'email');  // returns true

Validation::getInstance()->validate('false.mail.de', 'email');  // throws Exception

Validation::getInstance()->validate('false.mail.de', 'email', false); // returns "Invalid email"
```

You can either use the example above or use the Validation Trait.
Like this:
```
use app\framework\Component\Validation\ValidationTrait;

class yourClass 
{
    use ValidationTrait;
    
    function yourFunction()
    {
        self::validate()->validate('example@web.de', 'email');
    }
}
```

## General Information

If the given Validators don't mach your requirements, you can add your own by using the addValidator() function.