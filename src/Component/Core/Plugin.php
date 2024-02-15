<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace WPframework\Component\Core;

use WP_User;
use WPframework\Component\Core\Settings\AdminSettingsPage;
use WPframework\Component\Core\Traits\ActivateElementorTrait;
use WPframework\Component\Core\Traits\AdminBarMenuTrait;

class Plugin
{
    use AdminBarMenuTrait;
    public const ADMIN_BAR_MENU_ID = 'wp-app-environment';

    protected $env_menu_id;
    protected $http_env_type;
    protected $wp_sudo_admin;
    protected $admin_group;
    protected $tenant_id;

    public function __construct()
    {
        // define basic app settings
        $this->define_wpframework_init();

        /*
         * The tenant ID for the application.
         *
         * This sets the tenant ID based on the environment configuration. The `APP_TENANT_ID`
         * can be configured in the `.env` file. Setting `APP_TENANT_ID` to false will disable the
         * custom uploads directory behavior that is typically used in a multi-tenant setup. In a
         * multi-tenant environment, `APP_TENANT_ID` is required and must always be set. The method
         * uses `envTenantId()` function to retrieve the tenant ID from the environment settings.
         */
        $this->tenant_id = envTenantId();

        // Adds security headers if the SET_SECURITY_HEADERS constant is set and true.
        if ( \defined( 'SET_SECURITY_HEADERS' ) && SET_SECURITY_HEADERS === true ) {
            add_action( 'send_headers', [ $this, 'security_headers' ] );
        }

        // Disable User Notification of Password Change Confirmation
        apply_filters(
            'send_email_change_email',
            function ( $user, $userdata ) {
                return env( 'SEND_EMAIL_CHANGE_EMAIL' ) ? env( 'SEND_EMAIL_CHANGE_EMAIL' ) : true;
            }
        );

        // @phpstan-ignore-next-line
        add_filter(
            'the_generator',
            function() {
                // Remove wp version.
                return null;
            }
        );

        // separate uploads for multi tenant.
        if ( ! \is_null( $this->tenant_id ) || false !== $this->tenant_id ) {
            add_filter( 'upload_dir', [ $this, 'set_upload_directory' ] );
        }

        // multi tenant setup for plugins.
        if ( \defined( 'ALLOW_MULTITENANT' ) && ALLOW_MULTITENANT === true ) {
            // Remove the delete action link for plugins.
            add_filter(
                'plugin_action_links',
                function ( $actions, $plugin_file, $plugin_data, $context ) {
                    if ( \array_key_exists( 'delete', $actions ) ) {
                        unset( $actions['delete'] );
                    }

                    return $actions;
                },
                999,
                4
            );

            // Allow if user has 'manage_tenant'.
            add_filter( 'user_has_cap', [ $this, 'manage_tenant_install_plugins' ], 999, 4 );

            add_filter(
                'all_plugins',
                function( $all_plugins ) {
                    $allowed_plugins = [
                        'tenant-manager/tenant-manager.php',
                    ];

                    // Iterate over all plugins and unset those not in the allowed list
                    foreach ( $all_plugins as $plugin_path => $plugin_info ) {
                        if ( \in_array( $plugin_path, $allowed_plugins, true ) ) {
                            unset( $all_plugins[ $plugin_path ] );
                        }
                    }

                    return $all_plugins;
                }
            );
        }// end if

        // Add the env type to admin bar.
        add_action( 'admin_bar_menu', [ $this, 'app_env_admin_bar_menu' ], 1199 );

        // custom theme directory.
        if ( \defined( 'APP_THEME_DIR' ) ) {
            register_theme_directory( APP_THEME_DIR );
        }

        /*
         * Prevent Admin users from deactivating plugins.
         *
         * While this will remove the deactivation link it does NOT prevent deactivation
         * It will only hide the link to deactivate.
         */
        add_filter(
            'plugin_action_links',
            function ( $actions, $plugin_file, $plugin_data, $context ) {
                if ( ! \defined( 'CAN_DEACTIVATE_PLUGINS' ) ) {
                    return $actions;
                }

                // if set to true users should be allowed to deactivate plugins.
                if ( true === CAN_DEACTIVATE_PLUGINS ) {
                    return $actions;
                }

                if ( \array_key_exists( 'deactivate', $actions ) ) {
                    unset( $actions['deactivate'] );
                }

                return $actions;
            },
            10,
            4
        );

        $this->add_wpframework_events();

        // Add some special admin pages.
        new AdminSettingsPage(
            'Composer plugins',
            function (): void {
                ?><div class="wrap">
					<h2>Composer Plugins List</h2>
					<?php
                    dump( packagistPluginsList() );
					?>
				</div>
				<?php
            }
        );
    }

