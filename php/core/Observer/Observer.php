<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Observer
 *
 * @author 1
 */
class Observer {

    protected $events = array();

    public function __construct(Factory $check) {
        ;
    }

    /**
     *
     * @param string $event
     * @param array $call (object, $method)
     */
    public function addEvent($event, $call) {
        $this->events[$event][] = $call;
    }

    public function trigger($event) {
        $args = func_get_args();
        array_shift($args);
        if (isset($this->events[$event]) && !empty($this->events[$event])) {
            $obsData = array();
            foreach ($this->events[$event] as $call) {
                if (sizeof($call) > 2) {
                    $calling[0] = $call[0];
                    $calling[1] = $call[1];
                    array_shift($call);
                    array_shift($call);
                    $args = array_merge($args, $call);
                } else {
                    $calling = $call;
                }
                if (is_callable($calling)) {
                    $obsData[] = call_user_func_array($calling, $args);
                }
            }
            return $obsData;
        }
        return array();
    }

}

?>
