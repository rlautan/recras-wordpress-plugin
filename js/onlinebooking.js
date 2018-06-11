'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RecrasContactForm = function () {
    function RecrasContactForm() {
        var _this = this;

        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        _classCallCheck(this, RecrasContactForm);

        this.languageHelper = new RecrasLanguageHelper();

        if (options instanceof RecrasOptions === false) {
            throw new Error(this.languageHelper.translate('ERR_OPTIONS_INVALID'));
        }
        this.options = options;
        this.languageHelper.setOptions(options);
        if (RecrasLanguageHelper.isValid(this.options.getLocale())) {
            this.languageHelper.setLocale(this.options.getLocale());
        }

        this.fetchJson = function (url) {
            return RecrasHttpHelper.fetchJson(url, _this.error);
        };

        this.GENDERS = {
            onbekend: 'GENDER_UNKNOWN',
            man: 'GENDER_MALE',
            vrouw: 'GENDER_FEMALE'
        };
        // https://html.spec.whatwg.org/multipage/form-control-infrastructure.html#inappropriate-for-the-control
        this.AUTOCOMPLETE_OPTIONS = {
            'contactpersoon.voornaam': 'given-name',
            'contactpersoon.achternaam': 'family-name',
            'contact.landcode': 'country',
            'contact.naam': 'organization',
            'contactpersoon.adres': 'address-line1',
            'contactpersoon.postcode': 'postal-code',
            'contactpersoon.plaats': 'address-level2'
        };
    }

    _createClass(RecrasContactForm, [{
        key: 'error',
        value: function error(msg) {
            console.log('Error', msg); //TODO
        }
    }, {
        key: 'fromPackage',
        value: function fromPackage(pack) {
            return this.getContactFormFields(pack.onlineboeking_contactformulier_id);
        }
    }, {
        key: 'fromVoucherTemplate',
        value: function fromVoucherTemplate(template) {
            return this.getContactFormFields(template.contactform_id);
        }
    }, {
        key: 'generateJson',
        value: function generateJson() {
            var elements = this.options.getElement().querySelectorAll('[id^="contactformulier-"]');
            var contactForm = {};
            [].concat(_toConsumableArray(elements)).forEach(function (field) {
                contactForm[field.dataset.identifier] = field.value;
            });
            return contactForm;
        }
    }, {
        key: 'getContactFormFields',
        value: function getContactFormFields(formId) {
            var _this2 = this;

            return this.fetchJson(this.options.getApiBase() + 'contactformulieren/' + formId + '/velden').then(function (fields) {
                fields = fields.sort(function (a, b) {
                    return a.sort_order - b.sort_order;
                });

                _this2.contactFormFields = fields;
                return _this2.contactFormFields;
            });
        }
    }, {
        key: 'getCountryList',
        value: function getCountryList() {
            var _this3 = this;

            return this.fetchJson('https://cdn.rawgit.com/umpirsky/country-list/ddabf3a8/data/' + this.languageHelper.locale + '/country.json').then(function (json) {
                _this3.countries = json;
                return _this3.countries;
            });
        }
    }, {
        key: 'showField',
        value: function showField(field, idx) {
            var _this4 = this;

            if (field.soort_invoer === 'header') {
                return '<h3>' + field.naam + '</h3>';
            }

            var label = this.showLabel(field, idx);
            var attrRequired = field.verplicht ? 'required' : '';
            var html = void 0;
            var fixedAttributes = 'id="contactformulier-' + idx + '" name="contactformulier' + idx + '" ' + attrRequired + ' data-identifier="' + field.field_identifier + '"';
            switch (field.soort_invoer) {
                case 'contactpersoon.geslacht':
                    html = '<select ' + fixedAttributes + ' autocomplete="sex">';
                    Object.keys(this.GENDERS).forEach(function (key) {
                        html += '<option value="' + key + '">' + _this4.languageHelper.translate(_this4.GENDERS[key]);
                    });
                    html += '</select>';
                    return label + html;
                case 'keuze':
                    html = '<select ' + fixedAttributes + ' multiple>';
                    field.mogelijke_keuzes.forEach(function (choice) {
                        html += '<option value="' + choice + '">' + choice;
                    });
                    html += '</select>';
                    return label + html;
                case 'veel_tekst':
                    return label + ('<textarea ' + fixedAttributes + '></textarea>');
                case 'contactpersoon.telefoon1':
                    return label + ('<input type="tel" ' + fixedAttributes + ' autocomplete="tel">');
                case 'contactpersoon.email1':
                    return label + ('<input type="email" ' + fixedAttributes + ' autocomplete="email">');
                case 'contactpersoon.nieuwsbrieven':
                    html = '<select ' + fixedAttributes + ' multiple>';
                    Object.keys(field.newsletter_options).forEach(function (key) {
                        html += '<option value="' + key + '">' + field.newsletter_options[key];
                    });
                    html += '</select>';
                    return label + html;
                case 'contact.landcode':
                    html = '<select ' + fixedAttributes + '>';
                    Object.keys(this.countries).forEach(function (code) {
                        var selectedText = code.toUpperCase() === _this4.languageHelper.getCountry() ? ' selected' : '';
                        html += '<option value="' + code + '"' + selectedText + '>' + _this4.countries[code];
                    });
                    html += '</select>';
                    return label + html;
                default:
                    var autocomplete = this.AUTOCOMPLETE_OPTIONS[field.soort_invoer] ? this.AUTOCOMPLETE_OPTIONS[field.soort_invoer] : '';
                    return label + ('<input type="text" ' + fixedAttributes + ' autocomplete="' + autocomplete + '">');
            }
        }
    }, {
        key: 'showLabel',
        value: function showLabel(field, idx) {
            var labelText = field.naam;
            if (field.verplicht) {
                labelText += '<span class="recras-contactform-required" title="' + this.languageHelper.translate('ATTR_REQUIRED') + '"></span>';
            }
            return '<label for="contactformulier-' + idx + '">' + labelText + '</label>';
        }
    }]);

    return RecrasContactForm;
}();"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RecrasDateHelper = function () {
    function RecrasDateHelper() {
        _classCallCheck(this, RecrasDateHelper);
    }

    _createClass(RecrasDateHelper, null, [{
        key: "datePartOnly",
        value: function datePartOnly(date) {
            var x = new Date(date.getTime() - date.getTimezoneOffset() * 60 * 1000); // Fix off-by-1 errors
            return x.toISOString().substr(0, 10); // Format as 2018-03-13
        }
    }, {
        key: "setTimeForDate",
        value: function setTimeForDate(date, timeStr) {
            date.setHours(timeStr.substr(0, 2), timeStr.substr(3, 2));
            return date;
        }
    }, {
        key: "timePartOnly",
        value: function timePartOnly(date) {
            return date.toTimeString().substr(0, 5); // Format at 09:00
        }
    }]);

    return RecrasDateHelper;
}();'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RecrasHttpHelper = function () {
    function RecrasHttpHelper() {
        _classCallCheck(this, RecrasHttpHelper);
    }

    _createClass(RecrasHttpHelper, null, [{
        key: 'call',
        value: function call(url, data, errorHandler) {
            if (!url) {
                throw new Error('ERR_FETCH_WITHOUT_URL'); //TODO: translate
            }
            return fetch(url, data).then(function (response) {
                if (!response.ok) {
                    errorHandler(response.status + ' ' + response.statusText);
                    return false;
                }
                return response.json();
            }).then(function (json) {
                return json;
            }).catch(function (err) {
                errorHandler(err);
            });
        }
    }, {
        key: 'fetchJson',
        value: function fetchJson(url, errorHandler) {
            return this.call(url, {
                method: 'get'
            }, errorHandler);
        }
    }, {
        key: 'postJson',
        value: function postJson(url, data, errorHandler) {
            return this.call(url, {
                body: JSON.stringify(data),
                method: 'post'
            }, errorHandler);
        }
    }]);

    return RecrasHttpHelper;
}();'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RecrasLanguageHelper = function () {
    function RecrasLanguageHelper() {
        _classCallCheck(this, RecrasLanguageHelper);

        this.locale = this.defaultLocale;
        this.options = null;

        //TODO: what is the best way to handle multiple locales?
        this.i18n = {
            de_DE: {
                AGREE_ATTACHMENTS: 'Ich stimme mit den folgenden Unterlagen:',
                ATTR_REQUIRED: 'Erforderlich',
                BUTTON_BOOK_NOW: 'Jetzt buchen',
                BUTTON_BUY_NOW: 'Jetzt kaufen',
                DATE: 'Datum',
                DATE_INVALID: 'Ungültiges datum',
                DATE_PICKER_NEXT_MONTH: 'Nächsten Monat',
                DATE_PICKER_PREVIOUS_MONTH: 'Vorheriger Monat',
                DATE_PICKER_MONTH_JANUARY: 'Januar',
                DATE_PICKER_MONTH_FEBRUARY: 'Februar',
                DATE_PICKER_MONTH_MARCH: 'März',
                DATE_PICKER_MONTH_APRIL: 'April',
                DATE_PICKER_MONTH_MAY: 'Mai',
                DATE_PICKER_MONTH_JUNE: 'Juni',
                DATE_PICKER_MONTH_JULY: 'Juli',
                DATE_PICKER_MONTH_AUGUST: 'August',
                DATE_PICKER_MONTH_SEPTEMBER: 'September',
                DATE_PICKER_MONTH_OCTOBER: 'Oktober',
                DATE_PICKER_MONTH_NOVEMBER: 'November',
                DATE_PICKER_MONTH_DECEMBER: 'Dezember',
                DATE_PICKER_DAY_MONDAY_LONG: 'Montag',
                DATE_PICKER_DAY_MONDAY_SHORT: 'Mo',
                DATE_PICKER_DAY_TUESDAY_LONG: 'Dienstag',
                DATE_PICKER_DAY_TUESDAY_SHORT: 'Di',
                DATE_PICKER_DAY_WEDNESDAY_LONG: 'Mittwoch',
                DATE_PICKER_DAY_WEDNESDAY_SHORT: 'Mi',
                DATE_PICKER_DAY_THURSDAY_LONG: 'Donnerstag',
                DATE_PICKER_DAY_THURSDAY_SHORT: 'Do',
                DATE_PICKER_DAY_FRIDAY_LONG: 'Freitag',
                DATE_PICKER_DAY_FRIDAY_SHORT: 'Fr',
                DATE_PICKER_DAY_SATURDAY_LONG: 'Samstag',
                DATE_PICKER_DAY_SATURDAY_SHORT: 'Sa',
                DATE_PICKER_DAY_SUNDAY_LONG: 'Sonntag',
                DATE_PICKER_DAY_SUNDAY_SHORT: 'So',
                DISCOUNT_CHECK: 'Überprüfen',
                DISCOUNT_CODE: 'Rabattcode',
                DISCOUNT_INVALID: 'Ungültiger Rabattcode',
                ERR_GENERAL: 'Etwas ist schief gelaufen:',
                ERR_INVALID_ELEMENT: 'Option "Element" ist kein gültiges Element',
                ERR_INVALID_HOSTNAME: 'Option "recras_hostname" ist ungültig.',
                ERR_INVALID_LOCALE: 'Ungültiges Gebietsschema. Gültige Optionen sind: {LOCALES}',
                ERR_INVALID_REDIRECT_URL: 'Ungültige redirect URL. Stellen Sie sicher, dass es mit http:// or https:// beginnt',
                ERR_NO_ELEMENT: 'Option "element" nicht eingestellt.',
                ERR_NO_HOSTNAME: 'Option "recras_hostname" nicht eingestellt.',
                GENDER_UNKNOWN: 'Unbekannte',
                GENDER_MALE: 'Mann',
                GENDER_FEMALE: 'Frau',
                LOADING: 'Wird geladen...',
                NO_PRODUCTS: 'Kein Produkt ausgewählt',
                PRICE_TOTAL: 'Insgesamt',
                PRICE_TOTAL_WITH_DISCOUNT: 'Insgesamt inklusive Rabatt',
                PRODUCT_MINIMUM: '(muss mindestens {MINIMUM} sein)',
                PRODUCT_REQUIRED: '{NUM} {PRODUCT} benötigt {REQUIRED_AMOUNT} {REQUIRED_PRODUCT} um auch gebucht zu werden.',
                TIME: 'Zeit',
                VOUCHER: 'Gutschein',
                VOUCHER_ALREADY_APPLIED: 'Gutschein bereits eingelöst',
                VOUCHER_APPLIED: 'Gutschein bereits eingelöst',
                VOUCHER_APPLY: 'Einlösen',
                VOUCHER_EMPTY: 'Leerer Gutscheincode',
                VOUCHER_INVALID: 'Ungültiger Gutscheincode',
                VOUCHER_QUANTITY: 'Anzahl der Gutscheine',
                VOUCHERS_DISCOUNT: 'Rabatt von Gutschein(en)'
            },
            en_GB: {
                AGREE_ATTACHMENTS: 'I agree with the following documents:',
                ATTR_REQUIRED: 'Required',
                BUTTON_BOOK_NOW: 'Book now',
                BUTTON_BUY_NOW: 'Buy now',
                DATE: 'Date',
                DATE_INVALID: 'Invalid date',
                DATE_PICKER_NEXT_MONTH: 'Next month',
                DATE_PICKER_PREVIOUS_MONTH: 'Previous month',
                DATE_PICKER_MONTH_JANUARY: 'January',
                DATE_PICKER_MONTH_FEBRUARY: 'February',
                DATE_PICKER_MONTH_MARCH: 'March',
                DATE_PICKER_MONTH_APRIL: 'April',
                DATE_PICKER_MONTH_MAY: 'May',
                DATE_PICKER_MONTH_JUNE: 'June',
                DATE_PICKER_MONTH_JULY: 'July',
                DATE_PICKER_MONTH_AUGUST: 'August',
                DATE_PICKER_MONTH_SEPTEMBER: 'September',
                DATE_PICKER_MONTH_OCTOBER: 'October',
                DATE_PICKER_MONTH_NOVEMBER: 'November',
                DATE_PICKER_MONTH_DECEMBER: 'December',
                DATE_PICKER_DAY_MONDAY_LONG: 'Monday',
                DATE_PICKER_DAY_MONDAY_SHORT: 'Mon',
                DATE_PICKER_DAY_TUESDAY_LONG: 'Tuesday',
                DATE_PICKER_DAY_TUESDAY_SHORT: 'Tue',
                DATE_PICKER_DAY_WEDNESDAY_LONG: 'Wednesday',
                DATE_PICKER_DAY_WEDNESDAY_SHORT: 'Wed',
                DATE_PICKER_DAY_THURSDAY_LONG: 'Thursday',
                DATE_PICKER_DAY_THURSDAY_SHORT: 'Thu',
                DATE_PICKER_DAY_FRIDAY_LONG: 'Friday',
                DATE_PICKER_DAY_FRIDAY_SHORT: 'Fri',
                DATE_PICKER_DAY_SATURDAY_LONG: 'Saturday',
                DATE_PICKER_DAY_SATURDAY_SHORT: 'Sat',
                DATE_PICKER_DAY_SUNDAY_LONG: 'Sunday',
                DATE_PICKER_DAY_SUNDAY_SHORT: 'Sun',
                DISCOUNT_CHECK: 'Check',
                DISCOUNT_CODE: 'Discount code',
                DISCOUNT_INVALID: 'Invalid discount code',
                ERR_GENERAL: 'Something went wrong:',
                ERR_INVALID_ELEMENT: 'Option "element" is not a valid Element',
                ERR_INVALID_HOSTNAME: 'Option "recras_hostname" is invalid.',
                ERR_INVALID_LOCALE: 'Invalid locale. Valid options are: {LOCALES}',
                ERR_INVALID_REDIRECT_URL: 'Invalid redirect URL. Make sure you it starts with http:// or https://',
                ERR_NO_ELEMENT: 'Option "element" not set.',
                ERR_NO_HOSTNAME: 'Option "recras_hostname" not set.',
                GENDER_UNKNOWN: 'Unknown',
                GENDER_MALE: 'Male',
                GENDER_FEMALE: 'Female',
                LOADING: 'Loading...',
                NO_PRODUCTS: 'No product selected',
                PRICE_TOTAL: 'Total',
                PRICE_TOTAL_WITH_DISCOUNT: 'Total including discount',
                PRODUCT_MINIMUM: '(must be at least {MINIMUM})',
                PRODUCT_REQUIRED: '{NUM} {PRODUCT} requires {REQUIRED_AMOUNT} {REQUIRED_PRODUCT} to also be booked.',
                TIME: 'Time',
                VOUCHER: 'Voucher',
                VOUCHER_ALREADY_APPLIED: 'Voucher already applied',
                VOUCHER_APPLIED: 'Voucher applied',
                VOUCHER_APPLY: 'Apply',
                VOUCHER_EMPTY: 'Empty voucher code',
                VOUCHER_INVALID: 'Invalid voucher code',
                VOUCHER_QUANTITY: 'Number of vouchers',
                VOUCHERS_DISCOUNT: 'Discount from voucher(s)'
            },
            nl_NL: {
                AGREE_ATTACHMENTS: 'Ik ga akkoord met de volgende gegevens:',
                ATTR_REQUIRED: 'Vereist',
                BUTTON_BOOK_NOW: 'Nu boeken',
                BUTTON_BUY_NOW: 'Nu kopen',
                DATE: 'Datum',
                DATE_INVALID: 'Ongeldige datum',
                DATE_PICKER_NEXT_MONTH: 'Volgende maand',
                DATE_PICKER_PREVIOUS_MONTH: 'Vorige maand',
                DATE_PICKER_MONTH_JANUARY: 'Januari',
                DATE_PICKER_MONTH_FEBRUARY: 'Februari',
                DATE_PICKER_MONTH_MARCH: 'Maart',
                DATE_PICKER_MONTH_APRIL: 'April',
                DATE_PICKER_MONTH_MAY: 'Mei',
                DATE_PICKER_MONTH_JUNE: 'Juni',
                DATE_PICKER_MONTH_JULY: 'Juli',
                DATE_PICKER_MONTH_AUGUST: 'Augustus',
                DATE_PICKER_MONTH_SEPTEMBER: 'September',
                DATE_PICKER_MONTH_OCTOBER: 'Oktober',
                DATE_PICKER_MONTH_NOVEMBER: 'November',
                DATE_PICKER_MONTH_DECEMBER: 'December',
                DATE_PICKER_DAY_MONDAY_LONG: 'Maandag',
                DATE_PICKER_DAY_MONDAY_SHORT: 'Ma',
                DATE_PICKER_DAY_TUESDAY_LONG: 'Dinsdag',
                DATE_PICKER_DAY_TUESDAY_SHORT: 'Di',
                DATE_PICKER_DAY_WEDNESDAY_LONG: 'Woensdag',
                DATE_PICKER_DAY_WEDNESDAY_SHORT: 'Wo',
                DATE_PICKER_DAY_THURSDAY_LONG: 'Donderdag',
                DATE_PICKER_DAY_THURSDAY_SHORT: 'Do',
                DATE_PICKER_DAY_FRIDAY_LONG: 'Vrijdag',
                DATE_PICKER_DAY_FRIDAY_SHORT: 'Vr',
                DATE_PICKER_DAY_SATURDAY_LONG: 'Zaterdag',
                DATE_PICKER_DAY_SATURDAY_SHORT: 'Za',
                DATE_PICKER_DAY_SUNDAY_LONG: 'Zondag',
                DATE_PICKER_DAY_SUNDAY_SHORT: 'Zo',
                DISCOUNT_CHECK: 'Controleren',
                DISCOUNT_CODE: 'Kortingscode',
                DISCOUNT_INVALID: 'Ongeldige kortingscode',
                ERR_GENERAL: 'Er ging iets mis:',
                ERR_INVALID_ELEMENT: 'Optie "element" is geen geldig Element',
                ERR_INVALID_HOSTNAME: 'Optie "recras_hostname" is ongeldig.',
                ERR_INVALID_LOCALE: 'Ongeldige locale. Geldige opties zijn: {LOCALES}',
                ERR_INVALID_REDIRECT_URL: 'Ongeldige redirect-URL. Zorg ervoor dat deze begint met http:// of https://',
                ERR_NO_ELEMENT: 'Optie "element" niet ingesteld.',
                ERR_NO_HOSTNAME: 'Optie "recras_hostname" niet ingesteld.',
                GENDER_UNKNOWN: 'Onbekend',
                GENDER_MALE: 'Man',
                GENDER_FEMALE: 'Vrouw',
                LOADING: 'Laden...',
                NO_PRODUCTS: 'Geen product gekozen',
                PRICE_TOTAL: 'Totaal',
                PRICE_TOTAL_WITH_DISCOUNT: 'Totaal inclusief korting',
                PRODUCT_MINIMUM: '(moet minstens {MINIMUM} zijn)',
                PRODUCT_REQUIRED: '{NUM} {PRODUCT} vereist dat ook {REQUIRED_AMOUNT} {REQUIRED_PRODUCT} geboekt wordt.',
                TIME: 'Tijd',
                VOUCHER: 'Tegoedbon',
                VOUCHER_ALREADY_APPLIED: 'Tegoedbon al toegepast',
                VOUCHER_APPLIED: 'Tegoedbon toegepast',
                VOUCHER_APPLY: 'Toepassen',
                VOUCHER_EMPTY: 'Lege tegoedbon',
                VOUCHER_INVALID: 'Ongeldige tegoedbon',
                VOUCHER_QUANTITY: 'Aantal tegoedbonnen',
                VOUCHERS_DISCOUNT: 'Korting uit tegoedbon(nen)'
            }
        };
    }

    _createClass(RecrasLanguageHelper, [{
        key: 'error',
        value: function error(msg) {
            console.log('Error', msg); //TODO
        }
    }, {
        key: 'extractTags',
        value: function extractTags(msg) {
            var tags = msg.match(/{(.+?)}/g);
            if (!Array.isArray(tags)) {
                return [];
            }
            return tags.map(function (tag) {
                return tag.substring(1, tag.length - 1);
            }); // Strip { and }
        }
    }, {
        key: 'filterTags',
        value: function filterTags(msg, packageID) {
            return Promise.resolve(msg);
            /*let tags = this.extractTags(msg);
            if (tags.length === 0) {
                return Promise.resolve(msg);
            }
             return RecrasHttpHelper.postJson(
                this.options.getApiBase() + 'tagfilter',
                {
                    tags: tags,
                    context: {
                        packageID: packageID,
                    },
                },
                this.error
            )
                .then(filtered => {
                    Object.keys(filtered).forEach(tag => {
                        msg = msg.split('{' + tag + '}').join(filtered[tag]);
                    });
                    return msg;
                });*/
        }
    }, {
        key: 'formatLocale',
        value: function formatLocale(what) {
            switch (what) {
                case 'currency':
                    return this.locale.replace('_', '-').toUpperCase();
                default:
                    return this.locale;
            }
        }
    }, {
        key: 'formatPrice',
        value: function formatPrice(price) {
            return price.toLocaleString(this.formatLocale('currency'), {
                currency: this.currency,
                style: 'currency'
            });
        }
    }, {
        key: 'getCountry',
        value: function getCountry() {
            return this.locale.substr(3, 2); // nl_NL -> NL
        }
    }, {
        key: 'setCurrency',
        value: function setCurrency() {
            var _this = this;

            var errorHandler = function errorHandler(err) {
                _this.currency = 'eur';
                _this.error(err);
            };

            return RecrasHttpHelper.fetchJson(this.options.getApiBase() + 'instellingen/currency', errorHandler).then(function (setting) {
                _this.currency = setting.waarde;
            });
        }
    }, {
        key: 'setLocale',
        value: function setLocale(locale) {
            this.locale = locale;
        }
    }, {
        key: 'setOptions',
        value: function setOptions(options) {
            this.options = options;
            return this.setCurrency();
        }
    }, {
        key: 'translate',
        value: function translate(string) {
            var vars = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

            var translated = void 0;
            if (this.i18n[this.locale] && this.i18n[this.locale][string]) {
                translated = this.i18n[this.locale][string];
            } else if (this.i18n.en_GB[string]) {
                translated = this.i18n.en_GB[string];
            } else {
                translated = string;
                console.warn('String not translated: ' + string);
            }
            if (Object.keys(vars).length > 0) {
                Object.keys(vars).forEach(function (key) {
                    translated = translated.replace('{' + key + '}', vars[key]);
                });
            }
            return translated;
        }
    }], [{
        key: 'isValid',
        value: function isValid(locale) {
            return this.validLocales.indexOf(locale) > -1;
        }
    }]);

    return RecrasLanguageHelper;
}();

