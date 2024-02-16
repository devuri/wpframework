<?php

namespace WPframework\Component;

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
        ?><!DOCTYPE html><html lang='en'>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
			<meta name="viewport" content="width=device-width">
			<title>Unavailable</title>
			<?php self::getStyles(); ?>
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

	/**
	 * CSS styles
	 */
	private static function getStyles(): void
	{
		?><style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				color: #444;
				max-width: 700px;
				margin: 2em auto;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			}
			h1 {
				border-bottom: 1px solid #dadada;
				clear: both;
				color: #666;
				font-size: 24px;
				margin: 30px 0 0 0;
				padding: 0;
				padding-bottom: 7px;
			}
			footer {
				clear: both;
				color: #cdcdcd;
				margin: 30px 0 0 0;
				padding: 0;
				padding-bottom: 7px;
				font-size: small;
				text-transform: uppercase;
			}
			#error-page {
				background: #fff;
				margin-top: 50px;
				-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
				box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
				padding: 1em 2em;
			}
			#error-page p,
			#error-page .die-message {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#error-page code {
				font-family: Consolas, Monaco, monospace;
			}
			ul li {
				margin-bottom: 10px;
				font-size: 14px ;
			}
			a {
				color: #0073aa;
			}
		</style><?php
	}
}
