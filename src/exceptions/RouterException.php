<?php

namespace MyGallery\Exceptions;

class RouterException extends MyGalleryException
{
    const WRONG_ROUTE = 1000;
    const UNDEFINED_ACTION = 1001;
    const NO_ROUTES = 1002;
    const NO_CONTROLLER = 1003;
    
    protected function codeToMessage(int $code)
    {
        switch($code) {
            case self::WRONG_ROUTE:
                $message = 'Введенный URI не соотв. ни одному маршруту';
                break;
            case self::UNDEFINED_ACTION:
                $message = 'Action метод не реализован в классе контроллера';
                break;
            case self::NO_ROUTES:
                $message = 'Отсутствуют маршруты';
                break;
            case self::NO_CONTROLLER:
                $message = 'Контроллер не существует';
                break;
            default:
                $message = 'Undefined Error';
        }
        
        return $message;
    }
    
}