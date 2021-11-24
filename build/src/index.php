<?php
error_reporting(0);
class openfunc{
    protected $object;
    function __construct(){
        $this->object=new normal();
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
class evil extends hack{
    protected $data;
    protected $a;
    protected $b; 
    protected $c;
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

if (isset($_GET['Xp0int']))  
{
    $Data = unserialize($_GET['Xp0int']);
} 
else 
{ 
    highlight_file(__file__); 
}
