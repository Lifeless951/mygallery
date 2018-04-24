<?php

namespace MyGallery\Exceptions;


class ControllerException extends MyGalleryException
{
    const UNDEFINED_ACTION = 1000;
    
    protected function codeToMessage(int $code)
    {
        switch ($code){
            case self::UNDEFINED_ACTION:
                $message = 'Action-метод не реализован в классе контроллера';
                break;
            default:
                $message = 'Undefined error';
        }
        
        return $message;
    }
}