RecrasLanguageHelper.defaultLocale = 'nl_NL';
RecrasLanguageHelper.validLocales = ['de_DE', 'en_GB', 'nl_NL'];'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var RecrasOptions = function () {
    function RecrasOptions(options) {
        _classCallCheck(this, RecrasOptions);

        this.languageHelper = new RecrasLanguageHelper();
        this.validate(options);
        this.options = this.setOptions(options);
    }

    _createClass(RecrasOptions, [{
        key: 'getApiBase',
        value: function getApiBase() {
            return this.getHostname() + '/api2/';
        }
    }, {
        key: 'getElement',
        value: function getElement() {
            return this.options.element;
        }
    }, {
        key: 'getHostname',
        value: function getHostname() {
            return this.options.hostname;
        }
    }, {
        key: 'getLocale',
        value: function getLocale() {
            return this.options.locale;
        }
    }, {
        key: 'getPackageId',
        value: function getPackageId() {
            return this.options.package_id;
        }
    }, {
        key: 'getRedirectUrl',
        value: function getRedirectUrl() {
            return this.options.redirect_url;
        }
    }, {
        key: 'getVoucherTemplateId',
        value: function getVoucherTemplateId() {
            return this.options.voucher_template_id;
        }
    }, {
        key: 'setOptions',
        value: function setOptions(options) {
            var protocol = options.recras_hostname === RecrasOptions.hostnameDebug ? 'http' : 'https';
            options.hostname = protocol + '://' + options.recras_hostname;

            return options;
        }
    }, {
        key: 'validate',
        value: function validate(options) {
            var hostnameRegex = new RegExp(/^[a-z0-9\-]+\.recras\.nl$/i);

            if (!options.element) {
                throw new Error(this.languageHelper.translate('ERR_NO_ELEMENT'));
            }
            if (options.element instanceof Element === false) {
                throw new Error(this.languageHelper.translate('ERR_INVALID_ELEMENT'));
            }

            if (!options.recras_hostname) {
                throw new Error(this.languageHelper.translate('ERR_NO_HOSTNAME'));
            }
            if (!hostnameRegex.test(options.recras_hostname) && options.recras_hostname !== RecrasOptions.hostnameDebug) {
                throw new Error(this.languageHelper.translate('ERR_INVALID_HOSTNAME'));
            }
            if (options.redirect_url) {
                if (options.redirect_url.indexOf('http://') === -1 && options.redirect_url.indexOf('https://') === -1) {
                    throw new Error(this.languageHelper.translate('ERR_INVALID_REDIRECT_URL'));
                }
            }
        }
    }]);

    return RecrasOptions;
}();

