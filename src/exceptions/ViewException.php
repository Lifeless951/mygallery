<?php

namespace MyGallery\Exceptions;


class ViewException extends MyGalleryException
{
    const TWIG_SYNTAX_ERROR = 1000;
    const WRONG_TEMPLATE = 1001;
    
    protected function codeToMessage(int $code)
    {
        switch ($code) {
            case self::TWIG_SYNTAX_ERROR:
                $message = 'Ошибка в синтаксисе шаблона';
                break;
            case self::WRONG_TEMPLATE:
                $message = 'Шаблон не существует';
                break;
            default:
                $message = 'Undefined exception';
        }
        
        return $message;
    }
}