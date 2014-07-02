<?php
session_start(); 
// If you're including this file from another file, remember to use session_start(); in the beginning of your script.

///////////////////////////////////////////////////////////////
//                                                            /
//    Simple contact form in PHP                              /
//    Author: Teknix / Kim Eirik Kvassheim <kek@teknix.no>    /
//    Version: 0.0.1                                          /
//                                                            /
///////////////////////////////////////////////////////////////


// Config: Mail settings
$mail_to            = "";
$mail_cc            = ""; // Optional
$mail_bcc           = ""; // Optional
$phone_countrycode  = "+47";
// Config: Error messages
$missing_name = "Your name field is empty. Please try again.";
$invalid_number = "The telephone number provided seems to be invalid. Please try again.";
$invalid_email = "The email address provided seems to be invalid. Please try again.";
$missing_message = "Message field is empty. Please try again.";
$invalid_token = "Auth token was not provided. If this is an XSS injection attempt then you are mean. Shoo!";
// End of config, no need to change anything below


// Functions
function escape($str)
{
    $str = htmlentities(trim($str), ENT_QUOTES, 'UTF-8');
    return $str;
}
function escapeNumber($num)
{
    $num = preg_replace('/[^0-9]/', '', $num);
    return $num;
}


// Check if form has been submitted
if(!empty($_POST['name']))
{
    if(!empty($_SESSION['formToken']) && !empty($_POST['formToken']) && $_POST['formToken'] === $_SESSION['formToken'])
    {
        // Form token is ok! Now we check the input fields
        // The token is used to prevent XSS attacks
        $name       = escape($_POST['name']);
        $email      = escape($_POST['email']);
        $telephone  = escapeNumber($_POST['telephone']);
        $message    = escape($_POST['message']);

        $error      = false;
        $errorlist  = "";

        if(empty($name))
        {
            $error = true;
            $errorlist .= $missing_name;
        }
        elseif(!ctype_digit($telephone))
        {
            $error = true;
            $errorlist .= $invalid_number;
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error = true;
            $errorlist .= $invalid_email;
        }
        elseif(empty($message))
        {
            $error = true;
            $errorlist .= $missing_message;
        }
        else
        {
            // Success
        }



        /*




            // All input fields are ok, let's check the captcha
            $captchaQ1 = intval($_POST['captchaQ1']);
            $captchaQ2 = intval($_POST['captchaQ2']);
            $captchaOp = $_POST['captchaOp'];
            $captchaAnswer = $captchaQ1.$captchaOp.$captchaQ2;
        }
        */
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
    ?>
    <style>
        table, td, input, textarea     { padding:5px; font-family:Verdana,Helvetica,Arial,sans-serif; font-size:12px; }
        input[type=submit]      { cursor:pointer; padding:10px 5px 10px 5px; }
        textarea                { width:300px; height:100px; }
    </style>
    <form action="contact.php" method="post">
        <table>
            <tr>
                <th colspan="3">Contact form</th>
            </tr>
            <tr>
                <td><label for="name">Name</label></td>
                <td></td>
                <td><input type="text" name="name" id="name" required></td>
            </tr>
            <tr>
                <td><label for="telephone">Telephone</label></td>
                <td><?php echo $phone_countrycode; ?></td>
                <td><input type="tel" name="telephone" id="telephone" required></td>
            </tr>
            <tr>
                <td><label for="email">Email</label></td>
                <td></td>
                <td><input type="email" name="email" id="email" required></td>
            </tr>
            <tr>
                <td><label for="message">Message</label></td>
                <td></td>
                <td><textarea name="message" id="message" required></textarea></td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="hidden" name="formToken" value="<?php echo $_SESSION['formToken'] = md5(mt_rand()); ?>">
                    <input type="submit" value="Send message" style="width:100%;">
                </td>
            </tr>
        </table>
    </form>
    <?php
}