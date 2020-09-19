'use strict';

var baseBuilderElements = [];

baseBuilderElements.push({
 	name: 'page header',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'page-header',
 	html: 	'<div class="page-header">'+
					'<h1>Example page header <small>Header subtext</small></h1>'+
			'</div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'typography',
 	previewScale: '0.4',
    icon: 'header'
});

baseBuilderElements.push({
 	name: 'progress bar',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'progress',
 	html:  '<div class="progress">'+
			        '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">'+
				    '60%'+
                '</div>'+
            '</div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'components',
    icon: 'progress-2'
});

baseBuilderElements.push({
 	name: 'list group',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'list-group',
 	html:  '<div class="list-group">'+
			  '<a href="#" class="list-group-item disabled">Cras justo odio</a>'+
			  '<a href="#" class="list-group-item">Dapibus ac facilisis in</a>'+
			  '<a href="#" class="list-group-item">Morbi leo risus</a>'+
			  '<a href="#" class="list-group-item">Porta ac consectetur ac</a>'+
			  '<a href="#" class="list-group-item">Vestibulum at eros</a>'+
			'</div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'components',
 	previewScale: '0.4',
    icon: 'th-list-1',
});

baseBuilderElements.push({
 	name: 'panel',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'panel',
 	html:  '<div class="panel panel-primary">'+
			  '<div class="panel-heading">Panel heading without title</div>'+
			  '<div class="panel-body">'+
			    'Panel content'+
			  '</div>'+
			  '<div class="panel-footer">Panel Footer</div>'+
			'</div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'components',
 	previewScale: '0.4',
    icon: 'window'
});

baseBuilderElements.push({
 	name: 'container',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'container',
 	html: 	'<div class="container"></div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'layout',
 	previewScale: '0.15',
 	dragHelper: true,
    icon: 'squares',
 	attributes: {
 		type: {
	 		list: [
	            {name: 'default', value: 'container'},
	            {name: 'wide', value: 'container-fluid'},
	        ],
	    }
 	},
});

baseBuilderElements.push({
 	name: 'row',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'row',
 	html: 	'<section class="row"><div class="col-md-4"></div><div class="col-md-3"></div><div class="col-md-5"></div></section>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'layout',
 	previewScale: '0.15',
 	dragHelper: true,
    icon: 'minus-outline'
});


baseBuilderElements.push({
 	name: 'well',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'well',
 	html: 	'<div class="well">Look, I\'m in a well!</div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	category: 'layout',
    icon: 'bucket'
});

baseBuilderElements.push({
 	name: 'label',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'label',
 	html: 	'<span class="label label-success">Label</span>',
 	types: ['flow', 'phrasing'],
 	validChildren: ['phrasing'],
 	category: 'typography',
 	previewScale: 2,
 	hiddenClasses: ['label'],
    icon: 'tag'
});

baseBuilderElements.push({
 	name: 'column',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'col-*',
 	html: 	'<div class="col-sm-6"></div>',
 	types: ['flow'],
 	validChildren: ['flow'],
 	canModify: ['text', 'box', 'margin', 'padding', 'attributes']
});

baseBuilderElements.push({
 	name: 'button group',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'btn-group',
 	html: 	'<div class="btn-group">'+
			  '<button type="button" class="btn btn-default">Left</button>'+
			  '<button type="button" class="btn btn-default">Middle</button>'+
			  '<button type="button" class="btn btn-default">Right</button>'+
			'</div>',
 	types: ['flow'],
 	validChildren: ['button'],
 	category: 'buttons',
 	previewScale: '0.9',
    icon: 'columns'
});

