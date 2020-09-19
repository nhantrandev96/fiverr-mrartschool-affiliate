angular.module('builder')

.factory('keybinds', ['$rootScope', 'dom', 'undoManager', function($rootScope, dom, undoManager) {

	var keybinds = {

		booted: false,

		init: function() {
			if ( ! this.booted) {
			
				$($rootScope.frameDoc.documentElement).keydown(function(e) {

				   	if (e.which === 38) {
				   		// arrow donw
				   		e.preventDefault();
				        dom.moveSelected('up');
				   	} else if (e.which === 40) {
				   		// arrow up
				   		e.preventDefault();
				        dom.moveSelected('down');
				   	} else if (e.which === 46) {
				   		// del
				   		e.preventDefault();
				        dom.delete($rootScope.selected.node);
				   	} else if (e.which === 67 && e.ctrlKey) {
				   		// C + Ctrl
				   		e.preventDefault();
				       	dom.copy($rootScope.selected.node);
				   	} else if (e.which === 86 && e.ctrlKey) {
				   		// V + Ctrl
				   		e.preventDefault();
				       	dom.paste($rootScope.selected.node);
				   	} else if (e.which === 88 && e.ctrlKey) {
				   		// X + Ctrl
				   		e.preventDefault();
				       	dom.cut($rootScope.selected.node);
				   	} else if (e.which === 89 && e.ctrlKey) {
                        // Y + Ctrl
                        undoManager.redo();
                    } else if (e.which === 90 && e.ctrlKey) {
                        // Z + Ctrl
                        undoManager.undo();
                    }
				});

				this.booted = true;
			}
		}
	};

	return keybinds;
	
}]);