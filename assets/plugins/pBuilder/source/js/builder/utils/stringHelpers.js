'use strict'

window.blDebounce = function(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        }, wait);
        if (immediate && !timeout) func.apply(context, args);
    };
};

window.utils = {
	getNodeIndex: function(nodeList, node) {
		for (var i = nodeList.length - 1; i >= 0; i--) {
			if (nodeList[i] == node) { return i; };
		};
	},
};

//uppercase fist letter of the word
String.prototype.ucFirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

String.prototype.toCamelCase = function() {
  return this.replace(/[- ](\w)/g, function(match) {
    return match[1].toUpperCase();
  });
}

String.prototype.toTitleCase = function() {
	//camel case to spaces
	var space = this.replace(/([A-Z])/g, function($1){return ' '+$1.toLowerCase();});

	//to title case
    return space.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

//camel case to dashes (some-string)
String.prototype.toDashedCase = function() {
	return this.replace(/([A-Z])/g, function($1){return '-'+$1.toLowerCase();});
};

//double and single qoutes
String.prototype.removeQoutes = function() {
	return this.replace(/["']/g, "");
};

//replace border with in a css value string (1px solid green)
String.prototype.replaceBorderWidth = function(width) {
	if ( ! width) width = 1;
	return this.replace(/^[0-9]+([a-z]+)?( [a-zA-Z]+ .+)$/, width+'px$2');
};

String.prototype.spacify = function() {
	return this.replace(/([0-9])([a-z])/, '$1 $2');
};

//normalize given css string so it's always 4 values instead of various shorthands
String.prototype.normalizeCss = function() {
	var string     = this.replace(/, /g, ','),
		spaceCount = string.split(' ').length - 1;

	if ( ! spaceCount) {
		return Array(5).join(' '+this).trim();
	} else if (spaceCount == 2) {
		var arr = string.split(' ');
		arr.splice(3, 0, arr[1]);
		return arr.join(' ').trim();
	} else {
		return this.toString().trim();
	}
};