baseBuilderElements.push({
 	name: 'button toolbar',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'btn-toolbar',
 	html: 	'<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">'+
		      '<div class="btn-group" role="group" aria-label="First group">'+
		        '<button type="button" class="btn btn-default">1</button>'+
		        '<button type="button" class="btn btn-default">2</button>'+
		        '<button type="button" class="btn btn-default">3</button>'+
		        '<button type="button" class="btn btn-default">4</button>'+
		      '</div>'+
		      '<div class="btn-group" role="group" aria-label="Second group">'+
		        '<button type="button" class="btn btn-default">5</button>'+
		        '<button type="button" class="btn btn-default">6</button>'+
		        '<button type="button" class="btn btn-default">7</button>'+
		      '</div>'+
		      '<div class="btn-group" role="group" aria-label="Third group">'+
		        '<button type="button" class="btn btn-default">8</button>'+
		      '</div>'+
		    '</div>',
 	types: ['flow'],
 	validChildren: ['.btn-group'],
 	category: 'buttons',
 	previewScale: '0.6',
    icon: 'progress-3'
});




//forms

baseBuilderElements.push({
 	name: 'input field',
 	nodes: ['input=text', 'input=email', 'input=password'],
 	frameworks: ['bootstrap'],
 	html: '<input type="text" class="form-control" placeholder="Text input">',
 	types: ['flow', 'phrasing', 'interactive', 'listed', 'labelable', 'submittable', 'resettable', 'reassociateable', 'form-associated'],
 	validChildren: false,
 	previewScale: '0.5',
 	showWysiwyg: false,
 	hiddenClasses: ['form-control'],
 	category: 'forms',
    icon: 'progress-0',
	attributes: {
 		placeholder: {
 			text: true,
 			value: 'Text input',
 			onAssign: function($scope) {
 				this.value = $scope.selected.node.getAttribute('placeholder');
 			},
 			onChange: function($scope, text) {
 				$scope.selected.node.setAttribute('placeholder', text);
 				$scope.repositionBox('select');
 			}
 		},
 		type: {
 			list: [
	            {name: 'Text', value: 'text'},
	            {name: 'Password', value: 'password'},
	            {name: 'Date', value: 'date'},
	            {name: 'Email', value: 'email'},
	            {name: 'Datetime', value: 'datetime'},
	            {name: 'Datetime Local', value: 'datetime-local'},
	            {name: 'Month', value: 'month'},
	            {name: 'Time', value: 'time'},
	            {name: 'Week', value: 'week'},
	            {name: 'Number', value: 'number'},
	            {name: 'Url', value: 'url'},
	            {name: 'Search', value: 'search'},
	            {name: 'Tel', value: 'tel'},
	            {name: 'Color', value: 'color'},
	        ],
	        value: false,
 			onAssign: function($scope) {
 				for (var i = this.list.length - 1; i >= 0; i--) {
 					if ($scope.selected.node.getAttribute('type') == this.list[i].value) {
 						return this.value = this.list[i];
 					}
 				};

 				return this.value = this.list[0];
 			},
 			onChange: function($scope, type) {
 				$scope.selected.node.setAttribute('type', type.value);
 			}
 		}
 	},
});

baseBuilderElements.push({
 	name: 'text area',
 	nodes: ['textarea'],
 	frameworks: ['bootstrap'],
 	html: '<textarea class="form-control" rows="3"></textarea>',
 	types: ['flow', 'phrasing', 'interactive', 'listed', 'labelable', 'submittable', 'resettable', 'reassociateable', 'form-associated'],
 	validChildren: false,
 	previewScale: '0.5',
 	showWysiwyg: false,
 	hiddenClasses: ['form-control'],
 	category: 'forms',
    icon: 'doc-landscape',
 	attributes: {
 		rows: {
 			text: true,
 			value: 1,
 			onAssign: function($scope) {
 				this.value = $scope.selected.node.getAttribute('rows');
 			},
 			onChange: function($scope, rows) {
 				$scope.selected.node.setAttribute('rows', rows);
 				$scope.repositionBox('select');
 			}
 		},
 		placeholder: {
 			text: true,
 			value: 'Text input',
 			onAssign: function($scope) {
 				this.value = $scope.selected.node.getAttribute('placeholder');
 			},
 			onChange: function($scope, text) {
 				$scope.selected.node.setAttribute('placeholder', text);
 				$scope.repositionBox('select');
 			}
 		},
 	},
});

