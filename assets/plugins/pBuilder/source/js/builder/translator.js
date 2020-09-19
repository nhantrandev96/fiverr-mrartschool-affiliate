'use strict';

window.Translator = {

	translations: {},

	get: function(key) {

		if (this.translations[key]) {
			return this.translations[key];
		}

		return key;
	},
};

