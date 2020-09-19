angular.module('builder.editors')

.factory('imageEditor', ['$rootScope', '$http', function($rootScope, $http) {

	var editor = {

		/**
		 * Feather editor instance.
		 * 
		 * @type mixed
		 */
		editor: false,

		/**
		 * Whether editor is already innitiated.
		 * 
		 * @type {Boolean}
		 */
		booted: false,

		/**
		 * Whether editor is innitiating.
		 * 
		 * @type {Boolean}
		 */
		booting: false,

		/**
		 * Old image url for replacing with new one.
		 * 
		 * @type {String}
		 */
		oldUrl: '',

		/**
		 * Api key.
		 * 
		 * @type {String}
		 */
		key: 'fe170a1bbdf8ae40',

		/**
		 * Initiate image editor.
		 * 
		 * @param  nide/string id
		 * @param  string      src 
		 * 
		 * @return void
		 */
		init: function(id, src) {
			this.booting = true;

			this.editor = new Aviary.Feather({
		       	apiKey: editor.key,
		       	apiVersion: 3,
		       	theme: 'dark',
		       	tools: 'all',
		       	appendTo: '',
		       	onSave: function(id, url) {
		       		$http.post('images/', { url: url, aviary: true, oldUrl: editor.oldUrl })
		       		.success(function(data) {
		       			if ($rootScope.selected.node.nodeName === 'IMG') {
		       				$rootScope.selected.node.setAttribute('src', data);
		       				$rootScope.$broadcast('builder.html.changed');
		       			}

		       			editor.oldUrl = '';
		       			editor.editor.close();
		       		});	        		
		       	},
		       	onError: function(errorObj) {
		          console.log(errorObj);
		       	},
		       	onLoad: function() {
		       		editor.booted = true;
		       		editor.booting = false;
		       		editor.open(id, src);
		       	}
		   	});
		},

		/**
		 * Open feather editor with given image.
		 * 
		 * @param  string/node id
		 * @param  string src
		 * 
		 * @return void
		 */
		open: function(id, src) {

			if (this.booting) return;
			
			if ( ! this.booted) {
				this.init(id, src);
			} else {
				this.editor.launch({
	           		image: id,
	           		url: src
	       		});
			}
		}
	}

	return editor;
}])