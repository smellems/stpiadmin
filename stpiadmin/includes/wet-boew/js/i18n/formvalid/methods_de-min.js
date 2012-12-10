/*!
 * Localized default methods for the jQuery validation plugin.
 * Locale: DE
 */
jQuery.extend(jQuery.validator.methods,{date:function(b,a){return this.optional(a)||/^\d\d?\.\d\d?\.\d\d\d?\d?$/.test(b)},number:function(b,a){return this.optional(a)||/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test(b)}});