baseBuilderElements.push({
 	name: 'checkbox',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'checkbox',
 	html: '<div class="checkbox"><label><input type="checkbox">Option #1</label></div>',
 	types: ['flow', 'phrasing', 'interactive', 'listed', 'labelable', 'submittable', 'resettable', 'reassociateable', 'form-associated'],
 	validChildren: false,
 	category: 'forms',
 	showWysiwyg: false,
    icon: 'check'
});

baseBuilderElements.push({
 	name: 'input group',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'input-group',
 	html: '<div class="form-group">'+
		    '<div class="input-group">'+
		      '<div class="input-group-addon">@</div>'+
		      '<input class="form-control" type="email" placeholder="Enter email">'+
		    '</div>'+
		  '</div>',
 	types: ['flow'],
 	validChildren: false,
 	attributes: {
 		size: {
	 		list: [
	            {name: 'Medium', value: ''},
	            {name: 'Large', value: 'input-group-lg'},
	            {name: 'Small', value: 'input-group-sm' },
	        ],
	    }
 	},
 	previewScale: '0.5',
 	showWysiwyg: false,
 	category: 'forms',
    icon: 'popup',
 	hiddenClasses: ['input-group'],
});

baseBuilderElements.push({
 	name: 'form group',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'form-group',
 	html: '<div class="form-group">'+
	    	'<label for="email" class="control-label">Email address</label>'+
	    	'<input type="email" class="form-control" id="email" placeholder="Enter email">'+
	  	  '</div>',
 	types: ['flow'],
 	validChildren: false,
 	attributes: {
 		state: {
 			list: [
 				{name: 'None', value: ''},
	            {name: 'Error', value: 'has-error'},
	            {name: 'Success', value: 'has-success'},
	            {name: 'Warning', value: 'has-warning'},
	        ]
 		}
 	},
 	previewScale: '0.5',
 	showWysiwyg: false,
 	category: 'forms',
    icon: 'menu',
 	hiddenClasses: ['form-group']
});

baseBuilderElements.push({
	name: 'link',
 	frameworks: ['base', 'bootstrap'],
 	nodes: ['a'],
 	html: '<a href="#">A simple hyperlink.</a>',
 	types: ['flow', 'phrasing', 'interactive'],
 	validChildren: ['flow'],
 	category: 'typography',
 	onEdit: function($scope) {
 		$scope.linker.removeClass('hidden');

 		var left = 0, top = 0,
 			pos = $scope.selected.node.getBoundingClientRect(),
 			rightEdge = $('#viewport').width(),
            bottomEdge = $('#viewport').height(),
 			leftEdge = $('#elements-container')[0].getBoundingClientRect(),
 			linkerRight = pos.left + $scope.frameOffset.left + $scope.linker.width(),
 			linkerTop  = pos.top + $scope.frameOffset.top + $scope.linker.height();

 		//make sure linker doesn't go over right sidebar
 		if (rightEdge.left < linkerRight) {
 			left = pos.left - (linkerRight - rightEdge.left) - 40;
 		} else {
 			left = pos.left - ($scope.linker.width() - $scope.selected.node.offsetWidth)/2;
 		}

 		//position linker either above or below link dom element depending on space available
 		if (bottomEdge < linkerTop) {
 			top = pos.top - $scope.selected.node.offsetHeight - $scope.linker.height() - 10;
 		} else {
 			top = pos.top + $scope.selected.node.offsetHeight;
 		}

 		//make sure linker doesn't go under the left sidebar
 		if (left < leftEdge.left) {
 			left = leftEdge.left + 30;
 		}

 		$scope.linker.css({ top: top, left: left});
 	},
    icon: 'link-outline'
});


