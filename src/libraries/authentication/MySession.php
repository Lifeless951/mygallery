<?php

namespace MyGallery\Libraries\Authentication;


/**
 * Класс для старта/пересоздания сессий
 * Class MySession
 * @package MyGallery\Libraries\Authentication
 */
class MySession
{
    private static $db;
    
    /**
     * Создает сессию с проверкой доступа к
     * @param \PDO $db
     * @throws \Exception
     */
    public static function start(\PDO $db)
    {
        ini_set('session.use_strict_mode', 1);
        self::$db = $db;
        session_start();
        
        if ( isset($_SESSION['destroyed']) ) {
            if ( $_SESSION['destroyed'] >= (time() - 180) ) {
                // Доступ к истекшей сессии из-за нестабильного соединения
                // Ставим пользователю куки новой с ID новой сессии
                session_commit();
                session_id($_SESSION['new_session_id']);
                session_start();
            } else {
                //Попытка использования истекшей сессии. Удаляем все SID пользователя.
                self::removeAllAuthenticationFlags(session_id());
                throw new \Exception('Доступ к истекшей сессии');
            }
        }
    }
    
    /**
     * Пересоздает сессию с новым ID и сохранением SID новой сессии
     * в данные истекшей сессии
     */
    public static function regenerateId()
    {
        $newSID = session_create_id();
        $_SESSION['new_session_id'] = $newSID;
        $_SESSION['destroyed'] = time();
        
        session_write_close();
        session_id($newSID);
        ini_set('session.use_strict_mode', 0);
        session_start();
        ini_set('session.use_strict_mode', 1);
        
        unset($_SESSION['destroyed']);
        unset($_SESSION['new_session_id']);
    }
    
    
    /**
     * Удаляет все сессии, связанные с пользователем переданной в параметре сессии
     * @param string $sessionId
     */
    public static function removeAllAuthenticationFlags(string $sessionId)
    {
        //TODO: добавить связь таблицы sessions с таблицей user_info для реализации возможности удаления
        $sql = "DELETE * FROM";
        self::$db->set($sql, [$sessionId]);
    }
}