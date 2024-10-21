<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework;

use WP_User;

class AutoLogin
{
    /**
     * The secret key used for generating and verifying signatures.
     *
     * @var null|string
     */
    protected $secret_key = null;

    /**
     * Holds the login service parameters.
     *
     * @var null|array
     */
    protected $login_service = null;

    /**
     * The URL of the home page of the WordPress site.
     *
     * @var null|string
     */
    protected $home_url = null;

    /**
     * The WordPress environment setup.
     *
     * @var null|string
     */
    protected $environment_type = null;

    /**
     * environments that allow autologin.
     *
     * @var array
     */
    protected $environments;

    /**
     * The URL of the user's admin area (dashboard).
     *
     * @var null|string
     */
    protected $user_admin_url;

    /**
     * AutoLogin constructor.
     *
     * @return void This method does not return any value.
     */
    public function __construct(?string $secret_key = null, ?string $environment_type = null, array $environments = [ 'sec', 'secure', 'prod', 'production' ])
    {
        $this->secret_key       = $secret_key;
        $this->environment_type = $environment_type;
        $this->environments     = $environments;
        $this->home_url         = home_url('/');
        $this->user_admin_url   = user_admin_url();
        $this->login_service    = [];
    }

    /**
     * Registers the auto-login action to handle automatic logins when the 'init' action is triggered.
     *
     * @return void This method does not return any value.
     */
    public function register_login_action(): void
    {
        if ($this->secret_key) {
            add_action('init', [ $this, 'handle_auto_login' ]);
        }
    }

    /**
     * Initializes the automatic login functionality.
     *
     * @return void This method does not return any value.
     */
    public static function init(string $secret_key, string $environment_type): void
    {
        $auto_login = new self($secret_key, $environment_type);
        $auto_login->register_login_action();
    }

    /**
     * Handles the automatic login process based on the provided query parameters.
     *
     * @return void This method does not return any value.
     */
    public function handle_auto_login(): void
    {
        // Get the current timestamp.
        $current_timestamp = time();

        // do not allow production login.
        if (\in_array($this->environment_type, $this->environments, true)) {
            error_log('auto login will not work in this environment: ' . $this->environment_type);

            return;
        }

        // WARNING | Processing form data without nonce verification.
        if (isset($_GET['token']) && isset($_GET['sig'])) {
            $this->login_service = [
                'token'    => static::get_query('token'),
                'time'     => static::get_query('time'),
                'username' => static::get_query('username'),
                'site_id'  => static::get_query('site_id'),
            ];

            // Check if the URL has expired (more than 60 seconds old).
            if ($current_timestamp - (int) $this->login_service['time'] > 60) {
                wp_die('login expired');
            }

            $signature = base64_decode(static::get_query('sig'), true);

            if (\is_null($this->login_service['username']) || ! $signature) {
                error_log('auto login username invalid');

                return;
            }

            if ( ! $this->verify_signature($signature)) {
                wp_safe_redirect($this->home_url);

                return;
            }

            // WP_User object on success, false on failure.
            $user = get_user_by('login', $this->login_service['username']);

            if (false === $user) {
                $user = null;
            }

            if ( ! $this->wp_user_exists($user)) {
                wp_die('User not found.');
            }

            if ($user) {
                static::authenticate($user);
                wp_safe_redirect($this->user_admin_url);
                exit;
            }

            wp_safe_redirect($this->home_url);
            exit;
        }// end if
    }

    /**
     * Determines whether the user exists in the database.
     *
     * @param null|WP_User $user The WP_User object
     *
     * @return null|bool Null no user, True if user exists in the database, false if not.
     */
    protected function wp_user_exists(?WP_User $user): ?bool
    {
        if (\is_null($user)) {
            return null;
        }

        if ($user->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Verifies the authenticity of the signature for the auto-login request.
     *
     * @param string $signature The signature to be verified for the auto-login request.
     *
     * @return bool Returns true if the provided signature matches the expected signature, false otherwise.
     */
    protected function verify_signature($signature)
    {
        $http_query = http_build_query($this->login_service, '', '&');

        $generatedSignature = hash_hmac('sha256', $http_query, $this->secret_key);

        return hash_equals($generatedSignature, $signature);
    }

    /**
     * Authenticates the user and performs necessary actions after successful authentication.
     *
     * @param WP_User $user The WP_User object representing the user to authenticate.
     *
     * @return void This method does not return any value.
     */
    protected static function authenticate(WP_User $user): void
    {
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, false, is_ssl());
        update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
        do_action('wpenv_auto_login', $user->user_login, $user);
        do_action('wp_login', $user->user_login, $user);
    }

    /**
     * @param string $req_input The name of the query parameter to retrieve and sanitize.
     *
     * @return null|string The sanitized value of the specified query parameter, or null if the parameter is not set.
     */
    protected static function get_query(string $req_input)
    {
        if (isset($_GET[$req_input])) {
            return sanitize_text_field(wp_unslash($_GET[$req_input]));
        }

        return null;
    }
}
