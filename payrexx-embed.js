var transaction = null;
var lastPostMessageHeight = 0;

var updateIframeHeight = function() {
  jQuery('iframe').css('height', lastPostMessageHeight + 'px');
};

var scrollPage = function(offset) {
  var positionToScrollTo = jQuery('iframe').position().top + offset;
  jQuery('body, html').animate({scrollTop: positionToScrollTo}, 400, 'linear', function() {
    jQuery(window).trigger('scroll')
  });
};

var postMessage = function(e) {
  if (typeof e.data === 'string') {
    try {
      var data = JSON.parse(e.data);
    } catch (e) {
          console.log("Payrexx-embed: Error on postMessage: " + e);
    }
    if (data && data.payrexx) {
      jQuery.each(data.payrexx, function(name, value) {
        switch (name) {
          case 'height':
            lastPostMessageHeight = parseInt(value);
            updateIframeHeight();
            break;
          case 'top':
            scrollPage(parseInt(value));
            break;
        }
      });
    }
  }
};

jQuery(document).ready(function (){

    window.addEventListener('message', postMessage, false);

    jQuery('iframe').on('load', function () {
      jQuery(this)[0].contentWindow.postMessage(JSON.stringify({origin: window.location.origin}), jQuery('iframe').attr('src'));
      jQuery(window).resize(updateIframeHeight);
      updateIframeHeight();
    });
});

