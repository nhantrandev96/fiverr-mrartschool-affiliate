'use strict'

angular.module('undoManager', [])

.factory('undoManager', ['$rootScope', '$timeout', 'css', function($rootScope, $timeout, css) {

	var manager = {

		/**
		 * Stack of undo/redo commands.
		 * 
		 * @type {Array}
		 */
		commands: [],

		/**
		 * Current position of pointer in undo/redo stack.
		 * 
		 * @type {Number}
		 */
		pointer: -1,

		/**
		 * Whether or not there's any undos left in the stack.
		 * 
		 * @type {Boolean}
		 */
		canUndo: false,

		/**
		 * Whether or not there's any redos left in the stack.
		 * 
		 * @type {Boolean}
		 */
		canRedo: false,

		/**
		 * Whether or not there's any undos left in the stack.
		 * 
		 * @return {Boolean}
		 */
		hasUndo: function () {
            return this.pointer !== -1;
        },

        /**
		 * Whether or not there's any redos left in the stack.
		 * 
		 * @return {Boolean}
		 */
        hasRedo: function () {
            return this.pointer < (this.commands.length - 1);
        },

        updateStatuses: function() {
        	this.canUndo = this.hasUndo();
        	this.canRedo = this.hasRedo();
        },

		/**
		 * Execute undo function of command at current pointers position in stack.
		 * 
		 * @return void
		 */
		undo: function() {
			var command = this.commands[this.pointer];
		
			if (command) {		
				command.undo();
				this.pointer -= 1;
				this.updateStatuses();		
			}	
		},

		/**
		 * Execute redo function of command at current pointers position in stack.
		 * 
		 * @return void
		 */
		redo: function() {
			var command = this.commands[this.pointer + 1];

			if (command) {
				command.redo();
				this.pointer += 1;
				this.updateStatuses();
			}
		},

		/**
		 * Add a new undo/redo command to the stack.
		 * 
		 * @param string name     name of the command
		 * @param object params   command parameters
 		 */
		add: function(name, params) {
			
			//invalidate commands higher on the stack then this one if any exist
			this.commands.splice(this.index + 1, this.commands.length - this.index);

			//make a new command
			var command = new manager[name](params);

			//push it onto the stack
			this.commands.push(command);

			//update pointer position
			this.pointer = this.commands.length - 1;

			//update canUndo/canRedo booleans and prevent
			//apply already in progress errors		
			$timeout(function(){
				$rootScope.$apply(function() {
					manager.updateStatuses();
				});
			});

			return command;
		},

		generic: function(params) {
			this.undo = params.undo;
			this.redo = params.redo;
		},

		/**
		 * Command for undoing/redoing dom node reordering.
		 * 
		 * @param  object params
		 * @return void
		 */
		reorderElement: function(params) {
			this.params = params;		
		},

		/**
		 * Command for undoing/redoing dom node deletion.
		 * 
		 * @param  object params
		 * @return void
		 */
		deleteNode: function(params) {
			this.params = params;
		},

		/**
		 * Command for undoing/redoing new dom node insertion.
		 * 
		 * @param  object params
		 * @return void
		 */
		insertNode: function(params) {
			this.params = params;
		},

		/**
		 * Command for undoing/redoing new column insertion.
		 * 
		 * @param  object params
		 * @return void
		 */
		insertColumn: function(params) {
			this.params = params;
		},

		/**
		 * Command for undoing/redoing element style changes in inspector.
		 * 
		 * @param  object params 
		 * @return void
		 */
		revertStyles: function(params) {
			this.params = params;
		}
	};

	/**
	 * RevertStyles command undo action.
	 * 
	 * @return void
	 */
	manager.revertStyles.prototype.undo = function() {
		css.add(this.params.path, this.params.property, this.params.oldStyles);

		$rootScope.repositionBox('select');
		$rootScope.hoverBox.hide();
	};

	/**
	 * RevertStyles command redo action.
	 * 
	 * @return void
	 */
	manager.revertStyles.prototype.redo = function() {

		if (this.params.redoProps) {
			css.add(this.params.path, this.params.redoProps);
		} else {
			css.add(this.params.path, this.params.property, this.params.newStyles);
		}

		$rootScope.repositionBox('select');
		$rootScope.hoverBox.hide();
	};

	/**
	 * DeleteNode command undo action.
	 * 
	 * @return void
	 */
	manager.deleteNode.prototype.undo = function() {
		this.insertAtIndex = manager.reorderElement.prototype.insertAtIndex;
		this.insertAtIndex();	
	};

	/**
	 * DeleteNode command redo action.
	 * 
	 * @return void
	 */
	manager.deleteNode.prototype.redo = function() {
		this.params.node.remove();
		$rootScope.selectBox.hide();
		$rootScope.hoverBox.hide();
	};

	/**
	 * InsertNode command undo action.
	 * 
	 * @return void
	 */
	manager.insertNode.prototype.undo = function() {
		if (this.params.node) {
			$(this.params.node).remove();
			$rootScope.selectBox.hide();
			$rootScope.hoverBox.hide();
		}	
	};

	manager.insertColumn.prototype.undo = function() {
		this.params.node.remove();
		this.params.resize(this.params.oldNode, '+', 1);
		
		$rootScope.selectBox.hide();
		$rootScope.hoverBox.hide();
	};

	manager.insertColumn.prototype.redo = function() {
		this.insertAtIndex = manager.reorderElement.prototype.insertAtIndex;
		this.insertAtIndex();
		this.params.resize(this.params.oldNode, '-', 1);
	};

	/**
	 * InsertNode command redo action.
	 * 
	 * @return void
	 */
	manager.insertNode.prototype.redo = function() {
		this.insertAtIndex = manager.reorderElement.prototype.insertAtIndex;
		this.insertAtIndex();	
	};

	/**
	 * reorderElement command undo action.
	 * 
	 * @return void
	 */
	manager.reorderElement.prototype.undo = function() {
		this.insertAtIndex('undo');	
	};

	/**
	 * reorderElement command redo action.
	 * 
	 * @return void
	 */
	manager.reorderElement.prototype.redo = function() {
		this.insertAtIndex('redo');	
	};

	/**
	 * Insert node at given index in the parent.
	 * 
	 * @param  string type   prepend or append
	 * @return void
	 */
	manager.reorderElement.prototype.insertAtIndex = function(type) {
		var before = true, parent = $(this.params.parent);
		
		//we'll need to use different index if we're undoing or redoing the insert
		var index = type == 'redo' ? this.params.redoIndex : this.params.undoIndex;

		//if index is zero just prepend node to parent
		if (index === 0) {
			parent.prepend(this.params.node);
		}

		//if index is higher then parent has children just append node to parent
		else if (index+1 >= this.params.parentContents.length) {
			parent.append(this.params.node);
		}
		else {
			var contents = parent.contents(),
				currentIndex = contents.index(this.params.node);

			//if node doesn't exist in the parent contents always insert
			//it before the index otherwise do it depending on the index difference
			before = currentIndex == -1 ? true : currentIndex > index;
			
			//loop trough the parent contents and when index matches
			//prepend or append node to the node current at that index
			for (var i = contents.length - 1; i >= 0; i--) {
				if (i === index) {
	    			if (before) {
	    				return $(contents[i]).before(this.params.node);
	    			} else {
	    				return $(contents[i]).after(this.params.node);
	    			}
	    		}
			}
		}

		$rootScope.repositionBox('select');
	};
	
	builder.undo = manager.commands;

	return manager;
}]);