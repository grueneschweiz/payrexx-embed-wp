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
 * Version:           0.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Michael Schär
 * Author URI:        https://github.com/Michael-Schaer
 * Text Domain:       payrexx-embed
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
function payrexx_embed_shortcode($atts) {
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'url' => 'https://mypage.payrexx.com/en/pay?cid=abcdefgh', // Default value for 'url'
        ), $atts, 'payrexx-embed'
    );

    // Get the 'url' attribute
    $url = esc_attr($atts['url']);

    // Define the iframe source URL using the 'url'
    $iframe_src = $url . "&hide_description=1&donation[preselect_interval]=one_time";

    // Return the iframe HTML
    return '<iframe class="payrexx-embed-frame" src='. esc_url($url) . ' allow="payment *" id="payrexx-embed" width="100%" style="border:none" frameborder="0"></iframe>';
}

// Register the shortcode
add_shortcode('payrexx-embed', 'payrexx_embed_shortcode');

// Enqueue JavaScript and CSS files
function payrexx_embed_enqueue_scripts() {

    // Ensure the script is only loaded when the shortcode is used
    
    if(!is_singular()) {
        // not a single page or post
        return;
    }

    // Retrieve ACF field content
    if(class_exists('ACF') ) {

        $fields = get_field_objects(get_the_ID());
        $fields['main_content'];
        
        $has_iframe = false;
        $content = $fields['main_content']['value']['content'];
        if(isset($content)) {
            foreach ($content as $key => $value) {
                if(str_contains($content[$key]['text'], '<iframe class="payrexx-embed-frame"')) {
                    $has_iframe = true;
                    break;
                }
            }
        } else {
            // no content
            return;
        }
        if(!$has_iframe) {
            // no iframe found
            return;
        }
    }
    // For non-ACF users it's much easier
    else {
        if (!isset($post->post_content) || !has_shortcode($post->post_content, 'payrexx-embed')) {
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

