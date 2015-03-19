/**
 * multilingual add-on
 * This add-on allow to define message in multiple languages
 *
 * @link        http://bootstrapvalidator.com/addons/multilingual/
 * @author      https://twitter.com/nghuuphuoc
 * @copyright   (c) 2013 - 2014 Nguyen Huu Phuoc
 * @license     http://bootstrapvalidator.com/license/
 */
(function($) {
    /**
     * The 'message' option of each validator can be
     * - a literal object mapping the locale with message
     *  $(form).bootstrapValidator({
     *      fields: {
     *          fieldName: {
     *              validators: {
     *                  validatorName: {
     *                      message: {
     *                          en_US: 'The message in English',
     *                          fr_FR: 'The message in French'
     *                      }
     *                  }
     *              }
     *          }
     *      }
     *  });
     *  - a callback function returns the literal object as above
     *  $(form).bootstrapValidator({
     *      fields: {
     *          fieldName: {
     *              validators: {
     *                  validatorName: {
     *                      message: function(validator, $field, validatorName) {
     *                          return {
     *                              en_US: 'The message in English',
     *                              fr_FR: 'The message in French'
     *                          };
     *                      }
     *                  }
     *              }
     *          }
     *      }
     *  });
     */
    FormValidation.AddOn.multilingual = {
        /**
         * @param {FormValidation.Base} validator The validator instance
         * @param {Object} options The add-on options
         */
        init: function(validator, options) {
            this._setMessage(validator);

            // Update the message when changing the locale
            var that = this,
                opts = validator.getOptions();
            validator
                .getForm()
                .on(opts.events.localeChanged, function(e, data) {
                    that._setMessage(data.fv);
                });
        },

        _setMessage: function(validator) {
            var options = validator.getOptions(),
                locale  = validator.getLocale();

            for (var field in options.fields) {
                if (!options.fields[field].validators) {
                    continue;
                }

                var fields = validator.getFieldElements(field),
                    type   = fields.attr('type'),
                    total  = ('radio' === type || 'checkbox' === type) ? 1 : fields.length;

                for (var i = 0; i < total; i++) {
                    var $field = fields.eq(i);

                    for (var v in options.fields[field].validators) {
                        var message     = options.fields[field].validators[v].message,
                            messageType = typeof message,
                            localized   = null;
                        // message is defined by language package
                        if ('undefined' === messageType && FormValidation.I18n[locale][v]['default']) {
                            localized = FormValidation.I18n[locale][v]['default'];
                        }
                        // message is a literal object
                        else if ('object' === messageType && message[locale]) {
                            localized = message[locale];
                        }
                        // message is defined by a function
                        else if ('function' === messageType) {
                            var result = message.apply(this, [validator, $field, v]);
                            if (result && result[locale]) {
                                localized = result[locale];
                            }
                        }

                        if (localized) {
                            validator.updateMessage(field, v, localized);
                        }
                    }
                }
            }
        }
    };
}(jQuery));
