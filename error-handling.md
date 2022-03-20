##`Lets learn PHP ERROR handling : `
###- `types of error in php :`
1. **Parse error or Syntax Error.**
2. **Fatal Error :**
` It is the type of error where PHP compiler understand the PHP code but it recognizes an undeclared function. This means that function is called without the definition of function.`
```injectablephp
function add($x, $y)
{
    $sum = $x + $y;
    echo "sum = " . $sum;
}
$x = 0;
$y = 20;
add($x, $y);
  
diff($x, $y);
```
`PHP Fatal error:  Uncaught Error:
Call to undefined function diff()
in /home/36db1ad4634ff7deb7f7347a4ac14d3a.php:12`

3. **Warning Error :**
   ` he main reason of warning errors are including a missing file. This means that the PHP function call the missing file.`
```injectablephp
$x = "Hello i'm sabbir";
  
include ("unknown.php");
  
echo $x . "WPDeveloper";
```
`PHP Warning:  include(gfg.php): failed to
open stream: No such file or directory in
/home/aed0ed3b35fece41022f332aba5c9b45.php on line 5`

4. **Notice Error:**
   `  It is similar to warning error. It means that the program contains something wrong but it allows the execution of script.`
```injectablephp
  
$x = "GeeksforGeeks";
  
echo $x;
  
echo $geeks;
```
`PHP Notice:  Undefined variable: geeks in
/home/84c47fe936e1068b69fb834508d59689.php on line 5`


##php error constants and their definitations:
- **E_ERROR** : A fatal error that causes script termination
- **E_WARNING** : Run-time warning that does not cause script termination
- **E_PARSE** : Compile time parse error.
- **E_NOTICE** : Run time notice caused due to error in code
- **E_CORE_ERROR** : Fatal errors that occur during PHP’s initial startup (installation)
- **E_CORE_WARNING** : Warnings that occur during PHP’s initial startup
- **E_COMPILE_ERROR** : Fatal compile-time errors indication problem with script.
- **E_USER_ERROR** : User-generated error message.
- **E_USER_WARNING** : User-generated warning message.
- **E_USER_NOTICE** : User-generated notice message.
- **E_STRICT** : Run-time notices.
- **E_RECOVERABLE_ERROR** : Catchable fatal error indicating a dangerous error
- **E_DEPRECATED** : Run-time notices.















![another try](https://learncodeonline.in/mascot.png)

```injectablephp
$a = name;
$b = 'hello';
for ($i = 0; $i < 10; $i++){
echo 'hello there';
}
```

```javascript
var a = 20;
```

###`lets make a table :`
| ID | roll | name | another |
|-----|------|------|---------|
| 1 | 20 |sabbir| try |

##`rich text like hacker :`

> hello this is a hacker like text line :

1. list one
2. list two
3. list three

- list four
  -list five
