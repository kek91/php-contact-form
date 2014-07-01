php-contact-form
================

Simple and effective contact form in PHP

Please note this script uses your php.ini SMTP configuration for sending emails.
The standard PHP mail() function does not support SMTP authentication/SSL etc.

Known vulnerabilities
    - XSS (could be solved by generating a token on form page and checking the token on validation page)
    - Anti SPAM check can be cracked easily