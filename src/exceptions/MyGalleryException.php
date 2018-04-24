<?php

namespace MyGallery\Exceptions;


abstract class MyGalleryException extends \Exception
{
    public function __construct(string $message,int $errorCode, \Throwable $previous = NULL)
    {
        parent::__construct($message, $errorCode, $previous);
    }
    
    public function __toString()
    {
        return "Error #{$this->getCode()}\n
                Error message: {$this->getMessageFromCode($this->code)}\n
                Message: {$this->getMessage()}\n
                In {$this->getFile()} at line {$this->getLine()}\n
                $this->getTraceAsString()";
    }
    
    public function getMessageFromCode($code)
    {
        return $this->codeToMessage($code);
    }
    
    protected abstract function codeToMessage(int $code);
}