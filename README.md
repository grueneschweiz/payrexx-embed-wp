# payrexx-embed-wp

This plugin allows to embed an iframe from payrexx (e.g. a donation form) to a wordpress site without having to worry about the size of the frame. We catch the postMessage from the payrexx iframe to get notified about the height of the content and set the iframe height according to it. The result is an iframe without scrollbars that fits seemlessly into the site.

The plugin has been testet with Gutenberg and ACF sites. Other classic sites should work as well.

### Usage
- Copy the folder into your wordpress plugins and enable the plugin
- Put a shortcode into your page or post as follows

`[payrexx-embed url="https://mysite.payrexx.com/en/pay?cid=abcdefgh"]`

##### Language
Choose the language in the URL that matches your site (e.g. "de", "fr", "it", "en"). Users can always change the language themselves in the dialogue.
`... url="https://mysite.payrexx.com/de/...`

##### Default amount
You can also specify the preselected amount by adding `&donation%5Bpreselect_amount%5D=NUMBER`. For the preselected amount of 200 CHF the whole shortcode would look like this:
`[payrexx-embed url="https://mysite.payrexx.com/en/pay?cid=abcdefgh&donation%5Bpreselect_amount%5D=200"]`

##### Source page
If you want to use one donation form on multiple pages, but still distinguish where the payment came from, use '&invoice_number=mypage.ch/campaignXY'.
Example: 
`[payrexx-embed url="https://mysite.payrexx.com/en/pay?cid=abcdefgh&invoice_number=verts.ch/fr/energiesolaire"]`
Your payments will have the source in their description in the payrexx admin panel under "Transactions".
