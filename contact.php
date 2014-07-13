<?php
session_start(); 
// If you're including this file from another file, remember to use session_start(); in the beginning of your script.

///////////////////////////////////////////////////////////////
//                                                            /
//    Simple contact form in PHP                              /
//    Author: Teknix / Kim Eirik Kvassheim <kek@teknix.no>    /
//    Version: 1.0.0                                          /
//                                                            /
///////////////////////////////////////////////////////////////

// Config
$enable_captcha     = true; // set to true/false to enable/disable CAPTCHA

$mail_to            = ""; // Mail to
$mail_cc            = ""; // Mail copy to (Optional)
$mail_subject       = "PHP Contact Form";
$phone_countrycode  = "+47";

$missing_name       = "Your name field is empty. Please try again.";
$missing_message    = "Message field is empty. Please try again.";
$invalid_number     = "The telephone number provided seems to be invalid. Please try again.";
$invalid_email      = "The email address provided seems to be invalid. Please try again.";
$invalid_captcha    = "The security question was not correct. Please try again.";
$invalid_token      = "Auth token was not provided. If this is an XSS injection attempt then you are mean. Shoo!";
$mail_sent_success  = "Email successfully sent! Thank you.";
$mail_sent_failure  = "Thanks for your interest. Unfortunately, due to some error the email could not be sent.<br>Please try again later.";



// Functions
function escape($str)
{
    $str = strip_tags(trim($str));
    return $str;
}

function escapeNumber($num)
{
    $num = preg_replace('/[^0-9]/', '', $num);
    return $num;
}

function sendMail($to, $cc, $from, $subject, $msg, $name, $tel)
{
    $headers  = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    if(filter_var($cc, FILTER_VALIDATE_EMAIL)) $headers .= "CC: $cc\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message  = '<html><body>';
    $message .= '<h1>'.$subject.'</h1>';
    $message .= 'Name: '.$name.' ('.$from.')<br>';
    $message .= 'Telephone: '.$tel.'<br>';
    $message .= $msg;
    $message .= '</body></html>';
    if(mail($to, $subject, $message, $headers)) return true;
    else return false;
}


// Check if form has been submitted
if(!empty($_POST['name']))
{
    if(!empty($_SESSION['formToken']) && !empty($_POST['formToken']) && $_POST['formToken'] === $_SESSION['formToken'])
    {
        // Form token (for preventing XSS attacks) is ok!
        // Now we check the input fields
        $name           = escape($_POST['name']);
        $email          = escape($_POST['email']);
        $telephone      = escapeNumber($_POST['telephone']);
        $captcha        = escape($_POST['captcha']);
        $captchaAnswer  = escape($_SESSION['CAPTCHA']);
        $message        = nl2br(escape($_POST['message']));

        // In case of any errors we save the input fields to $_SESSION
        $_SESSION['formName']       = $name;
        $_SESSION['formEmail']      = $email;
        $_SESSION['formTelephone']  = $telephone;
        $_SESSION['formMessage']    = $message;

        $error      = false;
        $errorlist  = array();

        if(empty($name))
        {
            $error = true;
            $errorlist[] = $missing_name;
        }
        if(!ctype_digit($telephone))
        {
            $error = true;
            $errorlist[] = $invalid_number;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error = true;
            $errorlist[] = $invalid_email;
        }
        if(empty($message))
        {
            $error = true;
            $errorlist[] = $missing_message;
        }
        if($enable_captcha === true && $captcha != $captchaAnswer)
        {
            $error = true;
            $errorlist[] = $invalid_captcha;
        }

        if($error === false)
        {
            if(sendMail($mail_to, $mail_cc, $email, $mail_subject, $message, $name, $telephone))
            {
                echo $mail_sent_success;
                echo '<br><br>Copy of the email:<br><br>
                <h1>'.$mail_subject.'</h1>
                From: '.$name.' ('.$email.')<br>
                Telephone: '.$telephone.'<br>
                Message: <br>'.$message;
                $_SESSION['formName']       = "";
                $_SESSION['formEmail']      = "";
                $_SESSION['formTelephone']  = "";
                $_SESSION['formMessage']    = "";
            }
            else
            {
                echo $mail_sent_failure;
            }
        }
        else
        {
            // Error
            echo 'Sorry, one or more errors occured. Please see errors below and fix them to proceed:';
            echo '<ul>';
            foreach($errorlist as $error)
            {
                echo '<li>', $error, '</li>';
            }
            echo '</ul>';
            echo '<a href="contact.php">&raquo; Go back</a>';
        }
    }
    else
    {
        /* No valid token were supplied 
        meaning someone tried to submit 
        without going through the HTML form. */
        echo $invalid_token;
    }
}



// If form has not been submitted we show the HTML form
else
{
    $formToken = md5(mt_rand());
    $_SESSION['formToken'] = $formToken;
    ?>
    <style>
        input { width:160px; }
    </style>
    <form action="contact.php" method="post">
        <table>
            <tr>
                <th colspan="3">Contact form</th>
            </tr>
            <tr>
                <td><label for="name">Name</label></td>
                <td></td>
                <td><input type="text" name="name" id="name" value="<?php echo $_SESSION['formName']; ?>" required></td>
            </tr>
            <tr>
                <td><label for="telephone">Telephone</label></td>
                <td><?php echo $phone_countrycode; ?></td>
                <td><input type="tel" name="telephone" id="telephone" value="<?php echo $_SESSION['formTelephone']; ?>" required></td>
            </tr>
            <tr>
                <td><label for="email">Email</label></td>
                <td></td>
                <td><input type="email" name="email" id="email" value="<?php echo $_SESSION['formEmail']; ?>" required></td>
            </tr>
            <?php
            if($enable_captcha)
            {
            ?>
            <tr>
                <td><label for="captcha">Security question:<br>Type in the letters</label></td>
                <td></td>
                <td><?php include("asciicaptcha.php"); ?> <input type="text" name="captcha" id="captcha" value="" required></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td><label for="message">Message</label></td>
                <td></td>
                <td><textarea name="message" id="message" style="width:300px;height:100px;" required><?php echo $_SESSION['formMessage']; ?></textarea></td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="hidden" name="formToken" value="<?php echo $formToken; ?>">
                    <input type="submit" value="Send message" style="width:100%; cursor:pointer; padding:10px;">
                </td>
            </tr>
        </table>
    </form>
    <?php
}