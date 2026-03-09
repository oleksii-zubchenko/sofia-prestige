<?php

namespace BPPIV;

class Shortcode {
    
    private $_instance = null;

    public function __construct(){
        add_shortcode( 'panorama', [$this, 'panorama_callback'] );
    }

    public function panorama_callback(){
        
    }

    public static function instance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


}