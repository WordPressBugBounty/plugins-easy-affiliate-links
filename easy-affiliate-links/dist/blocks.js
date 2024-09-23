var EasyAffiliateLinks;
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
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
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

;// CONCATENATED MODULE: ./easy-affiliate-links/assets/js/blocks/affiliate-link/index.js
var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var _wp$components = wp.components,
    Disabled = _wp$components.Disabled,
    ToolbarGroup = _wp$components.ToolbarGroup,
    ToolbarButton = _wp$components.ToolbarButton;
var Fragment = wp.element.Fragment; // Backwards compatibility.

var InspectorControls;
var BlockControls;
var AlignmentToolbar;

if (wp.hasOwnProperty('blockEditor')) {
  InspectorControls = wp.blockEditor.InspectorControls;
  BlockControls = wp.blockEditor.BlockControls;
  AlignmentToolbar = wp.blockEditor.AlignmentToolbar;
} else {
  InspectorControls = wp.editor.InspectorControls;
  BlockControls = wp.editor.BlockControls;
  AlignmentToolbar = wp.editor.AlignmentToolbar;
}

var ServerSideRender;

if (wp.hasOwnProperty('serverSideRender')) {
  ServerSideRender = wp.serverSideRender;
} else {
  ServerSideRender = wp.components.ServerSideRender;
}


registerBlockType('easy-affiliate-links/easy-affiliate-link', {
  title: __('Easy Affiliate Link'),
  description: __('Display an EAFL affiliate link.'),
  icon: 'admin-links',
  keywords: ['eafl', 'affiliate', 'link'],
  category: 'common',
  supports: {
    html: false
  },
  transforms: {
    from: [{
      type: 'shortcode',
      tag: 'eafl',
      attributes: {
        id: {
          type: 'string',
          shortcode: function shortcode(_ref) {
            var _ref$named$id = _ref.named.id,
                id = _ref$named$id === void 0 ? '' : _ref$named$id;
            return id.replace('id', '');
          }
        },
        text: {
          type: 'string',
          shortcode: function shortcode(_ref2) {
            var _ref2$named$text = _ref2.named.text,
                text = _ref2$named$text === void 0 ? '' : _ref2$named$text;
            return text.replace('text', '');
          }
        }
      }
    }]
  },
  edit: function edit(props) {
    var attributes = props.attributes,
        setAttributes = props.setAttributes;

    var selectAffiliateLink = function selectAffiliateLink() {
      EAFL_Modal.open('insert', {
        insertCallback: function insertCallback(link, text) {
          if (!text) {
            text = 'affiliate link';
          }

          setAttributes({
            id: '' + link.id,
            type: link.type,
            text: text,
            updated: Date.now()
          });
        },
        selectedText: ''
      });
    }; // Open modal if no ID is selected yet.


    if (!attributes.id) {
      selectAffiliateLink();
    }

    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(BlockControls, null, /*#__PURE__*/React.createElement(AlignmentToolbar, {
      value: attributes.textAlign,
      onChange: function onChange(nextAlign) {
        setAttributes({
          textAlign: nextAlign
        });
      }
    }), /*#__PURE__*/React.createElement(ToolbarGroup, null, /*#__PURE__*/React.createElement(ToolbarButton, {
      icon: "admin-links",
      className: "eafl-link-button",
      label: __('Edit Affiliate Link'),
      onClick: function onClick() {
        EAFL_Modal.open('edit', {
          linkId: attributes.id,
          saveCallback: function saveCallback() {
            setAttributes({
              updated: Date.now()
            });
          }
        });
      }
    }), 'text' === attributes.type && /*#__PURE__*/React.createElement(ToolbarButton, {
      icon: "edit",
      label: __('Edit Link Text'),
      onClick: function onClick() {
        EAFL_Modal.open('text', {
          linkId: attributes.id,
          text: attributes.text,
          changeCallback: function changeCallback(newText, id) {
            setAttributes({
              text: newText,
              updated: Date.now()
            });
          }
        });
      }
    }), /*#__PURE__*/React.createElement(ToolbarButton, {
      icon: "update",
      label: __('Change Affiliate Link'),
      onClick: selectAffiliateLink
    }))), /*#__PURE__*/React.createElement(Disabled, null, /*#__PURE__*/React.createElement(ServerSideRender, {
      block: "easy-affiliate-links/easy-affiliate-link",
      attributes: attributes
    })));
  },
  save: function save(props) {
    return null;
  }
});
;// CONCATENATED MODULE: ./easy-affiliate-links/assets/js/blocks/affiliate-link-inline/index.js
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }



var affiliate_link_inline_ = wp.i18n.__;
var affiliate_link_inline_wp$components = wp.components,
    affiliate_link_inline_ToolbarGroup = affiliate_link_inline_wp$components.ToolbarGroup,
    affiliate_link_inline_ToolbarButton = affiliate_link_inline_wp$components.ToolbarButton;
