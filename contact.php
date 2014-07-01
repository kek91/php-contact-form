<?php
session_start(); 
// Erase this session_start(); if including the script from another file

/*******************************************************
* Simple contact form in PHP                           *
* Author: Teknix / Kim Eirik Kvassheim <kek@teknix.no> *
* Version: 0.0.1                                       *
********************************************************/

// Edit the following variables
$mail_to        = "";
$mail_cc        = ""; // Optional
$mail_bcc       = ""; // Optional

// Validation
//include_once("validate.class.php");
function escape($str)
{
    echo "Before escape: $str";
    $str = trim($str);
    $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
    echo "<br><br>After escape: $str";
    //return $str;
}

// Check if form has been submitted
if(!empty($_POST))
{
    if(isset($_SESSION['formToken'] && $_POST['formToken'] === $_SESSION['formToken']))
    {
        // Form token is ok! Now we check the input fields
        $name = escape($_POST['name']);
        $email








        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 3,
                'max' => 30
            ),
            'email' => array(
                'required' => true,
                'mailcheck' => true
            ),
            'telephone' => array(
                'required' => false,
                'telephone' => true
            )
        ));

        if($validation->passed())
        {
            // All input fields are ok, let's check the captcha
            $captchaQ1 = intval($_POST['captchaQ1']);
            $captchaQ2 = intval($_POST['captchaQ2']);
            $captchaOp = $_POST['captchaOp'];
            $captchaAnswer = $captchaQ1.$captchaOp.$captchaQ2;
        }
    }
    else
    {
        /* 
        * No valid token were supplied 
        * meaning someone tried to submit 
        * without going through the form.
        * This will return false 
        */
        return false;
    }
}

// If form has not been submitted we show the HTML form
else
{

}