RecrasOptions.hostnameDebug = '172.16.0.2';'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/****************************
 *  Recras voucher library  *
 *  v 0.1.0                 *
 ***************************/

var RecrasVoucher = function () {
    function RecrasVoucher() {
        var _this = this;

        var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

        _classCallCheck(this, RecrasVoucher);

        this.languageHelper = new RecrasLanguageHelper();

        if (options instanceof RecrasOptions === false) {
            throw new Error(this.languageHelper.translate('ERR_OPTIONS_INVALID'));
        }
        this.options = options;

        this.element = this.options.getElement();
        this.element.classList.add('recras-buy-voucher');

        this.fetchJson = function (url) {
            return RecrasHttpHelper.fetchJson(url, _this.error);
        };
        this.postJson = function (url, data) {
            return RecrasHttpHelper.postJson(_this.options.getApiBase() + url, data, _this.error);
        };

        if (this.options.getLocale()) {
            if (!RecrasLanguageHelper.isValid(this.options.getLocale())) {
                console.warn(this.languageHelper.translate('ERR_INVALID_LOCALE', {
                    LOCALES: RecrasLanguageHelper.validLocales.join(', ')
                }));
            } else {
                this.languageHelper.setLocale(this.options.getLocale());
            }
        }

        this.languageHelper.setOptions(options).then(function () {
            return _this.getVoucherTemplates();
        }).then(function (templates) {
            if (_this.options.getVoucherTemplateId()) {
                _this.changeTemplate(_this.options.getVoucherTemplateId());
            } else {
                _this.showTemplates(templates);
            }
        });
    }

    _createClass(RecrasVoucher, [{
        key: 'appendHtml',
        value: function appendHtml(msg) {
            this.element.insertAdjacentHTML('beforeend', msg);
        }
    }, {
        key: 'buyTemplate',
        value: function buyTemplate() {
            var _this2 = this;

            this.findElement('.buyTemplate').setAttribute('disabled', 'disabled');

            var payload = {
                voucher_template_id: this.selectedTemplate.id,
                number_of_vouchers: parseInt(this.findElement('.number-of-vouchers').value, 10),
                contact_form: this.contactForm.generateJson()
            };
            if (this.options.getRedirectUrl()) {
                payload.redirect_url = this.options.getRedirectUrl();
            }
            this.postJson('vouchers/buy', payload).then(function (json) {
                _this2.findElement('.buyTemplate').removeAttribute('disabled');

                if (json.payment_url) {
                    window.location.href = json.payment_url;
                } else {
                    console.log(result);
                }
            });
        }
    }, {
        key: 'changeTemplate',
        value: function changeTemplate(templateID) {
            this.clearAllExceptTemplateSelection();
            this.showContactForm(templateID);
        }
    }, {
        key: 'clearAll',
        value: function clearAll() {
            this.clearElements(this.element.children);
        }
    }, {
        key: 'clearAllExceptTemplateSelection',
        value: function clearAllExceptTemplateSelection() {
            var elements = document.querySelectorAll('#' + this.element.id + ' > *:not(.recras-voucher-templates)');
            this.clearElements(elements);
        }
    }, {
        key: 'clearElements',
        value: function clearElements(elements) {
            [].concat(_toConsumableArray(elements)).forEach(function (el) {
                el.parentNode.removeChild(el);
            });
            this.appendHtml('<div class="latestError"></div>');
        }
    }, {
        key: 'error',
        value: function error(msg) {
            this.findElement('.latestError').innerHTML = '<strong>{ this.languageHelper.translate(\'ERR_GENERAL\') }</strong><p>' + msg + '</p>';
        }
    }, {
        key: 'findElement',
        value: function findElement(querystring) {
            return this.element.querySelector(querystring);
        }
    }, {
        key: 'findElements',
        value: function findElements(querystring) {
            return this.element.querySelectorAll(querystring);
        }
    }, {
        key: 'formatPrice',
        value: function formatPrice(price) {
            return this.languageHelper.formatPrice(price);
        }
    }, {
        key: 'getContactFormFields',
        value: function getContactFormFields(template) {
            var _this3 = this;

            var contactForm = new RecrasContactForm(this.options);
            return contactForm.fromVoucherTemplate(template).then(function (formFields) {
                _this3.contactForm = contactForm;
                return formFields;
            });
        }
    }, {
        key: 'getVoucherTemplates',
        value: function getVoucherTemplates() {
            var _this4 = this;

            return this.fetchJson(this.options.getApiBase() + 'voucher_templates').then(function (templates) {
                _this4.templates = templates;
                return templates;
            });
        }
    }, {
        key: 'maybeDisableBuyButton',
        value: function maybeDisableBuyButton() {
            var button = this.findElement('.buyTemplate');
            if (!button) {
                return false;
            }

            var shouldDisable = false;
            if (!this.findElement('.recras-contactform').checkValidity()) {
                shouldDisable = true;
            }

            if (this.findElement('.number-of-vouchers').value < 1) {
                shouldDisable = true;
            }

            if (shouldDisable) {
                button.setAttribute('disabled', 'disabled');
            } else {
                button.removeAttribute('disabled');
            }
        }
    }, {
        key: 'quantitySelector',
        value: function quantitySelector() {
            return '<div><label for="number-of-vouchers">' + this.languageHelper.translate('VOUCHER_QUANTITY') + '</label><input type="number" id="number-of-vouchers" class="number-of-vouchers" min="1" value="1" required></div>';
        }
    }, {
        key: 'showBuyButton',
        value: function showBuyButton() {
            var html = '<div><button type="submit" class="buyTemplate" disabled>' + this.languageHelper.translate('BUTTON_BUY_NOW') + '</button></div>';
            this.appendHtml(html);
            this.findElement('.buyTemplate').addEventListener('click', this.buyTemplate.bind(this));
        }
    }, {
        key: 'showContactForm',
        value: function showContactForm(templateId) {
            var _this5 = this;

            this.selectedTemplate = this.templates.filter(function (t) {
                return t.id === templateId;
            })[0];

            this.getContactFormFields(this.selectedTemplate).then(function (fields) {
                var waitFor = [];

                var hasCountryField = fields.filter(function (field) {
                    return field.field_identifier === 'contact.landcode';
                }).length > 0;

                if (hasCountryField) {
                    waitFor.push(_this5.contactForm.getCountryList());
                }
                Promise.all(waitFor).then(function () {
                    var html = '<form class="recras-contactform">';
                    html += _this5.quantitySelector();
                    fields.forEach(function (field, idx) {
                        html += '<div>' + _this5.contactForm.showField(field, idx) + '</div>';
                    });
                    html += '</form>';
                    _this5.appendHtml(html);
                    _this5.showBuyButton();

                    [].concat(_toConsumableArray(_this5.findElements('[id^="contactformulier-"]'))).forEach(function (el) {
                        el.addEventListener('change', _this5.maybeDisableBuyButton.bind(_this5));
                    });
                });
            });
        }
    }, {
        key: 'showTemplates',
        value: function showTemplates(templates) {
            var _this6 = this;

            var templateOptions = templates.map(function (template) {
                return '<option value="' + template.id + '">' + template.name + ' (' + _this6.formatPrice(template.price) + ')';
            });
            var html = '<select class="recrasVoucherTemplates"><option>' + templateOptions.join('') + '</select>';
            this.appendHtml('<div class="recras-voucher-templates">' + html + '</div>');

            var voucherSelectEl = this.findElement('.recrasVoucherTemplates');
            voucherSelectEl.addEventListener('change', function () {
                var selectedTemplateId = parseInt(voucherSelectEl.value, 10);
                _this6.changeTemplate(selectedTemplateId);
            });
        }
    }]);

    return RecrasVoucher;
}();