    public static function init(): self
    {
        return new self();
    }

    /**
     * Sets the upload directory to a tenant-specific location.
     *
     * This function modifies the default WordPress upload directory paths
     * to store tenant-specific uploads in a separate folder based on the tenant ID.
     * It ensures that each tenant's uploads are organized and stored in an isolated directory.
     *
     * @param array $dir The array containing the current upload directory's path and URL.
     *
     * @return array The modified array with the new upload directory's path and URL for the tenant.
     */
    public function set_upload_directory( $dir )
    {
        $custom_dir = '/tenant/' . $this->tenant_id . '/uploads';

        // Set the base directory and URL for the uploads.
        $dir['basedir'] = WP_CONTENT_DIR . $custom_dir;
        $dir['baseurl'] = content_url() . $custom_dir;

        // Append the subdirectory to the base path and URL, if any.
        $dir['path'] = $dir['basedir'] . $dir['subdir'];
        $dir['url']  = $dir['baseurl'] . $dir['subdir'];

        return $dir;
    }

    /**
     * Modifies user capabilities to allow users with 'manage_tenant' capability to install plugins.
     *
     * This function checks if the user's requested capabilities include 'install_plugins' and
     * then checks if the user has the 'manage_tenant' capability. If they do, the function
     * allows them to install plugins by modifying the `$allcaps` array.
     *
     * @param array   $allcaps An associative array of all the user's capabilities.
     * @param array   $caps    Actual capabilities being checked.
     * @param array   $args    Adds context to the capabilities being checked, typically starting with the capability name.
     * @param WP_User $user    The user object.
     *
     * @return array Modified array of user capabilities.
     */
    public function manage_tenant_install_plugins( $allcaps, $caps, $args, $user )
    {
        if ( isset( $args[0] ) && 'install_plugins' === $args[0] ) {
            $allcaps['install_plugins'] = ! empty( $allcaps['manage_tenant'] );
        }

        return $allcaps;
    }

    /**
     * Custom admin footer text.
     *
     * @return string The formatted footer text with dynamic content.
     */
    public function framework_footer_label(): string
    {
        $home_url   = esc_url( home_url() );
        $date_year  = gmdate( 'Y' );
        $site_name  = esc_html( get_bloginfo( 'name' ) );
        $tenant_id  = esc_html( envTenantId() );
        $powered_by = esc_html( apply_filters( 'wpframework_powered_by', __( 'Powered by WPframework.', 'wp-framework' ) ) );

        return wp_kses_post( "&copy; $date_year <a href=\"$home_url\" target=\"_blank\">$site_name</a> " . __( 'All Rights Reserved.', 'wp-framework' ) . " $powered_by $tenant_id" );
    }

    /**
     * Schedules custom WP Framework events.
     *
     * Initializes a new ScheduledEvent instance for the WP Framework app event,
     * setting it to trigger on an hourly basis. The event triggers a custom action 'wpframework_events'.
     */
    protected function add_wpframework_events(): void
    {
        $app_events = new ScheduledEvent(
            'wpframework_app_event',
            function(): void {
                do_action( 'wpframework_events' );
            },
            'hourly'
        );

        $app_events->add_wpframework_event();
    }

    protected function security_headers(): void
    {
        $home_domain = $this->extract_domain( WP_HOME );

        header( 'Access-Control-Allow-Origin: https://www.google-analytics.com' );
        header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: same-origin' );

        // Consolidated Content-Security-Policy
        $csp = "script-src 'self' *.$home_domain https://www.google-analytics.com https://*.google-analytics.com https://*.googlesyndication.com https://*.google.com https://*.quantcount.com https://*.facebook.net https://*.gubagoo.io https://*.hotjar.com https://*.inspectlet.com https://*.pingdom.net https://*.twitter.com https://*.quantserve.com https://*.googletagservices.com https://*.googleapis.com; " .
               "frame-ancestors 'self' https://$home_domain; " .
               "default-src 'self'; " .
        // Consider adding a default-src directive for a fallback
               "object-src 'none';";
        // Disallow plugins (Flash, Silverlight, etc.)

        header( "Content-Security-Policy: $csp" );
    }

