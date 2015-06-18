var Validators = {

    filter: function(value) {
        return (typeof value === 'string') ? value.trim() : value;
    },

    validate : function (value, settings, form) {

        var that = this;

        value = this.filter(value);

        return settings.reduce(function (result, rule) {

            var valid = that.check[rule.filter].apply(value, rule.parameters.concat([form]));
            var errors = (valid) ? result.errors : result.errors.concat([rule.errorMessage]);
            return {
                valid: result.valid && valid,
                errors: errors
            };

        }, {valid: true, errors: []});

    },

    check : {

        regexp : function(regex) {
            return !!this.match(new RegExp(regex));
        },

        required : function() {
            return this.length > 0;
        },

        min: function(minLength) {
            return this.length >= minLength;
        },

        max: function(maxLength) {
            return this.length <= maxLength;
        },

        variants: function(variants) {
            return variants.indexOf(this) > -1;
        },

        mime: function(pattern) {
            if (this.type) {
                return !!this.type.match(new RegExp(pattern));
            }
            return false;
        },

        size: function(maxSize) {
            return this.size <= maxSize;
        },

        equals: function(id, form) {
            var second = form.querySelector('[name="' + id + '"]').value;
            return this.toString() === Validators.filter(second);
        },

        uniqueMail: function() {
            return true;
        }

    }
};

