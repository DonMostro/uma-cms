<?php
/**
 *  Clase Base Observable
 */
class Observable {
    /**
    * Protected
    * $observers un array de objetos Observer para notificar
    */
    protected $observers;
 
    /**
    * Protected
    * $state almacena el estado del objeto observable
    */
    protected $state;
    
    protected $method;
 
    /**
    * Construye el objeto observable
    */
    function __construct() {
        $this->observers=array();
        $modelpool = ModelPool::getInstance();
        $modelpool->register($this);
    }
 
    /**
    * Llama la función update() usando la referncia de cada
    * observer registrado - usado por los hijos de Observable
    * @return void
    */ 
    public function notifyObservers () {
        $observers=count($this->observers);
        for ($i=0;$i<$observers;$i++) {
            $this->observers[$i]->update();
        }
    }
 
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