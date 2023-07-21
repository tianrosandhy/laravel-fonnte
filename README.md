## Laravel Fonnte Wrapper

This package contain a simple http wrapper to send unofficial whatsapp message with fonnte.

### Requirement
- Laravel 8 or more
- Active and registered Fonnte token (https://fonnte.com)

### Installation
- `composer require tianrosandhy/laravel-fonnte`
- Setup .env and fill `FONNTE_TOKEN=` with your fonnte token, and `FALLBACK_FONNTE_RECIPIENT=` with your fallback whatsapp number
- Test by calling `Fonnte::ping();` or, `Fonnte::sendMessage($recipient, $message);`

### Examples

##### Send Simple Message

    <?php
    use Fonnte;
    ...
    ...
    
    // $recipients support single string, comma separated string, or array of string
    $recipients = ['08123456789', '081122334455'];
    $message = "Hello world!";
    $additional_param = []; // check https://docs.fonnte.com/api-send-message for complete additional options
    Fonnte::sendMessage($recipients, $message, $additional_param);
