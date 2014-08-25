<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Profiler
 *
 * @author 1
 */
class Profiler {
    private $timers=array();
    private $log=array();
    public function __construct(Factory $check) {
        ;
    }
    public function start($mark)
    {
        $this->timers[$mark]=microtime(true);
    }
    public function end($mark)
    {
        if(isset($this->timers[$mark]))
        {
            $this->log[]='<b>'.$mark.'</b><br/>executed on: '.(microtime(true)-$this->timers[$mark]).' sec.';
        }
    }
    public function getLog()
    {
        return '<div class="log prettyprint"><pre>'.implode('<br/><br/>',$this->log).'</pre></div>';
    }
}

?>
