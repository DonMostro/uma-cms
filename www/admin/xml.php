<?php

//Usage:

//$xml=&new XML();

//$xml->parse('file');

//echo $xml->elements[0]['NAME'];

class XML{

    var $file;

    var $xml_parser;

    var $elements;

    var $parents;

    var $pos;

    

    function startElement($parser, $name, $attrs)

    {

       $this->pos++;

       $this->elements[$this->pos]=$attrs;

       $this->elements[$this->pos]['_name']=$name;

       if(count($this->parents)>0)$this->elements[$this->pos]['_parent']=$this->parents[count($this->parents)-1];

       array_push($this->parents,$this->pos);

    }



    function endElement($parser, $name)

    {

       array_pop($this->parents);

    }

    

    function XML(){

       $this->xml_parser = xml_parser_create();

       xml_set_object($this->xml_parser,$this);

       xml_set_element_handler($this->xml_parser, "startElement", "endElement");

    }

    

    function parse($file){

        $this->elements=array();

        $this->parents=array();

        $this->pos=-1;

        if (!($fp = fopen($file, "r"))) {

           die("could not open XML input");

        }



        while ($data = fread($fp, 4096)) {

           if (!xml_parse($this->xml_parser, $data, feof($fp))) {

               die(sprintf("XML error: %s at line %d",

                           xml_error_string(xml_get_error_code($this->xml_parser)),

                           xml_get_current_line_number($this->xml_parser)));

           }

        }

        xml_parser_free($this->xml_parser);

    }

    



}

?>

