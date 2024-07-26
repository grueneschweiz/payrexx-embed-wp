# payrexx-embed-wp

This plugin allows to embed an iframe from payrexx (e.g. a donation form) to a wordpress site without having to worry about the size of the frame. We catch the postMessage from the payrexx iframe to get notified about the height of the content and set the iframe height according to it. The result is an iframe without scrollbars that fits seemlessly into the site.

The plugin has been testet with Gutenberg and ACF sites. Other classic sites should work as well.

### Usage
- Copy the folder into your wordpress plugins and enable the plugin
- Put a shortcode into your page or post as follows 
`[payrexx-embed url="https://mysite.payrexx.com/en/pay?cid=abcdefgh"]`