    /**
     * Extracts the domain from a URL using an optional list of public suffixes.
     *
     * If a suffix list is provided, the function attempts to match the longest possible suffix in the domain,
     * which helps in accurately determining the registrable part of the domain. If the suffix list is empty,
     * the function defaults to extracting the last two parts of the hostname, which may not be accurate for
     * domains with multiple subdomains or certain country-code top-level domains (ccTLDs).
     *
     * The function now accepts a second parameter $suffixList, which is an array of domain suffixes (e.g., ['co.uk', 'com', 'org']).
     *
     * @param string $url        The URL to extract the domain from.
     * @param array  $suffixList An optional array of public suffixes to improve domain extraction accuracy.
     *
     * @return null|string The extracted domain or null if extraction fails.
     */
    protected function extract_domain( string $url, array $suffixList = [] ): ?string
    {
        $parsedUrl = wp_parse_url( $url );

        if ( ! isset( $parsedUrl['host'] ) ) {
            return null;
        }

        // Reverse the host parts for easier comparison to the suffix list.
        $hostParts = array_reverse( explode( '.', $parsedUrl['host'] ) );

        if ( ! empty( $suffixList ) ) {
            foreach ( $suffixList as $suffix ) {
                $suffixParts = array_reverse( explode( '.', $suffix ) );
                $match       = true;

                foreach ( $suffixParts as $index => $part ) {
                    if ( ! isset( $hostParts[ $index ] ) || $hostParts[ $index ] !== $part ) {
                        $match = false;

                        break;
                    }
                }

                if ( $match ) {
                    // Add one to the suffix parts count for the domain part.
                    $domainPartsCount = \count( $suffixParts ) + 1;
                    if ( isset( $hostParts[ $domainPartsCount ] ) ) {
                        // Reconstruct the domain from the matched parts.
                        return implode( '.', array_reverse( \array_slice( $hostParts, 0, $domainPartsCount ) ) );
                    }
                }
            }//end foreach
        }// end if

        // Fallback to the simpler method if no match is found in the suffix list or if the list is empty.
        $numParts = \count( $hostParts );
        if ( $numParts >= 2 ) {
            return implode( '.', array_reverse( \array_slice( $hostParts, 0, 2 ) ) );
        }

        return null;
    }

    protected function define_wpframework_init(): void
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && WP_SUDO_ADMIN ) {
            $this->wp_sudo_admin = (int) WP_SUDO_ADMIN;
        } else {
            $this->wp_sudo_admin = null;
        }

        if ( \defined( 'SUDO_ADMIN_GROUP' ) && SUDO_ADMIN_GROUP ) {
            $this->admin_group = SUDO_ADMIN_GROUP;
        } else {
            $this->admin_group = null;
        }

        if ( \defined( 'HTTP_ENV_CONFIG' ) && HTTP_ENV_CONFIG ) {
            $this->http_env_type = strtoupper( HTTP_ENV_CONFIG );
        } else {
            $this->http_env_type = null;
        }

        // admin bar menu ID.
        $this->env_menu_id = self::ADMIN_BAR_MENU_ID;

        // allows auto login.
        if ( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ) ) {
            AutoLogin::init( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ), env( 'WP_ENVIRONMENT_TYPE' ) );
        }

        if ( env( 'DISABLE_WP_APPLICATION_PASSWORDS' ) ) {
            add_filter( 'wp_is_application_passwords_available', '__return_false' );
        }

        /*
         * Adds a custom label to the WordPress admin footer.
         *
         * Utilizes the 'admin_footer_text' filter to append a custom label, including the site name,
         * current year, and a custom message, to the admin footer area.
         */
        add_filter( 'admin_footer_text', [ $this, 'framework_footer_label' ] );
    }
}
