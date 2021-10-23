<?php

namespace app\components;

use yii\base\Component;

/**
 * Class Debug
 */
class Debug extends Component
{
    public static function mailMysqlError($ErrorMessage, $FileName = '')
    {
        $File_val = '';
        if ( ! empty($FileName)) {
            $File_val = basename($FileName, '.php') . ".php";
        }

        $message = $ErrorMessage;
        $From    = "admin@sathsang.com";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "From: Sathsang <$From>\r\n";
        $to      = "balajimada8@gmail.com.com";
        $headers .= 'Cc: balajimada8@gmail.com.com' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $subject = "Sathsang - Mysql Error - " . date("Ymd H:i:s") . " - " . $File_val;

        mail($to, $subject, $message, $headers);
    }
}