baseBuilderElements.push({
 	name: 'addon',
 	nodes: '*',
 	frameworks: ['bootstrap'],
 	class: 'input-group-addon',
 	html: false,
 	canDrag: false,
 	types: ['flow'],
 	validChildren: false,
 	canModify: ['text', 'attributes'],
 	attributes: {
 		side: {
	 		list: [
	            {name: 'Left', value: 'left'},
	            {name: 'Right', value: 'right'},
	        ],
	        value: false,
 			onAssign: function($scope) {
 				if ( ! $($scope.selected.node).index()) {
 					this.value = this.list[0];
 				} else {
 					this.value = this.list[1];
 				}
 			},
 			onChange: function($scope, position) {
 				var childs = $($scope.selected.parent).children();

 				//insert input group addon either before first element
 				//of parent or after the last one
 				if (position.value == 'right') {
 					$(childs[childs.length-1]).after($scope.selected.node);
 				} else {
 					$(childs[0]).before($scope.selected.node);
 				}
 			}
	    },
	    contents: {
	    	list: [
	    		{name: 'Text', value: 'text'},
	    		{name: 'Checkbox', value: 'checkbox'},
	    		{name: 'Radio', value: 'radio'},
	    		{name: 'Button', value: 'button'},
	    		{name: 'Dropdown', value: 'dropdown'},
	    	],
	    	onAssign: function($scope) {
	    		var childs = $($scope.selected.node).children();

 				if (childs.length == 0) {
 					this.value = this.list[0];
 				} else if (childs[0].type == 'checkbox') {
 					this.value = this.list[1];
 				} else if (childs[0].type == 'radio') {
 					this.value = this.list[2];
 				} else if (childs[0].nodeName == 'BUTTON') {
 					this.value = this.list[3];
 				} else if (childs.length > 1) {
 					this.value = this.list[4];
 				}
 			},
 			onChange: function($scope, contents) {

 				//text
 				if (contents.value == 'text') {
 					$($scope.selected.node).removeClass().addClass('input-group-addon');
 					$($scope.selected.node).html('@');
 				//checkbox
 				} else if (contents.value == 'checkbox') {
 					$($scope.selected.node).removeClass().addClass('input-group-addon');
 					$($scope.selected.node).html('<input type="checkbox">');
 				//radio
 				} else if (contents.value == 'radio') {
 					$($scope.selected.node).removeClass().addClass('input-group-addon');
 					$($scope.selected.node).html('<input type="radio">');
 				//button
 				} else if (contents.value == 'button') {
 					$($scope.selected.node).removeClass().addClass('input-group-btn');
 					$($scope.selected.node).html('<button class="btn btn-default" type="button">Go!</button>');
 				//dropdown
 				} else if (contents.value == 'dropdown') {
 					$($scope.selected.node).removeClass().addClass('input-group-btn');
 					$($scope.selected.node).html(
 						'<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>'+
				        '<ul class="dropdown-menu" role="menu">'+
				          '<li><a href="#">Action</a></li>'+
				          '<li><a href="#">Another action</a></li>'+
				          '<li><a href="#">Something else here</a></li>'+
				          '<li class="divider"></li>'+
				          '<li><a href="#">Separated link</a></li>'+
				        '</ul>'
				    );
 				}
 			}
	    }
 	},
 	showWysiwyg: false,
 	hiddenClasses: ['input-group-addon'],
});

baseBuilderElements.push({
 	name: 'select',
 	nodes: ['select'],
 	frameworks: ['bootstrap'],
 	html:'<select class="form-control">'+
		  '<option>1</option>'+
		  '<option>2</option>'+
		  '<option>3</option>'+
		  '<option>4</option>'+
		  '<option>5</option>'+
		 '</select>',
 	types: ['flow', 'phrasing', 'interactive', 'listed', 'labelable', 'submittable', 'resettable', 'reassociateable', 'form-associated'],
 	validChildren: false,
 	attributes: {
 		state: {
 			list: [
 				{name: 'None', value: ''},
	            {name: 'Error', value: 'has-error'},
	            {name: 'Success', value: 'has-success'},
	            {name: 'Warning', value: 'has-warning'},
	        ],
 		}
 	},
 	previewScale: '0.5',
 	showWysiwyg: false,
 	category: 'forms',
    icon: 'arrow-combo'
});

