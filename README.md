php-contact-form
================


## About
Simple and effective contact form in PHP. 
This uses pure HTML and PHP, but better UI feedback with jQuery/ajax might be implemented later.

Please note this script uses your php.ini SMTP configuration for sending emails.
The standard PHP mail() function does not support SMTP authentication/SSL etc.


## Install instructions
- Clone the project or copy the files: contact.php, asciicaptcha.php and bebas.ttf
- You may include contact.php from another file, but remember to use session_start(); at the top of your script.
- Edit the config variables and it's pretty much ready for use.


## Preview (v1.0.0)
![Alt text](https://raw.githubusercontent.com/kek91/php-contact-form/master/php-contact-form.png?raw=true "PHP Contact Form preview")


## Changelog
04.07.2014 v1.0.0