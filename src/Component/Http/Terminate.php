<?php

namespace WPframework\Component\Http;

class Terminate {

    /**
     * Sends an HTTP status code.
     *
     * @param int $status_code The HTTP status code to send.
     */
    protected static function sendStatusCode(int $status_code): void {
        http_response_code($status_code);
    }

    /**
     * Renders the error page with a given message and status code.
     *
     * @param string $message     The message to display.
     * @param int    $status_code The HTTP status code.
     */
    protected static function renderErrorPage(string $message, int $status_code): void
	{
        ?><!DOCTYPE html>
		<html lang='en'>
		<head>
		    <meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
		    <meta name="viewport" content="width=device-width">
		    <title>Unavailable</title>
		    <style type="text/css">
		        /* CSS styles */
		    </style>
		</head>
		<body id="page">
		    <div id="error-page" class="">
		        <?php echo $message; ?>
		    </div>
		    <footer align="center">
		        Status Code:<span style="color:#afafaf"><?php echo $status_code; ?></span>
		    </footer>
		</body>
		</html>
		<?php
    }

    /**
     * Terminates the script execution, displaying an error page with a custom message and HTTP status code.
     *
     * @param string $message     The message to display.
     * @param int    $status_code The HTTP status code to send. Defaults to 500.
     */
    public static function exit(string $message, int $status_code = 500): void {
        self::sendStatusCode($status_code);
        self::renderErrorPage($message, $status_code);
        exit;
    }
}