baseBuilderElements.push({
 	name: 'image',
 	nodes: ['img'],
 	frameworks: ['bootstrap'],
 	html: '<img src="http://ironsummitmedia.github.io/startbootstrap-freelancer/img/profile.png" class="img-responsive">',
 	types: ['flow', 'phrasing', 'embedded', 'interactive', 'form-associated'],
 	validChildren: false,
 	category: 'media',
    icon: 'picture-outline',
 	canModify: ['padding', 'margin', 'attributes', 'shadows', 'borders'],
    hideEditIcon: true,
 	previewScale: '0.3',
 	attributes: {
 		shape: {
 			list: [
 				{name: 'Default', value: ''},
	            {name: 'Rounded', value: 'img-rounded'},
	            {name: 'Thumbnail', value: 'img-thumbnail'},
	            {name: 'Circle', value: 'img-circle'},
	        ],
 		},
        url: {
            text: true,
            value: '//www.youtube.com/embed/wGp0GAd1d1s',
            onAssign: function($scope) {
                this.value = $scope.selected.node.src;
            },
            onChange: function($scope, url) {
                $scope.selected.node.src = url;
            }
        }
 	},
});

baseBuilderElements.push({
 	name: 'responsive video',
 	nodes: '*',
 	class: 'embed-responsive',
 	frameworks: ['bootstrap'],
 	html: '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="//www.youtube.com/embed/sENM2wA_FTg"></iframe></div>',
 	types: ['flow'],
 	validChildren: false,
 	category: 'media',
    icon: 'video',
    hideEditIcon: true,
 	canModify: ['padding', 'margin', 'shadows', 'attributes'],
 	previewScale: '0.7',
 	previewHtml: '<img data-name="responsive video t" data-src="//www.youtube.com/embed/sENM2wA_FTg" class="img-responsive preview-node" src="'+ window.baseUrl +'public/images/previews/responsiveEmbedPreview.png">',
 	attributes: {
 		url: {
 			text: true,
 			value: '//www.youtube.com/embed/wGp0GAd1d1s',
 			onAssign: function($scope) {
 				this.value = $scope.selected.node.dataset.src;
 			},
 			onChange: function($scope, url) {
 				$scope.selected.node.dataset.src = url;
 			}
 		}
 	},
 	hiddenClasses: ['embed-responsive', 'preview-node', 'img-responsive'],
});

baseBuilderElements.push({
 	name: 'image grid',
 	nodes: '*',
 	class: 'image-grid',
 	frameworks: ['bootstrap'],
 	html:   '<div class="row image-grid">'+
			 '<div class="col-xs-3">'+
			    '<a href="#" class="thumbnail">'+
			      '<img src="templates/freelancer/images/portfolio/cabin.png">'+
			    '</a>'+
			  '</div>'+
			  '<div class="col-xs-3">'+
			    '<a href="#" class="thumbnail">'+
			      '<img src="templates/freelancer/images/portfolio/cabin.png">'+
			    '</a>'+
			  '</div>'+
			  '<div class="col-xs-3">'+
			    '<a href="#" class="thumbnail">'+
			      '<img src="templates/freelancer/images/portfolio/cabin.png">'+
			    '</a>'+
			  '</div>'+
			  '<div class="col-xs-3">'+
			    '<a href="#" class="thumbnail">'+
			      '<img src="templates/freelancer/images/portfolio/cabin.png">'+
			    '</a>'+
			  '</div>'+
			'</div>',
 	types: ['flow'],
 	validChildren: false,
 	category: 'media',
    icon: 'grid',
 	canModify: ['padding', 'margin', 'shadows', 'attributes'],
 	previewScale: '0.2'
});



