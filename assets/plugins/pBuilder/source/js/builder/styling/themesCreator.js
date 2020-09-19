angular.module('builder.styling')

.controller('ThemesCreatorController', ['$scope', '$http', 'themes', function($scope, $http, themes) {
	
	$scope.themes = themes;

	//less variables that have been modified for the current theme.	
	$scope.modifiedVars = {};

	//custom users less for the theme, if any
	$scope.customLess = false;

	//controls custom less panel visibility
	$scope.customLessOpen = false;

	//bootstrap theme variables
	$scope.bootstrap = {
		defaultVars: false,
		activeVars: false,
		currentTheme: false,
	};

	//themes that's currently being edited variables
	$scope.editing = {
		theme: '',
		name: '',
		type: 'private',
		saving: false,
		errorMessage: false,
	};

	//if we're navigating away from theme creator clear the inputs
	$scope.$watch('activePanel', function(name, oldName) {
		if (oldName == 'themesCreator') {
			$scope.editing = {
				theme: '',
				name: '',
				type: 'private',
				saving: false,
				errorMessage: false,
			};

			themes.editing = false;
		}
	});

	$scope.applyChanges = function() {
		var dirty = [];

		//loop trough default bootstrap variables and current
		//ones and push any that don't match into dirty array 
		for (var group in $scope.bootstrap.currentTheme) {
			var vars = $scope.bootstrap.currentTheme[group].variables;
			
			for (var i = vars.length - 1; i >= 0; i--) {
				if (vars[i].value !== $scope.bootstrap.defaultVars[group].variables[i].value) {
					var temp = {}; temp[vars[i].name] = vars[i].value;
					dirty.push(temp);
				}
			};
		}

		$scope.modifyVars(dirty);
	};

	$scope.$watch('bootstrap.currentTheme', function(newT, oldT) {
		if ( ! newT || ! oldT) return;

		var active   = $scope.bootstrap.activeGroup,
			group    = newT[active].variables,
			oldGroup = oldT[active].variables,
			dirty    = {};

		for (var i = group.length - 1; i >= 0; i--) {
			var variable = group[i];

			if (variable.value !== oldGroup[i].value) {
				dirty[variable.name] = variable.value;
			}
		};

		$scope.modifyVars(dirty);
	}, true);

	//whether or not passed value is a less color value
	$scope.isColor = function(string) {
		return string.indexOf('#') > -1 ||
			   string.indexOf('darken') > -1 ||
			   string.indexOf('lighten') > -1;
	};

	$scope.getPreviewStyle = function(string) {
		var color = string;

		return {
			'border-right-color': color,
			'border-right-width': '25px',
		}
	};

	$scope.modifyVars = function(vars, ignore) {

		//if we get passed a string it's a value that
		//we simply need to aplly to modified vars
		if (angular.isString(vars)) {
			$scope.modifiedVars[$scope.activeVar] = vars;

		//if it's an array we'll need to loop through it
		//and extend modified vars with each object
		} else if (angular.isArray(vars)) {
			for (var i = vars.length - 1; i >= 0; i--) {
				$.extend($scope.modifiedVars, vars[i]);
			};

		//it's an object, we just need to extend modified vars
		} else {
			$.extend($scope.modifiedVars, vars);
		}

		//there are unsaved changes
		if ( ! ignore) {
			$scope.dirty = true;
		}
	
		$scope.less.modifyVars($scope.modifiedVars);
	};

	$scope.saveImage = function() {
		html2canvas($scope.doc.find('#preview-screen'), {width: 506}).then(function(canvas) {
			$http.post('pr-themes/save-image/', { image: canvas.toDataURL('image/png', '0.7'), theme: $scope.editing.name })		
		})
	};

	$scope.saveCurrent = function() {
		$scope.editing.errorMessage = false;
		$scope.editing.saving = true;

		var payload = {
			vars: $scope.modifiedVars,
			theme: $scope.editing.theme,
			name: $scope.editing.name,
			type: $scope.editing.type,
			image: 'themes/'+$scope.editing.name+'/image.png',
			custom: $scope.customLess,
		};

		$http.post('pr-themes/', payload).success(function(data) {
			$scope.editing.saving = false;
			$scope.dirty = false;

			if (angular.isObject(data)) {
				$scope.editing.theme = data;
				themes.all.push(data);
			} else {
				$scope.editing.theme = '';
			}
			
			$scope.saveImage();
		}).error(function(data) {
			$scope.editing.saving = false;
			$scope.editing.errorMessage = data;
		});
	};

	$scope.toggleCustomLessPanel = function() {
		if ($scope.customLessOpen) {
			$scope.customLessOpen = false;
		} else {
			$scope.customLessOpen = true;
		}

		setTimeout(function() {
			$scope.lessEditor.resize();
			$scope.lessEditor.focus();
		}, 250);
	};
}]);

