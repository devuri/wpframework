<?php
/**
 * This file is part of the Plugin.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

class ThemeSwitcher
{
    public function int() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'settings_init']);
    }

    /**
     * Add a new page under Appearance menu in the admin dashboard
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Theme Switcher', 'theme-switcher'), // Page title
            __('Theme Switcher', 'theme-switcher'), // Menu title
            'manage_options', // Capability
            'theme-switcher', // Menu slug
            [$this, 'settings_page'] // Callback function
        );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Theme Switcher', 'theme-switcher'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('theme_switcher_options_group');
                do_settings_sections('theme-switcher');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Initialize settings
     */
    public function settings_init() {
        register_setting(
            'theme_switcher_options_group', // Option group
            'theme_switcher_options', // Option name
            [$this, 'options_validate'] // Sanitize callback
        );

        add_settings_section(
            'theme_switcher_main', // Section ID
            __('Theme Settings', 'theme-switcher'), // Title
            [$this, 'section_text'], // Callback function
            'theme-switcher' // Page slug
        );

        add_settings_field(
            'theme_switcher_field', // Field ID
            __('Select Theme', 'theme-switcher'), // Title
            [$this, 'setting_input'], // Callback function
            'theme-switcher', // Page slug
            'theme_switcher_main' // Section ID
        );
    }

    /**
     * Section text for the settings page
     */
    public function section_text() {
        echo '<p>' . __('Select the theme you want to activate.', 'theme-switcher') . '</p>';
    }

    /**
     * Render the select input for theme selection
     */
    public function setting_input() {
        $options = get_option('theme_switcher_options');
        $current_theme = isset($options['theme']) ? $options['theme'] : wp_get_theme()->get_stylesheet();

        $themes = wp_get_themes();
        echo '<select name="theme_switcher_options[theme]">';
        foreach ($themes as $theme) {
            $selected = ($theme->get_stylesheet() == $current_theme) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($theme->get_stylesheet()) . '" ' . $selected . '>' . esc_html($theme->get('Name')) . '</option>';
        }
        echo '</select>';
    }

    /**
     * Validate and sanitize options input
     *
     * @param array $input The input to be validated
     * @return array The sanitized input
     */
    public function options_validate($input) {
        $current_theme = wp_get_theme()->get_stylesheet();

        if (isset($input['theme']) && $input['theme'] !== $current_theme) {
            switch_theme($input['theme']);
        }

        return $input;
    }
}
