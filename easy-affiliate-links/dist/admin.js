var EasyAffiliateLinks;
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 6747:
/***/ (() => {

window.EasyAffiliateLinks.cleanUp = {
  init: function init() {
    document.addEventListener('click', function (e) {
      for (var target = e.target; target && target != this; target = target.parentNode) {
        if (target.matches('.eafl-statistics-cleanup #remove_all')) {
          EasyAffiliateLinks.cleanUp.onClickRemoveAll(target, e);
          break;
        }
      }
    }, false);
  },
  onClickRemoveAll: function onClickRemoveAll(el, e) {
    if (el.checked) {
      if (!confirm('Warning: this will remove ALL our click data')) {
        el.checked = false;
      }
    }
  }
};
ready(function () {
  window.EasyAffiliateLinks.cleanUp.init();
});

function ready(fn) {
  if (document.readyState != 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

/***/ }),

/***/ 6073:
/***/ ((module) => {

"use strict";
module.exports = window.jQuery;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

;// CONCATENATED MODULE: ./easy-affiliate-links/assets/js/admin/tools.js
/* provided dependency */ var jQuery = __webpack_require__(6073);

var action = false;
var args = {};
var items = [];
var items_total = 0;

function handle_items() {
  var data = {
    action: 'eafl_' + action,
    security: eafl_admin.nonce,
    items: JSON.stringify(items),
    args: args
  };
  jQuery.post(eafl_admin.ajax_url, data, function (out) {
    if (out.success) {
      items = out.data.items_left;
      update_progress_bar();

      if (items.length > 0) {
        handle_items();
      } else {
        jQuery('#eafl-tools-finished').show();
      }
    } else {
      window.location = out.data.redirect;
    }
  }, 'json');
}

function update_progress_bar() {
  var percentage = (1.0 - items.length / items_total) * 100;
  jQuery('#eafl-tools-progress-bar').css('width', percentage + '%');
}

;
jQuery(document).ready(function ($) {
  // Import Process
  if (typeof window.eafl_tools !== 'undefined') {
    action = eafl_tools.action;
    args = eafl_tools.args;
    items = eafl_tools.items;
    items_total = eafl_tools.items.length;
    handle_items();
  } // Reset settings


  jQuery('#eafl_tools_reset_settings').on('click', function (e) {
    e.preventDefault();

    if (confirm('Are you sure you want to reset all settings?')) {
      var data = {
        action: 'eafl_reset_settings',
        security: eafl_admin.nonce
      };
      jQuery.post(eafl_admin.ajax_url, data, function (out) {
        if (out.success) {
          window.location = out.data.redirect;
        } else {
          alert('Something went wrong.');
        }
      }, 'json');
    }
  });
});
// EXTERNAL MODULE: ./easy-affiliate-links/assets/js/admin/clean-up.js
var clean_up = __webpack_require__(6747);
;// CONCATENATED MODULE: ./easy-affiliate-links/assets/js/admin.js






})();

(EasyAffiliateLinks = typeof EasyAffiliateLinks === "undefined" ? {} : EasyAffiliateLinks).admin = __webpack_exports__;
/******/ })()
;