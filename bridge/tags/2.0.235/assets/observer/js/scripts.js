'use strict';

import jQuery from 'jquery';

jQuery.tify = {
  observe: function (selector, callback) {
    let observer = new MutationObserver(function (mutations) {
          mutations.forEach(function (mutation) {
            if (mutation.type === 'childList') {
              if (mutation.addedNodes.length >= 1) {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                  let $el = jQuery(mutation.addedNodes[i]);
                  if ($el.is(selector)) {
                    $el.each(callback);
                  } else {
                    $el.find(selector).each(callback);
                  }
                }
              }
            }
          });
        }),
        observerConfig = {attributes: true, childList: true, characterData: true, subtree: true},
        targetNode = document.body;

    return observer.observe(targetNode, observerConfig);
  }
};