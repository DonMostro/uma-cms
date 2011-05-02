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
    * Registro de la referencia a un objeto objeto
    * @return void
    */ 
    public function addObserver (& $observer) {
        $this->observers[]=& $observer;
    }
 
    //! Un descriptor de acceso    
    /**
    * Devuelve el valor actual de la propiedad estatal
    * @return mixed
    */ 
    public function getState () {
        return $this->state;
    }
    
    public function getMethod() {
    	return $this->method;
    }
 
    //! Un descriptor de acceso    
    /**
    * Asigna un valor a la propiedad estatal
    * @param $statevariable mixta para almacenar
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