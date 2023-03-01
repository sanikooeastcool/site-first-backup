/*!
 * Variation Swatches for WooCommerce 
 * 
 * Author: Emran Ahmed ( emran.bd.08@gmail.com ) 
 * Date: 6/16/2022, 4:46:07 PM
 * Released under the GPLv3 license.
 */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/WooVariationSwatches.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

// ================================================================
// WooCommerce Variation Swatches

/*global _, wc_add_to_cart_variation_params, woo_variation_swatches_options */
// ================================================================
var WooVariationSwatches = function ($) {
  var Default = {};

  var WooVariationSwatches = /*#__PURE__*/function () {
    function WooVariationSwatches(element, config) {
      _classCallCheck(this, WooVariationSwatches);

      // Assign
      this._element = element;
      this.$element = $(element);
      this._config = $.extend({}, Default, config);
      this.product_variations = this.$element.data('product_variations') || [];
      this.is_ajax_variation = this.product_variations.length < 1;
      this.product_id = this.$element.data('product_id');
      this.reset_variations = this.$element.find('.reset_variations');
      this.attributeFields = this.$element.find('.variations select');
      this.selected_item_template = "<span class=\"woo-selected-variation-item-name\" data-default=\"\"></span>";
      this.$element.addClass('wvs-loaded'); // Call

      this.init();
      this.update(); // Trigger
      // $(document).trigger('woo_variation_swatches', [this.$element])
    }

    _createClass(WooVariationSwatches, [{
      key: "isAjaxVariation",
      value: function isAjaxVariation() {
        //this.product_variations = this.$element.data('product_variations') || []
        return this.is_ajax_variation; // = this.product_variations.length < 1
      }
    }, {
      key: "init",
      value: function init() {
        this.prepareLabel();
        this.prepareItems();
        this.setupItems();
        this.setupEvents();
        this.setUpStockInfo();
      }
    }, {
      key: "prepareLabel",
      value: function prepareLabel() {
        var _this = this;

        // Append Selected Item Template
        if (woo_variation_swatches_options.show_variation_label) {
          this.$element.find('.variations .label').each(function (index, el) {
            $(el).append(_this.selected_item_template);
          });
        }
      }
    }, {
      key: "prepareItems",
      value: function prepareItems() {
        this.$element.find('ul.variable-items-wrapper').each(function (i, el) {
          $(el).parent().addClass('woo-variation-items-wrapper');
        });
      }
    }, {
      key: "setupItems",
      value: function setupItems() {
        var _this2 = this;

        var self = this;
        this.$element.find('ul.variable-items-wrapper').each(function (i, element) {
          var selected = '';
          var $selected_variation_item = $(element).parent().prev().find('.woo-selected-variation-item-name');
          var select = $(element).parent().find('select.woo-variation-raw-select');
          var options = select.find('option');
          var disabled = select.find('option:disabled');
          var out_of_stock = select.find('option.enabled.out-of-stock');
          var current = select.find('option:selected');
          var eq = select.find('option').eq(1);
          var selects = [];
          var disabled_selects = [];
          var out_of_stocks = []; // All Options

          options.each(function () {
            if ($(this).val() !== '') {
              selects.push($(this).val());
              selected = current.length === 0 ? eq.val() : current.val();
            }
          }); // Disabled

          disabled.each(function () {
            if ($(this).val() !== '') {
              disabled_selects.push($(this).val());
            }
          }); // Out Of Stocks

          out_of_stock.each(function () {
            if ($(this).val() !== '') {
              out_of_stocks.push($(this).val());
            }
          });

          var in_stocks = _.difference(selects, disabled_selects);

          _this2.setupItem(element, selected, in_stocks, out_of_stocks, $selected_variation_item);
        });
      }
    }, {
      key: "setupItem",
      value: function setupItem(element, selected, in_stocks, out_of_stocks, $selected_variation_item) {
        var _this3 = this;

        // Mark Selected
        $(element).find('li.variable-item').each(function (index, el) {
          var attribute_value = $(el).attr('data-value');
          var attribute_title = $(el).attr('data-title'); // Resetting LI

          $(el).removeClass('selected disabled no-stock').addClass('disabled');
          $(el).attr('aria-checked', 'false');
          $(el).attr('tabindex', '-1');
          $(el).attr('data-wvstooltip-out-of-stock', '');
          $(el).find('input.variable-item-radio-input:radio').prop('disabled', true).prop('checked', false); // To Prevent blink

          if (selected.length < 1 && woo_variation_swatches_options.show_variation_label) {
            $selected_variation_item.text('');
          } // Ajax variation


          if (_this3.isAjaxVariation()) {
            $(el).find('input.variable-item-radio-input:radio').prop('disabled', false);
            $(el).removeClass('selected disabled no-stock'); // Selected

            if (attribute_value === selected) {
              $(el).addClass('selected');
              $(el).attr('aria-checked', 'true');
              $(el).attr('tabindex', '0');
              $(el).find('input.variable-item-radio-input:radio').prop('disabled', false).prop('checked', true);

              if (woo_variation_swatches_options.show_variation_label) {
                $selected_variation_item.text("".concat(woo_variation_swatches_options.variation_label_separator, " ").concat(attribute_title));
              }

              $(el).trigger('wvs-item-updated', [selected, attribute_value]);
            }
          } else {
            // Default Selected
            // We can't use es6 includes for IE11
            // in_stocks.includes(attribute_value)
            // _.contains(in_stocks, attribute_value)
            // _.includes(in_stocks, attribute_value)
            if (_.includes(in_stocks, attribute_value)) {
              $(el).removeClass('selected disabled');
              $(el).removeAttr('aria-hidden');
              $(el).attr('tabindex', '0');
              $(el).find('input.variable-item-radio-input:radio').prop('disabled', false); // Selected

              if (attribute_value === selected) {
                $(el).addClass('selected');
                $(el).attr('aria-checked', 'true');
                $(el).find('input.variable-item-radio-input:radio').prop('checked', true);

                if (woo_variation_swatches_options.show_variation_label) {
                  $selected_variation_item.text("".concat(woo_variation_swatches_options.variation_label_separator, " ").concat(attribute_title));
                }

                $(el).trigger('wvs-item-updated', [selected, attribute_value]);
              }
            } // Out of Stock


            if (_.includes(out_of_stocks, attribute_value) && woo_variation_swatches_options.clickable_out_of_stock) {
              $(el).removeClass('disabled').addClass('no-stock');
              $(el).attr('data-wvstooltip-out-of-stock', woo_variation_swatches_options.out_of_stock_tooltip_text);
            }
          }
        });
      }
    }, {
      key: "setupEvents",
      value: function setupEvents() {
        var self = this;
        this.$element.find('ul.variable-items-wrapper').each(function (i, element) {
          var select = $(element).parent().find('select.woo-variation-raw-select'); // Trigger Select event based on list

          if (woo_variation_swatches_options.clear_on_reselect) {
            // Non Selected Item Should Select
            $(element).on('click.wvs', 'li.variable-item:not(.selected):not(.radio-variable-item)', function (event) {
              event.preventDefault();
              event.stopPropagation();
              var value = $(this).data('value');
              select.val(value).trigger('change');
              select.trigger('click'); // select.trigger('focusin')

              if (woo_variation_swatches_options.is_mobile) {//     select.trigger('touchstart')
              } // $(this).trigger('focus') // Mobile tooltip


              $(this).trigger('wvs-selected-item', [value, select, self.$element]); // Custom Event for li
            }); // Selected Item Should un Select

            $(element).on('click.wvs', 'li.variable-item.selected:not(.radio-variable-item)', function (event) {
              event.preventDefault();
              event.stopPropagation();
              var value = $(this).val();
              select.val('').trigger('change');
              select.trigger('click'); //select.trigger('focusin')

              if (woo_variation_swatches_options.is_mobile) {//   select.trigger('touchstart')
              } // $(this).trigger('focus') // Mobile tooltip


              $(this).trigger('wvs-unselected-item', [value, select, self.$element]); // Custom Event for li
            }); // RADIO
            // On Click trigger change event on Radio button

            $(element).on('click.wvs', 'input.variable-item-radio-input:radio', function (event) {
              event.stopPropagation();
              $(this).trigger('change.wvs', {
                radioChange: true
              });
            });
            $(element).on('change.wvs', 'input.variable-item-radio-input:radio', function (event, params) {
              event.preventDefault();
              event.stopPropagation();

              if (params && params.radioChange) {
                var value = $(this).val();
                var is_selected = $(this).parent('li.radio-variable-item').hasClass('selected');

                if (is_selected) {
                  select.val('').trigger('change');
                  $(this).parent('li.radio-variable-item').trigger('wvs-unselected-item', [value, select, self.$element]); // Custom Event for li
                } else {
                  select.val(value).trigger('change');
                  $(this).parent('li.radio-variable-item').trigger('wvs-selected-item', [value, select, self.$element]); // Custom Event for li
                }

                select.trigger('click'); //select.trigger('focusin')

                if (woo_variation_swatches_options.is_mobile) {//    select.trigger('touchstart')
                }
              }
            });
          } else {
            $(element).on('click.wvs', 'li.variable-item:not(.radio-variable-item)', function (event) {
              event.preventDefault();
              event.stopPropagation();
              var value = $(this).data('value');
              select.val(value).trigger('change');
              select.trigger('click'); // select.trigger('focusin')

              if (woo_variation_swatches_options.is_mobile) {//   select.trigger('touchstart')
              } // $(this).trigger('focus') // Mobile tooltip


              $(this).trigger('wvs-selected-item', [value, select, self._element]); // Custom Event for li
            }); // Radio

            $(element).on('change.wvs', 'input.variable-item-radio-input:radio', function (event) {
              event.preventDefault();
              event.stopPropagation();
              var value = $(this).val();
              select.val(value).trigger('change');
              select.trigger('click'); // select.trigger('focusin')

              if (woo_variation_swatches_options.is_mobile) {//   select.trigger('touchstart')
              } // Radio


              $(this).parent('li.radio-variable-item').removeClass('selected disabled no-stock').addClass('selected');
              $(this).parent('li.radio-variable-item').trigger('wvs-selected-item', [value, select, self.$element]); // Custom Event for li
            });
          } // Keyboard Access


          $(element).on('keydown.wvs', 'li.variable-item:not(.disabled)', function (event) {
            if (event.keyCode && 32 === event.keyCode || event.key && ' ' === event.key || event.keyCode && 13 === event.keyCode || event.key && 'enter' === event.key.toLowerCase()) {
              event.preventDefault();
              $(this).trigger('click');
            }
          });
        });
        this.$element.on('click.wvs', '.woo-variation-swatches-variable-item-more', function (event) {
          event.preventDefault();
          $(this).parent().removeClass('enabled-display-limit-mode enabled-catalog-display-limit-mode');
          $(this).remove();
        });
      }
    }, {
      key: "update",
      value: function update() {
        var _this4 = this;

        // this.$element.off('woocommerce_variation_has_changed.wvs')
        this.$element.on('woocommerce_variation_has_changed.wvs', function (event) {
          // Don't use any propagation. It will disable composite product functionality
          // event.stopPropagation();
          _this4.setupItems();
        });
      }
    }, {
      key: "setUpStockInfo",
      value: function setUpStockInfo() {
        var _this5 = this;

        if (woo_variation_swatches_options.show_variation_stock) {
          var max_stock_label = parseInt(woo_variation_swatches_options.stock_label_threshold, 10);
          this.$element.on('wvs-selected-item.wc-variation-form', function () {
            var attributes = _this5.getChosenAttributes();

            var variations = _this5.findStockVariations(_this5.product_variations, attributes);

            if (attributes.count > 1 && attributes.count === attributes.chosenCount) {
              _this5.resetStockInfo();
            }

            if (attributes.count > 1 && attributes.count === attributes.mayChosenCount) {
              variations.forEach(function (data) {
                var stockInfoSelector = "[data-attribute_name=\"".concat(data.attribute_name, "\"] > [data-value=\"").concat(data.attribute_value, "\"]");

                if (data.variation.is_in_stock && data.variation.max_qty && data.variation.variation_stock_left && data.variation.max_qty <= max_stock_label) {
                  _this5.$element.find("".concat(stockInfoSelector, " .wvs-stock-left-info")).attr('data-wvs-stock-info', data.variation.variation_stock_left);

                  _this5.$element.find(stockInfoSelector).addClass('wvs-show-stock-left-info');
                } else {
                  _this5.$element.find(stockInfoSelector).removeClass('wvs-show-stock-left-info');

                  _this5.$element.find("".concat(stockInfoSelector, " .wvs-stock-left-info")).attr('data-wvs-stock-info', '');
                }
              });
            }
          });
          this.$element.on('hide_variation.wc-variation-form', function () {
            _this5.resetStockInfo();
          });
        }
      }
    }, {
      key: "resetStockInfo",
      value: function resetStockInfo() {
        this.$element.find('.variable-item').removeClass('wvs-show-stock-left-info');
        this.$element.find('.wvs-stock-left-info').attr('data-wvs-stock-info', '');
      }
    }, {
      key: "getChosenAttributes",
      value: function getChosenAttributes() {
        var data = {};
        var count = 0;
        var chosen = 0;
        this.attributeFields.each(function () {
          var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
          var value = $(this).val() || '';

          if (value.length > 0) {
            chosen++;
          }

          count++;
          data[attribute_name] = value;
        });
        return {
          'count': count,
          'chosenCount': chosen,
          'mayChosenCount': chosen + 1,
          'data': data
        };
      }
    }, {
      key: "findStockVariations",
      value: function findStockVariations(allVariations, selectedAttributes) {
        var found = [];

        for (var _i = 0, _Object$entries = Object.entries(selectedAttributes.data); _i < _Object$entries.length; _i++) {
          var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
              attribute_name = _Object$entries$_i[0],
              attribute_value = _Object$entries$_i[1];

          if (attribute_value.length === 0) {
            var values = this.$element.find("ul[data-attribute_name='".concat(attribute_name, "']")).data('attribute_values') || [];

            var _iterator = _createForOfIteratorHelper(values),
                _step;

            try {
              for (_iterator.s(); !(_step = _iterator.n()).done;) {
                var value = _step.value;

                var compare = _.extend(selectedAttributes.data, _defineProperty({}, attribute_name, value));

                var matched_variation = this.findMatchingVariations(allVariations, compare);

                if (matched_variation.length > 0) {
                  var variation = matched_variation.shift();
                  var data = {};
                  data['attribute_name'] = attribute_name;
                  data['attribute_value'] = value;
                  data['variation'] = variation;
                  found.push(data);
                }
              }
            } catch (err) {
              _iterator.e(err);
            } finally {
              _iterator.f();
            }
          }
        }

        return found;
      }
    }, {
      key: "findMatchingVariations",
      value: function findMatchingVariations(variations, attributes) {
        var matching = [];

        for (var i = 0; i < variations.length; i++) {
          var variation = variations[i];

          if (this.isMatch(variation.attributes, attributes)) {
            matching.push(variation);
          }
        }

        return matching;
      }
    }, {
      key: "isMatch",
      value: function isMatch(variation_attributes, attributes) {
        var match = true;

        for (var attr_name in variation_attributes) {
          if (variation_attributes.hasOwnProperty(attr_name)) {
            var val1 = variation_attributes[attr_name];
            var val2 = attributes[attr_name];

            if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
              match = false;
            }
          }
        }

        return match;
      }
    }], [{
      key: "_jQueryInterface",
      value: function _jQueryInterface(config) {
        return this.each(function () {
          new WooVariationSwatches(this, config);
        });
      }
    }]);

    return WooVariationSwatches;
  }();
  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */


  $.fn['WooVariationSwatches'] = WooVariationSwatches._jQueryInterface;
  $.fn['WooVariationSwatches'].Constructor = WooVariationSwatches;

  $.fn['WooVariationSwatches'].noConflict = function () {
    $.fn['WooVariationSwatches'] = $.fn['WooVariationSwatches'];
    return WooVariationSwatches._jQueryInterface;
  };

  return WooVariationSwatches;
}(jQuery);

