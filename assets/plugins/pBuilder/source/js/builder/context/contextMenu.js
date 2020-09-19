'use strict';

angular.module('undoManager')

.controller('ContextMenuController', ['$scope', '$rootScope', 'dom', 'undoManager', 'codeEditors', function($scope, $rootScope, dom, undoManager, codeEditors) {

	$scope.isTable = false;
	$scope.codeEditors = codeEditors;
	$scope.dom = dom;

	$scope.closeContextMenu = function() {
		$rootScope.contextMenuOpen = false;
	};

	/**
	 * Call function on dom manager by passed in
	 * name and hide context menu.
	 * 
	 * @param  string name 
	 * @return void
	 */
	$scope.executeCommand = function(name) {
		
		if (name == 'undo' || name == 'redo') {
			undoManager[name]();
		} else if (name == 'selectParent') {
			console.log($scope.selected.parent);
			if ($scope.selected.parent && $scope.selected.parent.contains($scope.selected.node)) {
				$scope.selectNode($scope.selected.parent);
			}
		} else if (name == 'moveSelected') {
			dom.moveSelected(arguments[1], $scope.selected.node);
		} else if (name == 'viewSource') {
			codeEditors.open('html');
		} else if (dom[name]) {
			dom[name]($scope.selected.node);
		}
		
		$scope.closeContextMenu();
	};
}])

.directive('blIframeContextMenu', ['$rootScope', function($rootScope) {
    return {
   		restrict: 'A',
      	link: function($scope) {
      		$rootScope.$on('builder.dom.loaded', function(e) {
      			var bottomEdge = $('#viewport').height(),
      				rightEdge  = $('#viewport').width(),
      				menu       = $('#context-menu');

	      		$scope.frameBody.on('contextmenu', function(e) {
	      			e.preventDefault();
					e.stopPropagation();

					$scope.$apply(function() {
						$rootScope.contextMenuOpen = true;
					});

					var node = $scope.elementFromPoint(e.pageX, e.pageY - $scope.frameBody.scrollTop());

					$scope.$apply(function() {
                        return node.nodeName === 'TD';
					});


                    if ( ! node.parentNode || node.parentNode.nodeName !== 'LI') {
                        $scope.selectNode(node);
                    }

					var top  = e.pageY - $scope.frameDoc.body.scrollTop,
						left = e.pageX + 10 + $scope.frameOffset.left,
						menuWidth = menu.width(),
						menuHeight = menu.height();


                    //make sure menu doesn't go under bottom edge
                    if (bottomEdge < top + menuHeight) {
                        top = e.pageY - menuHeight - $scope.frameDoc.body.scrollTop + 39;
                    }

                    //make sure menu doesn't go under right edge
                    if (rightEdge < left + $scope.frameOffset.left + menuWidth) {
                        left = left - menuWidth;
                    }

					menu.css({ top: top, left: left }).show();
	      		});
	      	});
      	}
    };
}]);