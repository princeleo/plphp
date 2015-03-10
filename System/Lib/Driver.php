<?php

class System_Lib_Driver
{
    public static  function DB($params = '', $active_record_override = NULL)
    {
        if(!function_exists('DB')){
            require_once(SYSTEM_PATH.'Database/DB.php');
        }
        return DB($params,$active_record_override);
    }
}