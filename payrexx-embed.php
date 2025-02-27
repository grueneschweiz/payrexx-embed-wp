<?php
/**
 * Plugin Name
 *
 * @package           payrexx-community
 * @author            Michael Schär
 * @copyright         2024 GRÜNE Schweiz
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       payrexx-embed
 * Description:       Embed payrexx iframes and resize them to the size of its content
 * Version:           0.1.5
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Michael Schär
 * Author URI:        https://github.com/Michael-Schaer
 * Text Domain:       payrexx-embed
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */


define( 'PAYREXX_EMBED_IFRAME_START',
        '<iframe class="payrexx-embed-frame" src="');

define( 'PAYREXX_EMBED_URL_OPTIONS',
        '&hide_description=1&donation[preselect_interval]=one_time');

define( 'PAYREXX_EMBED_IFRAME_END',
        '" allow="payment *" id="payrexx-embed" width="100%" style="border:none" frameborder="0"></iframe>');
 
function payrexx_embed_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'url' => 'https://en.wikipedia.org/wiki/Instant_answer', // Some random url as default value
        ), $atts, 'payrexx-embed' // shortcode
    );

    // Get the 'url' attribute
    $url = esc_attr($atts['url']);

    // Define the iframe source URL and add options
    $iframe_src = $url . PAYREXX_EMBED_URL_OPTIONS;

    // Return the iframe HTML
    return PAYREXX_EMBED_IFRAME_START . esc_url($iframe_src) . PAYREXX_EMBED_IFRAME_END;
}

// Register the shortcode
add_shortcode('payrexx-embed', 'payrexx_embed_shortcode');

// Enqueue JavaScript and CSS files
function payrexx_embed_enqueue_scripts() {

    // We want to ensure the script is only loaded when the shortcode is used
    // This is why we look at the content of the post before loading any scripts
    
    if(!is_singular()) {
        // not a single page or post
        return;
    }
    
    // Retrieve ACF field content
    if(class_exists('ACF') ) {
        $fields = get_field_objects(get_the_ID());
        
        $has_iframe = false;
        // First is for custom ACF sites (posts, pages), second for events calendar views
        $content = $fields['main_content']['value']['content'] ?? $fields['content']['value'] ?? null;
        if(isset($content) && is_array($content)) {
            foreach ($content as $key => $value) {
                if(array_key_exists('text', $content[$key]) && str_contains($content[$key]['text'], PAYREXX_EMBED_IFRAME_START)) {
                    $has_iframe = true;
                    break;
                }
            }
        } else {
            // no content or wrong template
            return;
        }
        if(!$has_iframe) {
            // no iframe found
            return;
        }
    }
    // For non-ACF users it's much easier to find shortcodes
    else {
        if (!has_shortcode(get_the_content(), 'payrexx-embed')) {
            // shortcode not found in the post content
            return;
        }
    }
    
    // Enqueue JavaScript file with jQuery dependency
    wp_enqueue_script(
        'payrexx-embed',
        plugin_dir_url(__FILE__) . 'payrexx-embed.js',
        array('jquery'), // Dependencies
        '1.0', // Version
        true // Load in footer
    );
    
    // Enqueue CSS
    wp_enqueue_style(
        'payrexx-embed',
        plugin_dir_url(__FILE__) . 'payrexx-embed.css',
        array(), // Dependencies
        '1.0' // Version
    );
}
add_action('wp_enqueue_scripts', 'payrexx_embed_enqueue_scripts');

?>
