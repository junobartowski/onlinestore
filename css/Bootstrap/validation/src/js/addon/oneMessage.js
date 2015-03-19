/**
 * oneMessage add-on
 * This add-ons shows only one message if the field is not valid
 *
 * @link        http://bootstrapvalidator.com/addons/oneMessage/
 * @author      https://twitter.com/nghuuphuoc
 * @copyright   (c) 2013 - 2014 Nguyen Huu Phuoc
 * @license     http://bootstrapvalidator.com/license/
 */
(function($) {
    FormValidation.AddOn.oneMessage = {
        /**
         * @param {FormValidation.Base} validator The validator instance
         * @param {Object} options The add-on options
         */
        init: function(validator, options) {
            var ns   = validator.getNamespace(),
                opts = validator.getOptions();
            validator
                .getForm()
                .on(opts.events.validatorError, function(e, data) {
                    if (options && options[data.field] === false) {
                        return;
                    }

                    data.element
                        .data(ns + '.messages')
                        // Hide all the messages
                        .find('.help-block[data-' + ns + '-for="' + data.field + '"]').hide()
                        // Show only message associated with current validator
                        .filter('[data-' + ns + '-validator="' + data.validator + '"]').show();
                });
        }
    };
}(jQuery));
