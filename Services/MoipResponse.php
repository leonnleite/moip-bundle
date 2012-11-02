<?php

namespace Tear\MoipBundle\Services;

class MoipResponse 
{
    private $response;

    function __construct(array $response)
    {
            $this->response = $response;
    }

    function __get($name)
    {
            if (isset($this->response[$name]))
            {
                    return $this->response[$name];
            }
            return null;
    }
    
    function __call($name,$arguments){
        if(substr($name, 0,3) == 'get'){
            $variabel = lcfirst(substr($name, 3));
            return $this->response[$variabel];
        }else{
            throw new Exception( "Method ({$name}) does not exist", 0 );
        }
        return null;
    }
}