var _wp$richText = wp.richText,
    registerFormatType = _wp$richText.registerFormatType,
    getTextContent = _wp$richText.getTextContent,
    applyFormat = _wp$richText.applyFormat,
    removeFormat = _wp$richText.removeFormat,
    slice = _wp$richText.slice,
    create = _wp$richText.create,
    insert = _wp$richText.insert;
var Component = wp.element.Component; // Backwards compatibility.

var affiliate_link_inline_BlockControls;

if (wp.hasOwnProperty('blockEditor')) {
  affiliate_link_inline_BlockControls = wp.blockEditor.BlockControls;
} else {
  affiliate_link_inline_BlockControls = wp.editor.BlockControls;
}

var affiliate_link_inline_name = 'easy-affiliate-links/affiliate-link';
registerFormatType(affiliate_link_inline_name, {
  title: affiliate_link_inline_('Affiliate Link'),
  tagName: 'a',
  className: 'eafl-link',
  attributes: {
    eaflId: 'data-eafl-id',
    eaflText: 'data-eafl-text',
    url: 'href',
    target: 'target'
  },
  edit: /*#__PURE__*/function (_Component) {
    _inherits(LinkEdit, _Component);

    var _super = _createSuper(LinkEdit);

    function LinkEdit() {
      var _this;

      _classCallCheck(this, LinkEdit);

      _this = _super.apply(this, arguments);
      _this.addLink = _this.addLink.bind(_assertThisInitialized(_this));
      _this.editLink = _this.editLink.bind(_assertThisInitialized(_this));
      _this.addLinkCallback = _this.addLinkCallback.bind(_assertThisInitialized(_this));
      _this.onRemoveFormat = _this.onRemoveFormat.bind(_assertThisInitialized(_this));
      return _this;
    }

    _createClass(LinkEdit, [{
      key: "addLink",
      value: function addLink() {
        var value = this.props.value;
        var selectedText = getTextContent(slice(value));
        EAFL_Modal.open('insert', {
          insertCallback: this.addLinkCallback,
          selectedText: selectedText
        });
      }
    }, {
      key: "addLinkCallback",
      value: function addLinkCallback(link, text) {
        var _this$props = this.props,
            value = _this$props.value,
            onChange = _this$props.onChange;

        if (!text) {
          text = 'affiliate link';
        }

        var format = {
          type: affiliate_link_inline_name,
          attributes: {
            url: link.url,
            eaflId: '' + link.id,
            // Make sure this is a string.
            eaflText: text
          }
        }; // TODO New tab and nofollow

        var toInsert = applyFormat(create({
          text: text
        }), format, 0, text.length);
        onChange(insert(value, toInsert));
      }
    }, {
      key: "editLink",
      value: function editLink() {
        var eaflId = this.props.activeAttributes.eaflId;
        EAFL_Modal.open('edit', {
          linkId: eaflId
        });
      }
    }, {
      key: "onRemoveFormat",
      value: function onRemoveFormat() {
        var _this$props2 = this.props,
            value = _this$props2.value,
            onChange = _this$props2.onChange;
        onChange(removeFormat(value, affiliate_link_inline_name));
      }
    }, {
      key: "render",
      value: function render() {
        var _this$props3 = this.props,
            isActive = _this$props3.isActive,
            activeAttributes = _this$props3.activeAttributes,
            value = _this$props3.value,
            onChange = _this$props3.onChange;
        return /*#__PURE__*/React.createElement(affiliate_link_inline_BlockControls, null, /*#__PURE__*/React.createElement(affiliate_link_inline_ToolbarGroup, null, !isActive && /*#__PURE__*/React.createElement(affiliate_link_inline_ToolbarButton, {
          icon: "admin-links",
          className: "eafl-link-button",
          label: affiliate_link_inline_('Affiliate Link'),
          onClick: this.addLink
        }), isActive && /*#__PURE__*/React.createElement(affiliate_link_inline_ToolbarButton, {
          isPressed: true,
          icon: "admin-links",
          className: "eafl-link-button",
          label: affiliate_link_inline_('Edit Affiliate Link'),
          onClick: this.editLink
        }), isActive && /*#__PURE__*/React.createElement(affiliate_link_inline_ToolbarButton, {
          isPressed: true,
          icon: "editor-unlink",
          className: "eafl-link-button",
          label: affiliate_link_inline_('Unlink Affiliate Link'),
          onClick: this.onRemoveFormat
        })));
      }
    }]);

    return LinkEdit;
  }(Component)
});
;// CONCATENATED MODULE: ./easy-affiliate-links/assets/js/blocks.js


(EasyAffiliateLinks = typeof EasyAffiliateLinks === "undefined" ? {} : EasyAffiliateLinks).blocks = __webpack_exports__;
/******/ })()
;