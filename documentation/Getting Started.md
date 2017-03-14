# Getting started [Framy v0.9]

## Setup
## Directory Structure

### Introduction
The default Framy application structure is intended to provide a great starting point for both large and small applications. Of course, you are free to organize your application however you like. Framy imposes almost no restrictions on where any given class is located.

### The Root Directory
In the root directory is every file of the project and Framy.
  
#### The app Directory
Inside the `app` directory, as you might expect, are all the core Class of your code and Framys. 
However especially for you its required to know that specially folder `custom` is for you. Because you are Special. Happy Birthday.

#### The config Directory
The `config` directory, as the name implies, contains all the config files. It's a great idea to read through all of these files and familiarize yourself with all of the options available to you.

#### The public Directory
The `public` directory contains the `index.php` file, which is the entry point for all requests entering your application. This directory also houses your assets such as images, JavaScript, and CSS.

#### The routes Directory
The `routes` directory contains the files `web.php` and `api.php`. Where you register the web an api routs.
   
#### The storage Directory
The `storage` directory contains your views as well as your raw, un-compiled assets such as LESS, SASS, or JavaScript. This directory also houses all of your language files and everything else you want to store.


### The App Directory
The `app` Directory which contains the core code of Framy. In the following topics I will shortly explain what the components, in the `Component` folder, are meant to do. The folder is called like that because the folder needs to mach with the namespaces and component sound better in an namespace.

#### ClassLoader
Is the house made auto loader currently only supporting `PSR-0` standard.

#### Config
Made to simplify interaction with config files.

#### Database
Communication with every type of Database under usage of Medoo.

#### Image
Image manipulation like crop, resize and more.

#### Route
Routing as we know. you can use a self made routing tool or you can use `Klein`.

#### StdLib
Standard Library with some basic function like an simple validation trait or different Objects like `ArrayObject` for an better work with array in this example. And exception classes.

#### Stopwatch
Specially to profile your code. As the name says its basically an Stopwatch for your code witch manny extra features.   

#### Storage
To work with files and directory's. 

#### TemplateEngine
Templates with different bridges like Smarty or mustache

#### Validation
Validate data.


## Creating Pages
Quite at first you should register the route. You can read how to exactly do this under `Routing`.
But here an example:
``` 
Route::add('hallowelt',function(){
   // do something
});
```

Inside the function can everything happen like calling an function from an class.
In this example we will not do this.

We will just generate an template with one of the bridges, i.e. Smarty.

To do this we need to add an Template. Lets name it hello.tpl (syntax Smarty requires)

We add the file under `storage/templates`.

It shall look like this:
```
<html> 
    <head> 
        <title>{$title}</title> 
    </head> 
    <body> 
        {$content} 
    </body> 
</html> 
```

Now we want to display the template.
```
Route::add('hallowelt',function(){
   $TE = new TemplateEngine("Smarty");
   
   // set the path to templates
   $TE->getBridge()->template_dir = realpath('../storage/templates');
   
   // assign values
   $TE->getBridge()->assign("title", "Hello");
   $TE->getBridge()->assign("content", "Hello World");
   
   // display content
   $TE->getBridge()->display("hello.tpl"); 
});
```

It would be more elegant to set the attribute template_dir in the constructor but we don't do this automatically yet.
It will show the text "Hello World" in the browser. So it works! 

## Routing
You can ether just use `Klein` or self made system for this you can find examples in following few lines.

```
// full code
use app\framework\Component\Route\Config;
use app\framework\Component\Route\Route;

//config
Config::set('basepath', 'YOUR_BASE_PATH'); 

//init routing
Route::init();

Route::add('user/(.*)/edit',function($id){
    echo 'Edit user with id '.$id;
});

Route::add('',function(){
    echo 'Welcome :-)';
});

Route::run();
```

`Klein` [Documentation](https://klein.readthedocs.io/en/latest/).

## Creating and Using Templates
You can chose between these bridges:

 - [Smarty](http://www.smarty.net/documentation)
 - [mustache](https://github.com/bobthecow/mustache.php/wiki)

To use them you simply enter the name by calling the class like this:

```
$templateEngine = new TemplateEngine("Smarty");
```

Now with `getBridge()` you can call every function the bridge provides.
For more information's about the `bridges` go to their documentation sites as linked in the list above. 

