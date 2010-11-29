<?php
/**
 *   ______________________
 *    Clase Base Observer
 *   ______________________ 
 */
class Observer {
    /**
    * Protected
    * $subject a child of class Observable that we're observing
    */
    protected $subject;
 
    //! A constructor
    /**
    * constructor de Observer
    * @param $subject el objeto de observe
    */
    public function Observer (& $subject) {
        $this->subject=& $subject;
 
        // Register los objetos so subject can notify it
        $subject->addObserver($this);
    }
 
    //! Un descriptor de acceso
    /**
    * Abstract function implemented by children to repond to
    * to changes in Observable subject
    * @return void
    */    
    public function update(){}
}
?>