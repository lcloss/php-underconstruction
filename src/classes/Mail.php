<?php

namespace App;

class Mail
{
    public static function send($from_mail, $mail_to, $mail_subject, $mail_message, $from_name = '')
    {
        $encoding = "utf-8";

        // Preferences for Subject field
        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 80,
            "line-break-chars" => "\n"
        );
    
        // Mail header
        $headers = [];
        $headers[] = "Content-type: text/html; charset=" . $encoding;
        $headers[] = "From: " . $from_name . " <" . $from_mail . ">";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Transfer-Encoding: 8bit";
        $headers[] = "Date: ".date("r (T)");
    
        $email_headers = implode("\n", $headers);
        $email_headers .= iconv_mime_encode("Subject", $mail_subject, $subject_preferences);

        // Send mail
        $success = mail($mail_to, $mail_subject, $mail_message, $email_headers);    
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