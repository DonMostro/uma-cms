<?php
/**
 *  Base Observerable class
 */
class Observable {
    /**
    * Protected
    * $observers an array of Observer objects to notify
    */
    protected $observers;
 
    /**
    * Protected
    * $state store the state of this observable object
    */
    protected $state;
    
    protected $method;
 
    //! A constructor
    /**
    * Constructs the Observerable object
    */
    function __contstruct() {
        $this->observers=array();
        $modelpool = ModelPool::getInstance();
        $modelpool->register($this);
    }
 
    //! An accessor
    /**
    * Calls the update() function using the reference to each
    * registered observer - used by children of Observable
    * @return void
    */ 
    public function notifyObservers () {
        $observers=count($this->observers);
        for ($i=0;$i<$observers;$i++) {
            $this->observers[$i]->update();
        }
    }
 
    //! An accessor
    /**
    * Register the reference to an object object
    * @return void
    */ 
    public function addObserver (& $observer) {
        $this->observers[]=& $observer;
    }
 
    //! An accessor
    /**
    * Returns the current value of the state property
    * @return mixed
    */ 
    public function getState () {
        return $this->state;
    }
    
    public function getMethod() {
    	return $this->method;
    }
 
    //! An accessor
    /**
    * Assigns a value to state property
    * @param $state mixed variable to store
    * @return void
    */ 
    public function setState ($state) {
        $this->state=$state;
    }
    
    public function setMethod ($method){
    	$this->method=$method;
    }
}
?>