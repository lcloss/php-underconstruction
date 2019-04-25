<?php

namespace App;

class Mail
{
    public function __construct()
    {

    }

    public static function send($from_mail, $mail_to, $mail_subject, $mail_message, $from_name = '')
    {
        $encoding = "utf-8";

        // Preferences for Subject field
        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 80,
            "line-break-chars" => "\r\n"
        );
    
        // Mail header
        $header = "Content-type: text/html; charset=".$encoding." \r\n";
        $header .= "From: ".$from_name." <".$from_mail."> \r\n";
        $header .= "MIME-Version: 1.0 \r\n";
        $header .= "Content-Transfer-Encoding: 8bit \r\n";
        $header .= "Date: ".date("r (T)")." \r\n";
        $header .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);
    
        // Send mail
        $success = mail($mail_to, $mail_subject, $mail_message, $header);    
        if ( !$success ) {
            $error_msg = error_get_last()['message'];

            if ( !empty($error_msg) ) {
                return false;
            } else {
                return true;
            }
        }

        return true;

    }
}