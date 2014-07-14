php-contact-form
================


## About
Simple and effective contact form in PHP. 
This uses pure HTML and PHP, but better UI feedback with jQuery/ajax might be implemented later.

Please note this script uses your php.ini SMTP configuration for sending emails.
The standard PHP mail() function does not support SMTP authentication/SSL etc.

The CAPTCHA is an ASCII generator which outputs thousands of # signs instead of a picture.
Should be pretty secure against SPAM bots.

The ASCII CAPTCHA is a customized script, you may find the original here: http://phpsnips.com/71/ASCII-CAPTCHA

## Install instructions
- Clone the project or copy the files: contact.php, asciicaptcha.php and bebas.ttf
- You may include contact.php from another file, but remember to use session_start(); at the top of your script.
- Edit the config variables and it's pretty much ready for use.


## Preview (v1.0.0)
![Alt text](https://raw.githubusercontent.com/kek91/php-contact-form/master/php-contact-form.png?raw=true "PHP Contact Form preview")


## Changelog
14.07.2014 v1.1.1
- Bug fix for CAPTCHA, forgot to transform input to lower case. This is now fixed with strtolower()

13.07.2014 v1.1.0
- Forgot to output the "telephone" field in the email
- Name is now also outputted in the email
- Added the nl2br() function for message for proper linebreaking

04.07.2014 v1.0.0
- First version released
