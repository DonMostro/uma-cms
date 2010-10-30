<?php
/**
 *  Base Observer class
 */
class Observer {
    /**
    * Protected
    * $subject a child of class Observable that we're observing
    */
    protected $subject;
 
    //! A constructor
    /**
    * Constructs the Observer
    * @param $subject the object to observe
    */
    public function Observer (& $subject) {
        $this->subject=& $subject;
 
        // Register this object so subject can notify it
        $subject->addObserver($this);
    }
 
    //! An accessor
    /**
    * Abstract function implemented by children to repond to
    * to changes in Observable subject
    * @return void
    */    
    public function update(){}
}
?>