/* harmony default export */ __webpack_exports__["default"] = (WooVariationSwatches);

/***/ }),

/***/ "./src/js/frontend.js":
/***/ (function(module, exports, __webpack_require__) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function _getRequireWildcardCache(nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }

function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { "default": obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj["default"] = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

jQuery(function ($) {
  Promise.resolve().then(function () {
    return _interopRequireWildcard(__webpack_require__("./src/js/WooVariationSwatches.js"));
  }).then(function () {
    // Init on Ajax Popup :)
    $(document).on('wc_variation_form.wvs', '.variations_form:not(.wvs-loaded)', function (event) {
      $(this).WooVariationSwatches();
    }); // Any custom product with .woo_variation_swatches_variations_form class to support

    $(document).on('wc_variation_form.wvs', '.woo_variation_swatches_variations_form:not(.wvs-loaded)', function (event) {
      $(this).WooVariationSwatches();
    }); // Yith Composite Product

    $(document).on('wc_variation_form.wvs', '.ywcp_inner_selected_container:not(.wvs-loaded)', function (event) {
      $(this).WooVariationSwatches();
    }); // Try to cover all ajax data complete

    $(document).ajaxComplete(function (event, request, settings) {
      _.delay(function () {
        $('.variations_form:not(.wvs-loaded)').each(function () {// $(this).wc_variation_form()
        });
      }, 100);
    }); // Support for Jetpack's Infinite Scroll,

    $(document.body).on('post-load.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Composite product load
    // JS API: https://docs.woocommerce.com/document/composite-products/composite-products-js-api-reference/

    $(document.body).on('wc-composite-initializing', '.composite_data', function (event, composite) {
      composite.actions.add_action('component_options_state_changed', function (self) {
        $(self.$component_content).find('.variations_form').removeClass('wvs-loaded wvs-pro-loaded');
      });
      /* composite.actions.add_action('active_scenarios_updated', (self) => {
         console.log('active_scenarios_updated')
         $(self.$component_content).find('.variations_form').removeClass('wvs-loaded wvs-pro-loaded')
       })*/
    }); // Support for Yith Infinite Scroll

    $(document).on('yith_infs_added_elem.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Support for Yith Ajax Filter

    $(document).on('yith-wcan-ajax-filtered.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Support for Woodmart theme

    $(document).on('wood-images-loaded.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Support for berocket ajax filters

    $(document).on('berocket_ajax_products_loaded.wvs berocket_ajax_products_infinite_loaded.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Flatsome Infinite Scroll Support

    $('.shop-container .products, .infinite-scroll-wrap').on('append.infiniteScroll', function (event, response, path) {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // FacetWP Load More

    $(document).on('facetwp-loaded.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // Savoy Load More

    $(document).on('nm_infload_after.wvs nm_ajax_shop_update_content.wvs', function () {
      $('.variations_form:not(.wvs-loaded)').each(function () {
        $(this).wc_variation_form();
      });
    }); // WooCommerce Filter Nav

    $('body').on('aln_reloaded.wvs', function () {
      _.delay(function () {
        $('.variations_form:not(.wvs-loaded)').each(function () {
          $(this).wc_variation_form();
        });
      }, 100);
    });
  });
}); // end of jquery main wrapper

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./src/js/frontend.js");


/***/ })

/******/ });