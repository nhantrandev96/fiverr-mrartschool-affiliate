<!DOCTYPE html>
<html id="architect">
<head>
	<title>Page Builder</title>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
	<link rel="stylesheet" href="<?= base_url('assets/plugins/pBuilder/') ?>public/css/builder.css?v=<?= av() ?>">
	<script type="text/javascript">
     window.baseUrl = '<?= base_url("assets/plugins/pBuilder/") ?>';
     window.rootURL = '<?= base_url() ?>';
    </script>
    
    <link rel="stylesheet" href="<?= base_url('assets/plugins/pBuilder') ?>/public/css/font-awesome.min.css?v=<?= av() ?>">
</head>

<body ng-app="builder">
    <div id="splash" ng-cloak>
        <div id="splash-spinner"></div>
    </div>

	<div ng-cloak style="height: 100%" ng-controller="BuilderController">
        <section id="viewport" class="clearfix">
            <div class="flyout-from-right" ng-class="flyoutOpen ? 'open' : 'closed'">
                <div class="flyout-content" ng-class="activePanel == 'export' ? 'open' : 'closed'" ng-controller="ExportController" id="export-panel" bl-render-export-panel>
                    <div bl-close-flyout-panel></div>
                    <div ng-if="isDemo" class="demo-overlay"><p class="alert alert-info">Export functionality is disabled on demo site. Normally you would be able to export an entire project, a single page or image(s) in a .zip file or copy any of the pages markup or styles into clipboard.</p></div>
                    <div class="row inner-content" ng-class="{demo: isDemo}">
                        <div class="images-column" ng-class="images.length ? 'col-md-2' : 'hidden'" bl-export-images>
                            <h2><span>{{ 'images' | translate }}</span></h2>
                            <ul class="list-unstyled">
                                <li ng-repeat="url in images" ng-style="{'background-image': 'url('+url+')'}"></li>
                            </ul>
                        </div>
                        <div class="html-column" ng-class="images.length ? 'col-md-5' : 'col-md-6'">
                            <h2><span>{{ 'markup' | translate }}</span> <button id="copy-html" class="btn btn-default">{{ 'copyToClipboard' | translate }}</button></h2>
                            <div id="html-export-preview"></div>
                        </div>
                        <div class="css-column" ng-class="images.length ? 'col-md-5' : 'col-md-6'">
                            <h2><span>{{ 'style' | translate }}</span> <button id="copy-css" class="btn btn-default">{{ 'copyToClipboard' | translate }}</button></h2>
                            <div id="css-export-preview"></div>
                        </div>
                    </div>
                    <div class="row flyout-panel-toolbar" ng-class="{demo: isDemo}">
                        <div class="center-block">
                            <button bl-export-project class="btn btn-primary"><i class="icon icon-download"></i> {{ 'downloadAsZipFile' | translate }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="description-container"></div>
            <aside id="elements-container" ng-controller="ElementsPanelController">
                <section id="elements-panel" bl-el-panel-filterable bl-el-panel-searchable ng-controller="NavbarController">
                    <div class="main-nav" left-panel-navigation>
                        <selected-tab></selected-tab>
                        <div class="nav-item" data-name="elements"><i class="icon icon-puzzle-outline"></i> <span>{{ 'elements' | translate }}</span></div>
                        <div class="nav-item" data-name="inspector"><i class="icon icon-brush-1"></i> <span>{{ 'inspector' | translate }}</span></div>
                        <div class="nav-item" data-name="pages" style="display: none"><i class="icon icon-docs"></i> <span>{{ 'pages' | translate }}</span></div>
                        <div class="nav-item" data-name="settings"><i class="icon icon-cog-outline"></i> <span>{{ 'settings' | translate }}</span></div>
                        <div class="nav-item" ng-click="toggleCodeEditor()" ng-class="{ active: codeEditors.currentlyOpen }" not-selectable><i class="icon icon-code"></i> <span>{{ 'codeEditor' | translate }}</span></div>
                        <div class="push-bottom">
                            <button class="nav-item" ng-click="undo()" ng-disabled="!undoManager.canUndo" not-selectable><i class="icon icon-reply"></i> <span>{{ 'undo' | translate }}</span></button>
                            <button class="nav-item" ng-click="redo()" ng-disabled="!undoManager.canRedo" not-selectable><i class="icon icon-forward"></i> <span>{{ 'redo' | translate }}</span></button>
                        </div>
                    </div>
                    <div class="panel-inner">
                        <div class="panel" ng-class="{ open: panels.active === 'elements' }" data-name="elements" bl-pretty-scrollbar bl-panels-accordion>
                            <section id="el-panel-top">
                                <div id="panel-search" class="panel-heading-input">
                                    <input type="text" class="form-control" id="el-search" placeholder="{{ 'searchElements' | translate }}" ng-model="query" ng-model-options="{ debounce: 300 }">
                                </div>
                                <div id="el-preview-container" ng-if="settings.get('showElementPreview')" bl-element-preview>
                                    <iframe frameborder="0"></iframe>
                                </div>
                            </section>
                            <div id="elements-list">
                                <div class="elements-box accordion-item open" id="components">
                                    <h3 class="accordion-heading">{{ 'components' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                                <div class="elements-box accordion-item" id="layout">
                                    <h3 class="accordion-heading">{{ 'layout' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                                <div class="elements-box accordion-item" id="media">
                                    <h3 class="accordion-heading">{{ 'media' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                                <div class="elements-box accordion-item" id="typography">
                                    <h3 class="accordion-heading">{{ 'typography' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                                <div class="elements-box accordion-item" id="buttons">
                                    <h3 class="accordion-heading">{{ 'buttons' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                                <div class="elements-box accordion-item" id="forms">
                                    <h3 class="accordion-heading">{{ 'forms' | translate }} <i class="icon icon-down-open-1"></i></h3>
                                    <div class="accordion-body"><ul class="list-unstyled"></ul></div>
                                </div>
                            </div>
                        </div>
                        <aside id="inspector" ng-class="{ open: panels.active === 'inspector' }" class="panel" data-name="inspector" ng-controller="InspectorController" bl-color-picker bl-panels-accordion>
                            <div id="inspector-overlay" ng-show="!selected.node"><div class="overlay-content"><i class="icon icon-brush-1"></i> {{ 'toEditStylesPlease' | translate }}</div></div>
                            <section id="inspector-inner" bl-pretty-scrollbar bl-panels-collapsable>
                                <ol class="element-path">
                                    <li ng-repeat="el in selected.path" ng-class="{ active: $last }" ng-click="selectNode($index)"><span class="name">{{ el.name }}</span> <i class="icon icon-angle-right"></i></li>
                                </ol>
                                <div ng-show="canEdit('attributes')" ng-controller="AttributesController" class="inspector-panel accordion-item open" id="attributes-panel">
                                    <h4 class="accordion-heading">{{ 'attributes' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                    <div class="accordion-body">
                                        <div class="panel-box">
                                            <div id="custom-attributes">
                                                <div id="visibility">
                                                    <ul class="list-unstyled list-inline" bl-element-visibility-controls>
                                                        <li data-size="xs" data-toggle="tooltip" data-placement="top" title="visible on mobile"><i class="icon icon-mobile"></i></li>
                                                        <li data-size="sm" data-toggle="tooltip" data-placement="top" title="visible on tablet"><i class="icon icon-tablet-1"></i></li>
                                                        <li data-size="md" data-toggle="tooltip" data-placement="top" title="visible on laptop"><i class="icon icon-laptop"></i></li>
                                                        <li data-size="lg" data-toggle="tooltip" data-placement="top" title="visible on desktop"><i class="icon icon-desktop"></i></li>
                                                    </ul>
                                                </div>
                                                <div class="form-group" ng-repeat="(name, config) in customAttributes">
                                                    <label for="el-{{ name }}">{{ name }}</label>
                                                    <!-- Render custom text input option -->
                                                    <input ng-class="name.length > 7 ? 'long-name' : ''" ng-if="config.text" type="text" id="el-{{ name }}" ng-model="config.value" ng-model-options="{ debounce: 300 }">
                                                    <!-- Render custom select input option -->
                                                    <select ng-if="config.list" id="el-{{ name }}" class="pretty-select" ng-model="config.value" ng-options="item.name for item in config.list"></select>
                                                    <!-- /end custom options -->
                                                </div>
                                            </div>
                                            <div class="form-group" ng-show="canEdit('float')">
                                                <label for="el-id">{{ 'float' | translate }}</label>
                                                <select id="el-float" class="pretty-select" ng-model="attributes.float">
                                                    <option value="none">{{ 'none' | translate }}</option>
                                                    <option value="pull-left">{{ 'left' | translate }}</option>
                                                    <option value="center-block">{{ 'center' | translate }}</option>
                                                    <option value="pull-right">{{ 'right' | translate }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="el-id">{{ 'id' | translate }}</label>
                                                <input class="pull-right" type="text" id="el-id" ng-model="attributes.id" ng-model-options="{ debounce: 300 }">
                                            </div>
                                            <div class="clearfix form-group">
                                                <label for="el-id">{{ 'class' | translate }}</label>
                                                <div id="el-class" bl-add-class-panel>
                                                    <ul class="list-unstyled list-inline">
                                                        <li class="label" ng-repeat="class in attributes.class" ng-if="class">{{ class }} <i class="icon icon-cancel" ng-click="removeClass(class); $event.stopPropagation();"></i></li>
                                                    </ul>
                                                    <div id="addclass-flyout" class="hidden">
                                                        <input type="text" id="addclass-input" ng-model="classInput">
                                                        <button class="btn btn-sm btn-success add-class"> <i class="icon icon-ok-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div ng-controller="MediaManagerController">
                                                <button ng-if="selected.isImage" ng-file-select="onFileSelect($files, 'src')" class="btn btn-primary btn-block">{{ 'uploadImage' | translate }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <section id="background-panel" class="inspector-panel accordion-item" ng-controller="BackgroundController" ng-show="canEdit('background')">
                                    <h4 class="accordion-heading">{{ 'background' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                    <div class="accordion-body">
                                        <div class="panel-box">
                                            <div data-controls="properties.color" class="color-picker-trigger" id="fill-color"><div class="background-box"><i class="icon icon-color-adjust"></i></div><div class="background-name">{{ 'color' | translate }}</div></div>
                                            <div bl-show-img-container id="image"><div class="background-box"><i class="icon icon-picture-outline"></i></div><div class="background-name">{{ 'image' | translate }}</div></div>
                                            <div bl-show-img-container id="gradient"><div class="background-box"><i class="icon icon-pipette"></i></div><div class="background-name">{{ 'gradient' | translate }}</div></div>
                                        </div>
                                    </div>
                                    <div id="background-flyout-panel" class="hidden">
                                        <div class="bl-panel-header clearfix"><div class="name">{{ 'background' | translate }}</div><div class="bl-panel-btns" ng-click="closePanel"><i class="icon icon-cancel"></i></div></div>
                                        <div ng-controller="MediaManagerController">
                                            <button type="button" ng-file-select="onFileSelect($files, 'bg')" class="btn btn-primary btn-block">{{ 'uploadImage' | translate }}</button>
                                        </div>
                                        <div id="texturePresets">
                                            <h5>{{ 'textures' | translate }}</h5>
                                            <ul class="img-presets-list" bl-pretty-scrollbar>
                                                <li ng-repeat="texture in textures track by $index">
                                                    <div ng-click="selectPreset($event)" class="preset" ng-style="{ 'background-image': 'url(<?= base_url("assets/plugins/pBuilder/") ?>public/images/textures/'+$index+'.png)' }"></div>
                                                </li>
                                            </ul>
                                            <div id="image-properties">
                                                <h5>{{ 'imageProperties' | translate }}</h5>
                                                <div id="img-positioning" class="clearfix">
                                                    <div class="pull-left">
                                                        <h6>{{ 'repeat' | translate }}</h6>
                                                        <ul class="list-unstyled">
                                                            <li><div class="radio"><label><input value="no-repeat" ng-checked="properties.repeat == 'no-repeat'" ng-model="properties.repeat" type="radio">{{ 'none' | translate }}</label></div></li>
                                                            <li><div class="radio"><label><input value="repeat-x" ng-checked="properties.repeat == 'repeat-x'" ng-model="properties.repeat" type="radio">{{ 'horizontal' | translate }}</label></div></li>
                                                            <li><div class="radio"><label><input value="repeat-y" ng-checked="properties.repeat == 'repeat-y'" ng-model="properties.repeat" type="radio">{{ 'vertical' | translate }}</label></div></li>
                                                            <li><div class="radio"><label><input value="repeat" ng-checked="properties.repeat == 'repeat'" ng-model="properties.repeat" type="radio">{{ 'all' | translate }}</label></div></li>
                                                        </ul>
                                                    </div>
                                                    <div class="pull-right">
                                                        <h6>{{ 'alignment' | translate }}</h6>
                                                        <div id="alignment" bl-img-alignment-grid></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="gradientPresets">
                                            <h5>{{ 'gradients' | translate }}</h5>
                                            <ul class="img-presets-list" bl-pretty-scrollbar>
                                                <li ng-repeat="gradient in gradients track by $index">
                                                    <div class="preset" ng-click="selectPreset($event)" ng-style="{ 'background-image': gradient }"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                                <section id="shadows-panel" class="inspector-panel accordion-item" ng-controller="ShadowsController">
                                    <div ng-show="canEdit('shadows')">
                                        <h4 class="accordion-heading">{{ 'shadows' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                        <div class="accordion-body">
                                            <div class="panel-box clearfix">
                                                <div id="shadow-knob-container">
                                                    <input type="text" value="0" bl-knob>
                                                    <div data-toggle="tooltip" data-placement="top" title="Shadow Color" data-controls="props.color" class="color-picker-trigger" bl-shadow-color-preview></div>
                                                </div>
                                                <div class="slider-group">
                                                    <div class="range-slider">
                                                        <div class="slider-label">{{ 'distance' | translate }}</div>
                                                        <div bl-range-slider="props.distance" max="20"></div>
                                                    </div>
                                                    <input type="text" ng-model="props.distance" class="pretty-input">
                                                </div>
                                                <div class="slider-group">
                                                    <div class="range-slider">
                                                        <div class="slider-label">{{ 'blur' | translate }}</div>
                                                        <div bl-range-slider="props.blur" max="20"></div>
                                                    </div>
                                                    <input type="text" ng-model="props.blur" class="pretty-input">
                                                </div>
                                                <div class="slider-group" ng-if="props.type == 'boxShadow'">
                                                    <div class="range-slider">
                                                        <div class="slider-label">{{ 'spread' | translate }}</div>
                                                        <div bl-range-slider="props.spread" max="20"></div>
                                                    </div>
                                                    <input type="text" ng-model="props.spread" class="pretty-input">
                                                </div>
                                                <select bl-pretty-select="props.type" data-width="100%">
                                                    <option value="boxShadow">{{ 'box' | translate }}</option>
                                                    <option value="textShadow">{{ 'text' | translate }}</option>
                                                </select>
                                                <select ng-if="props.type == 'boxShadow'" bl-pretty-select="props.inset" data-width="100%">
                                                    <option value="" selected>Outter</option>
                                                    <option value="inset">{{ 'inner' | translate }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section ng-controller="MarginPaddingController">
                                    <div id="padding-panel" class="inspector-panel accordion-item" ng-show="canEdit('padding')">
                                        <h4 class="accordion-heading">{{ 'padding' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                        <div class="accordion-body">
                                            <div class="clearfix panel-box">
                                                <div class="checkboxes clearfix" bl-checkboxes="padding"></div>
                                                <div bl-range-slider="padding"></div>
                                                <div bl-input-boxes="padding" class="clearfix input-boxes"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="margin-panel" class="inspector-panel accordion-item" ng-show="canEdit('margin')">
                                        <h4 class="accordion-heading">{{ 'margin' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                        <div class="accordion-body clearfix">
                                            <div class="panel-body">
                                                <section class="checkboxes clearfix" bl-checkboxes="margin"></section>
                                                <div bl-range-slider="margin"></div>
                                                <div bl-input-boxes="margin" class="clearfix input-boxes"></div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- text style box starts -->
                                <section ng-show="canEdit('text')" class="inspector-panel accordion-item" id="text-panel" ng-controller="TextController">
                                    <h4 class="accordion-heading">{{ 'textStyle' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                    <div class="accordion-body">
                                        <div id="text-box" class="clearfix panel-box">
                                            <div class="clearfix">
                                                <select id="el-font-family" bl-pretty-select="inspector.styles.text.fontFamily" data-width="200" class="pull-left">
                                                    <option value="">Font</option>
                                                    <option ng-repeat="font in textStyles.baseFonts" data-font-family="{{ font.css }}" value="{{ font.css }}">{{ font.name }}</option>
                                                </select>
                                                <div id="more-fonts" class="pull-right" data-toggle="modal" data-target="#fonts-modal"><i class="icon icon-google"></i></div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="icon-box italic" bl-toggle-text-style="fontStyle|italic">I</div>
                                                <div class="icon-box underline" bl-toggle-text-decoration="underline" ng-class="inspector.styles.text.textDecoration.indexOf('underline') > -1 ? 'active' : ''">U</div>
                                                <div class="icon-box strike" bl-toggle-text-decoration="line-through" ng-class="inspector.styles.text.textDecoration.indexOf('line-through') > -1 ? 'active' : ''">S</div>
                                                <div class="icon-box overline" bl-toggle-text-decoration="overline" ng-class="inspector.styles.text.textDecoration.indexOf('overline') > -1 ? 'active' : ''">O</div>
                                                <select id="el-font-weight" class="form-control" bl-pretty-select="inspector.styles.text.fontWeight" data-width="66">
                                                    <option ng-repeat="weight in textStyles.fontWeights" data-font-weight="{{ weight }}" value="{{ weight }}">{{ weight }}</option>
                                                </select>
                                            </div>
                                            <div id="el-font-style-box" class="clearfix">
                                                <div class="pull-left">
                                                    <input type="text" id="el-font-size" ng-model="inspector.styles.text.fontSize" ng-model-options="{ debounce: 300 }" class="form-control pull-left">
                                                    <div class="pull-right">
                                                        <div class="icon-box" bl-toggle-text-style="textAlign|left"><i class="icon icon-align-left"></i> </div>
                                                        <div class="icon-box" bl-toggle-text-style="textAlign|center"><i class="icon icon-align-center"></i> </div>
                                                        <div class="icon-box" bl-toggle-text-style="textAlign|right"><i class="icon icon-align-right"></i> </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div data-controls="inspector.styles.text.color" class="color-picker-trigger" style="background: {{ inspector.styles.text.color }}" bl-tooltip="textColor"></div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- text style box ends -->
                                <!-- border box starts -->
                                <section id="border-box" ng-show="canEdit('box')" ng-controller="BorderController">
                                    <div id="border-panel" class="inspector-panel accordion-item">
                                        <h4 class="accordion-heading">{{ 'border' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                        <div class="accordion-body">
                                            <div class="panel-box">
                                                <section class="checkboxes clearfix" bl-checkboxes="borderWidth"></section>
                                                <div bl-range-slider="borderWidth" max="20"></div>
                                                <div class="clearfix">
                                                    <div data-controls="inspector.styles.border.color" class="color-picker-trigger" bl-border-color-preview bl-tooltip="borderColor"></div>
                                                    <select id="border-style" ng-model="borderStyle">
                                                        <option value="none">{{ 'none' | translate }}</option>
                                                        <option value="solid">{{ 'solid' | translate }}</option>
                                                        <option value="dashed">{{ 'dashed' | translate }}</option>
                                                        <option value="dotted">{{ 'dotted' | translate }}</option>
                                                        <option value="double">{{ 'double' | translate }}</option>
                                                        <option value="groove">{{ 'groove' | translate }}</option>
                                                        <option value="ridge">{{ 'ridge' | translate }}</option>
                                                        <option value="inset">{{ 'inset' | translate }}</option>
                                                        <option value="outset">{{ 'outset' | translate }}</option>
                                                    </select>
                                                </div>
                                                <div bl-input-boxes="border.width" class="clearfix input-boxes"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="border-radius-panel" class="inspector-panel accordion-item">
                                        <h4 class="accordion-heading">{{ 'borderRoundness' | translate }} <i class="icon icon-down-open-1"></i></h4>
                                        <div class="accordion-body">
                                            <div id="borderRadius-box" class="panel-box clearfix">
                                                <section class="checkboxes clearfix">
                                                    <div class="pretty-checkbox pull-left">
                                                        <input type="checkbox" id="borderRadius.all" ng-click="inspector.toggleStyleDirections('borderRadius', 'all')">
                                                        <label for="borderRadius.all"><span class="ch-all"></span><span class="unch-all"></span></label>
                                                    </div>
                                                    <div class="pull-right">
                                                        <div class="pretty-checkbox">
                                                            <input type="checkbox" id="borderRadius.top" ng-click="inspector.toggleStyleDirections('borderRadius', 'topLeft')" ng-checked="inspector.checkboxes.borderRadius.indexOf('topLeft') !== -1">
                                                            <label for="borderRadius.top"><span class="ch-top border-top-left"></span><span class="unch-top border-top-left"></span></label>
                                                        </div>
                                                        <div class="pretty-checkbox">
                                                            <input type="checkbox" id="borderRadius.bottom" ng-click="inspector.toggleStyleDirections('borderRadius', 'bottomLeft')" ng-checked="inspector.checkboxes.borderRadius.indexOf('bottomLeft') !== -1">
                                                            <label for="borderRadius.bottom"><span class="ch-bottom border-bottom-left"></span><span class="unch-bottom border-bottom-left"></span></label>
                                                        </div>
                                                        <div class="pretty-checkbox">
                                                            <input type="checkbox" id="borderRadius.right" ng-click="inspector.toggleStyleDirections('borderRadius', 'topRight')" ng-checked="inspector.checkboxes.borderRadius.indexOf('topRight') !== -1">
                                                            <label for="borderRadius.right"><span class="ch-right border-top-right"></span><span class="unch-right border-top-right"></span></label>
                                                        </div>
                                                        <div class="pretty-checkbox">
                                                            <input type="checkbox" id="borderRadius.left" ng-click="inspector.toggleStyleDirections('borderRadius', 'bottomRight')" ng-checked="inspector.checkboxes.borderRadius.indexOf('bottomRight') !== -1">
                                                            <label for="borderRadius.left"><span class="ch-left border-bottom-right"></span><span class="unch-left border-bottom-right"></span></label>
                                                        </div>
                                                    </div>
                                                </section>
                                                <div bl-range-slider="borderRadius" max="20"></div>
                                                <div class="clearfix input-boxes radius-boxes">
                                                    <div class="big-box col-sm-6">
                                                        <input ng-model="radiusAll" ng-model-options="{ debounce: 300 }">
                                                    </div>
                                                    <div class="small-boxes col-sm-6">
                                                        <input ng-model="inspector.styles.border.radius.topLeft" ng-model-options="{ debounce: 300 }">
                                                        <input ng-model="inspector.styles.border.radius.topRight" ng-model-options="{ debounce: 300 }">
                                                        <input ng-model="inspector.styles.border.radius.bottomLeft" ng-model-options="{ debounce: 300 }">
                                                        <input ng-model="inspector.styles.border.radius.bottomRight" ng-model-options="{ debounce: 300 }">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- /border box ends -->
                                <div class="arrow-right" id="color-picker-arrow"></div>
                                <div class="arrow-right" id="background-arrow"></div>
                            </section>
                        </aside>
                        <div id="pages" ng-class="{ open: panels.active === 'pages' }" class="panel" data-name="pages" ng-controller="PagesController" bl-pretty-scrollbar>
                            <ul class="list-unstyled">
                                <li ng-repeat="page in project.active.pages track by $index" ng-click="project.changePage(page.name)" ng-class="{active: page.name == project.activePage.name}">
                                    <span class="name">{{ page.name }}</span>
                                </li>
                            </ul>

                            <div class="action-buttons">
                                <button ng-click="createNewPage()" bl-tooltip="newPage"><i class="icon icon-doc-add"></i></button>
                                <div class="pull-right">
                                    <button ng-click="savePage()" bl-tooltip="savePage"><i class="icon icon-floppy-1"></i></button>
                                    <button bl-tooltip="deletePage" ng-disabled="project.activePage.name.toLowerCase() === 'index'" ng-click="deletePage()"><i class="icon icon-trash"></i></button>
                                    <button bl-tooltip="copyPage" ng-click="copyPage()"><i class="icon icon-docs-1"></i></button>
                                </div>
                            </div>

                            <div class="page-settings">
                                <div class="form-group">
                                    <label for="page-name">{{ 'pageName' | translate }}</label>
                                    <input type="text" ng-disabled="project.activePage.name == 'Index'" class="form-control" ng-model="project.activePage.name" id="page-name">
                                </div>

                                <div class="form-group">
                                    <label for="page-title">{{ 'pageTitle' | translate }}</label>
                                    <input type="text" class="form-control" ng-model="project.activePage.title" id="page-title">
                                </div>

                                <div class="form-group">
                                    <label for="page-description">{{ 'pageDescription' | translate }}</label>
                                    <textarea ng-model="project.activePage.description" id="page-description" rows="5" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="page-tags">{{ 'pageTags' | translate }}</label>
                                    <textarea ng-model="project.activePage.tags" id="page-tags" rows="5" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="buttons-bottom">
                                <button class="btn btn-danger" ng-click="emptyProject()">{{ 'emptyProject' | translate }}</button>
                            </div>
                        </div>
                        <div id="settings" ng-class="{ open: panels.active === 'settings' }" class="panel" data-name="settings" ng-controller="SettingsController">
                            <div class="categories" bl-panels-accordion>
                                <div class="accordion-item" ng-repeat="(category, categorySettings) in settings.all" ng-class="{ open: category === 'autoSave' }">
                                    <div class="accordion-heading">{{ category | translate }} <i class="icon icon-down-open-1"></i></div>
                                    <div class="accordion-body">
                                        <div class="settings-container">
                                            <div class="form-group setting" ng-repeat="setting in categorySettings">
                                                <div class="with-toggle" ng-if="isBoolean(setting.value)">
                                                    <div class="label">{{ setting.name.toTitleCase() }}:</div>
                                                    <div class="toggle toggle-light" data-name="{{setting.name}}" bl-settings-toggler="{{setting.value ? 'on' : 'off'}}"></div>
                                                    <p>{{ setting.description }}</p>
                                                </div>
                                                <div class="with-input" ng-if="!isBoolean(setting.value)">
                                                    <div class="label">{{ setting.name.toTitleCase() }}:</div>
                                                    <input type="text" class="form-control" ng-model="setting.value">
                                                    <p>{{ setting.description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <section>
                            <div class="device-switcher" ng-show="devicesPanelOpen">
                                <div class="devices">
                                    <button ng-class="{ active: activeCanvasSize === 'xs' }" ng-click="resizeCanvas('xs')"><i class="icon icon-mobile"></i></button>
                                    <button ng-class="{ active: activeCanvasSize === 'sm' }" ng-click="resizeCanvas('sm')"><i class="icon icon-tablet-1"></i></button>
                                    <button ng-class="{ active: activeCanvasSize === 'md' }" ng-click="resizeCanvas('md')"><i class="icon icon-laptop"></i></button>
                                    <button ng-class="{ active: activeCanvasSize === 'lg' }" ng-click="resizeCanvas('lg')"><i class="icon icon-desktop"></i></button>
                                </div>
                                <div class="current-device">
                                    <i class="icon icon-desktop"></i>
                                    <div class="name">{{ activeCanvasSize | translate }}</div>
                                    <div class="size">{{ activeCanvasSize+'Size' | translate }}</div>
                                </div>
                            </div>
                            <div class="bottom-navigation">
                                <button ng-click="preview()" bl-tooltip="preview" placement="top"><i class="icon icon-eye"></i></button>
                                <button ng-click="openPanel('export')" bl-tooltip="export" placement="top"><i class="icon icon-export"></i></button>
                                <button ng-click="toggleDevicesPanel()" ng-class="{ active: devicesPanelOpen }" bl-tooltip="changeDevice" placement="top"><i class="icon icon-mobile"></i></button>

                                <button bl-tooltip="save" placement="top"  ng-click="project.save()" ng-disabled="savingChanges">
                                    <i class="icon" ng-class="savingChanges ? 'icon-spin6 icon-spin' : 'icon-floppy-1'"></i> 
                                </button>
                            </div>
                        </section>
                    </div>
                </section>
            </aside>
            <div id="middle">
                <div id="context-menu" ng-controller="ContextMenuController" ng-show="contextMenuOpen">
                    <h5 class="clearfix"><span class="name">{{ selected.element ? selected.element.name : '' }}</span><i ng-click="closeContextMenu()" class="icon icon-cancel"></i> </h5>
                    <ul class="list-unstyled">
                        <li ng-if="selected.isTable" ng-click="executeCommand('addRowBefore')"><div class="command-name">{{ 'addRowBefore' | translate }}</div></li>
                        <li ng-if="selected.isTable" ng-click="executeCommand('addRowAfter')"><div class="command-name">{{ 'addRowAfter' | translate }}</div></li>
                        <li ng-if="selected.isTable" ng-click="executeCommand('addColumnBefore')"><div class="command-name">{{ 'addColumnBefore' | translate }}</div></li>
                        <li ng-if="selected.isTable" ng-click="executeCommand('addColumnAfter')"><div class="command-name">{{ 'AddColumnAfter' | translate }}</div></li>
                        <li ng-if="selected.isTable" class="separator"></li>
                        <li ng-click="executeCommand('selectParent')"><div class="command-name"><i class="icon icon-up-outline"></i> {{ 'selectParent' | translate }}</div></li>
                        <li ng-click="executeCommand('wrapInTransparentDiv')"><div class="command-name"><i class="icon icon-bullseye"></i> {{ 'wrapInTransparentDiv' | translate }}</div></li>
                        <li class="separator"></li>
                        <li ng-click="executeCommand('cut')"><div class="command-name"><i class="icon icon-scissors"></i> {{ 'cut' | translate }}</div><div class="command-keybind">Ctrl+X</div></li>
                        <li ng-click="executeCommand('copy')"><div class="command-name"><i class="icon icon-docs"></i> {{ 'copy' | translate }}</div><div class="command-keybind">Ctrl+C</div></li>
                        <li ng-click="executeCommand('paste', $event)" ng-class="!dom.copiedNode ? 'disabled' : ''"><div class="command-name"><i class="icon icon-paste"></i> {{ 'paste' | translate }}</div><div class="command-keybind">Ctrl+V</div></li>
                        <li ng-click="executeCommand('delete')"><div class="command-name"><i class="icon icon-trash"></i> {{ 'delete' | translate }}</div><div class="command-keybind">Del</div></li>
                        <li ng-click="executeCommand('clone')"><div class="command-name"><i class="icon icon-database"></i> {{ 'duplicate' | translate }}</div></li>
                        <li class="separator"></li>
                        <li ng-click="executeCommand('moveSelected', 'up')"><div class="command-name"><i class="icon icon-up-open"></i> {{ 'moveUp' | translate }}</div><div class="command-keybind">&#8593;</div></li>
                        <li ng-click="executeCommand('moveSelected', 'down')"><div class="command-name"><i class="icon icon-down-open"></i> {{ 'moveDown' | translate }}</div><div class="command-keybind">&#8595;</div></li>
                        <li class="separator"></li>
                        <li ng-click="executeCommand('undo')" ng-class="!undoManager.canUndo ? 'disabled' : ''"><div class="command-name"><i class="icon icon-reply"></i> {{ 'undo' | translate }}</div><div class="command-keybind">Ctrl+Z</div></li>
                        <li ng-click="executeCommand('redo')" ng-class="!undoManager.canRedo ? 'disabled' : ''"><div class="command-name"><i class="icon icon-forward"></i> {{ 'redo' | translate }}</div><div class="command-keybind">Ctrl+Y</div></li>
                        <li ng-class="{hidden: codeEditors.currentlyOpen == 'html'}" class="separator"></li>
                        <li ng-class="{hidden: codeEditors.currentlyOpen == 'html'}" ng-click="executeCommand('viewSource')"><div class="command-name">{{ 'viewSource' | translate }}</div></li>
                    </ul>
                </div>
                <div id="frame-wrapper" bl-builder bl-resizable bl-iframe-nodes-sortable bl-iframe-nodes-selectable bl-iframe-text-editable bl-iframe-context-menu>
                    <section id="highlights">
                        <div id="linker" class="hidden" ng-controller="LinkerController">
                            <h3>{{ 'linkFor' | translate }} <span>{{ linker.label }}</span> <i ng-click="hideLinker()" class="fa pull-right fa-times"></i></h3>
                            <ul>
                                <li ng-class="{ disabled: linker.radio !== 'url' }">
                                    <label class="pull-left">
                                        <div class="radio-input"><input value="url" ng-model="linker.radio" type="radio"></div>
                                    </label>
                                    <div class="pull-right">
                                        <div class="title">{{ 'websiteUrl' | translate }}</div>
                                        <div class="body">
                                            <input class="form-control" ng-model="linker.url" ng-change="applyUrl()" type="text" placeholder="http://www.google.com">
                                        </div>
                                    </div>
                                </li>
                                <li ng-class="{ disabled: linker.radio !== 'page' }">
                                    <label class="pull-left">
                                        <div class="radio-input"><input value="page" ng-model="linker.radio" type="radio"></div>
                                    </label>
                                    <div class="pull-right">
                                        <div class="title">{{ 'page' | translate }}</div>
                                        <div class="body">
                                            <select ng-model="linker.page" ng-change="applyPage()" class="form-control">
                                                <option value="">{{ 'selectAPage' | translate }}</option>
                                                <option value="{{page.name}}" ng-repeat="page in project.active.pages">{{ page.name.ucFirst() }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li ng-class="{ disabled: linker.radio !== 'download' }">
                                    <label class="pull-left">
                                        <div class="radio-input"><input value="download" ng-model="linker.radio" type="radio"></div>
                                    </label>
                                    <div class="pull-right">
                                        <div class="title">{{ 'download' | translate }}</div>
                                        <div class="body">
                                            <input class="form-control" ng-model="linker.download" ng-change="applyDownload()" type="text" placeholder="https://www.google.com/images/srpr/logo11w.png">
                                        </div>
                                    </div>
                                </li>
                                <li ng-class="{ disabled: linker.radio !== 'email' }">
                                    <label class="pull-left">
                                        <div class="radio-input"><input value="email" ng-model="linker.radio" type="radio"></div>
                                    </label>
                                    <div class="pull-right">
                                        <div class="title">{{ 'emailAddress' | translate }}</div>
                                        <div class="body">
                                            <input class="form-control" ng-model="linker.email" ng-change="applyEmail()" type="text" placeholder="vebtolabs@gmail.com">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div bl-hover-box></div>
                        <div id="select-box" ng-hide="dragging" bl-columns-resizable>
                            <div id="select-box-actions" bl-context-box-actions ng-hide="rowEditorOpen">
                                <span class="element-tag"></span>
                                <i class="icon icon-pencil" data-action="edit" ng-if="!selected.element.hideEditIcon"></i>
                                <i class="icon" ng-class="selected.locked ? 'icon-lock' : 'icon-lock-open'" bl-toggle-element-lock data-action="lock"></i>
                                <i class="icon icon-trash" data-action="delete"></i>
                            </div>
                            <div id="column-resizers"></div>
                            <div id="resize-handles" ng-show="!selected.isColumn && settings.get('showWidthAndHeightHandles') && !rowEditorOpen">
                                <span data-direction="nw" class="drag-handle nw-handle"></span>
                                <span data-direction="n" class="drag-handle n-handle"></span>
                                <span data-direction="ne" class="drag-handle ne-handle"></span>
                                <span data-direction="e" class="drag-handle e-handle"></span>
                                <span data-direction="se" class="drag-handle se-handle"></span>
                                <span data-direction="s" class="drag-handle s-handle"></span>
                                <span data-direction="sw" class="drag-handle sw-handle"></span>
                                <span data-direction="w" class="drag-handle w-handle"></span>
                            </div>
                        </div>
                        <div id="edit-columns" bl-toggle-row-editor><i class="icon icon-cog-outline"></i> <span>{{ 'editColumns' | translate }}</span></div>
                        <div id="row-editor" bl-row-editor>
                            <div class="column-controls"></div>
                            <div class="row-presets clearfix">
                                <button class="btn btn-sm btn-default equalize-columns pull-left">{{ 'makeColsEqual' | translate }}</button>
                                <div class="pull-right" bl-row-presets></div>
                            </div>
                            <div class="column-controls-footer clearfix">
                                <i class="icon icon-cancel close-row-editor"></i>
                                <button class="btn btn-sm btn-primary pull-right save-and-close-row-editor">{{ 'saveAndClose' | translate }}</button>
                            </div>
                        </div>
                        <div id="text-toolbar" ng-controller="ToolbarController" bl-floating-toolbar>

                            <select id="toolbar-size" bl-pretty-select="font.size" data-width="120" append-to="#text-toolbar">
                                <option value="">Size</option>
                                <option ng-repeat="num in fontSizes" data-font-size="{{ num }}">{{ num }}</option>
                            </select>
                            <select id="toolbar-font" bl-pretty-select="font.family" data-width="150" append-to="#text-toolbar">
                                <option value="">Font</option>
                                <option ng-repeat="font in baseFonts" data-font-family="{{ font.css }}">{{ font.name }}</option>
                            </select>
                            <div id="toolbar-style">
                                <div class="bold">B</div>
                                <div class="italic">I</div>
                                <div class="underline">U</div>
                                <div class="strike">S</div>

                                <div class="wrap-link"><i class="icon icon-link-outline"></i></div>

                                <div class="align-left"><i class="icon icon-align-left"></i> </div>
                                <div class="align-center"><i class="icon icon-align-center"></i> </div>
                                <div class="align-right"><i class="icon icon-align-right"></i> </div>

                                <div class="show-icons-list" bl-toggle-icon-list><i class="icon icon-emo-thumbsup"></i> </div>
                                <section id="icons-list">
                                    <div class="arrow-up"></div>
                                    <input type="text" placeholder="Search for icons" ng-model="iconSearch">
                                    <ul class="list-unstyled list-inline">
                                        <li ng-repeat="icon in icons | filter:iconSearch" class="icon" data-icon-class="{{ icon }}"><i class="{{ icon }}"></i></li>
                                    </ul>
                                </section>
                            </div>
                            <div id="link-details" class="hidden">
                                <input type="text" class="form-control" ng-model="href" placeholder="Url...">
                                <input type="text" class="form-control" ng-model="title" placeholder="Title...">
                                <button type="button" class="btn btn-success" id="wrap-with-link" ng-disabled="href == 'http://'">Go</button>
                            </div>
                        </div>
                    </section>
                    <iframe id="iframe" frameborder="0" class="full-width"></iframe>
                    <div id="frame-overlay" class="hidden"></div>
                    <div id="theme-loading" class="hidden"><span>{{ 'loading' | translate }}...</span></div>
                </div>
            </div>
            <div id="code-editor-wrapper" ng-controller="CodeEditorController" bl-render-editors>
                <div id="editor-header" class="clearfix">
                    <div class="pull-left">
                        <select class="pretty-select" ng-model="editors.theme" ng-options="name for name in themes" ng-change="editors.changeTheme()"></select>
                    </div>
                    <div class="pull-right" id="editor-icons">
                        <button ng-class="{ active: editors.currentlyOpen === 'html' }" class="btn btn-success btn-sm" type="button" bl-show-editor="html">{{ 'html' | translate }}</button>
                        <button ng-class="{ active: editors.currentlyOpen === 'css' }" class="btn btn-success btn-sm" type="button" bl-show-editor="css">{{ 'css' | translate }}</button>
                        <button ng-class="{ active: editors.currentlyOpen === 'js' }" class="btn btn-success btn-sm" type="button" bl-show-editor="js">{{ 'js' | translate }}</button>
                        <button class="btn btn-sm with-icon expand-editor"><i class="icon icon-resize-full-alt"></i> </button>
                        <button class="btn btn-sm with-icon close-editor"><i class="icon icon-cancel"></i> </button>
                    </div>
                </div>
                <div class="code-editor" id="html-code-editor"></div>
                <div class="code-editor hidden" id="css-code-editor"></div>
                <div class="code-editor hidden clearfix" id="js-code-editor">
                    <div class="editor-column" id="jscript-code-editor"></div>

                    <div class="modal fade" id="new-library-modal" bl-new-library-modal>
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close pull-right" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                    <h4 class="modal-titlet">{{ 'createNewLibrary' | translate }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">{{ 'libraryName' | translate }}</label>
                                        <input type="text" class="form-control" name="name" ng-model="libraries.form.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="path">{{ 'libraryPath' | translate }}</label>
                                        <input type="text" class="form-control" name="path" ng-model="libraries.form.path">
                                        <p class="help-block">{{ 'pathToLibrary' | translate }}</p>
                                    </div>
                                    <p class="text-danger">{{ libraries.form.error }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger close-modal">{{ 'close' | translate }}</button>
                                    <button class="btn btn-success float-right save-library">{{ 'saveAndClose' | translate }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="code-editor-resize-overlay"></div>

            <div class="modal fade" id="fonts-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header clearfix">
                            <div title="Close Modal" class="close-modal" data-dismiss="modal">
                                <i class="icon icon-cancel-circled"></i>
                            </div>

                            <h4 class="modal-title pull-left">{{ 'selectOneOf' | translate }} {{ fonts.paginator.sourceItems.length }} {{ 'googleFonts' | translate }}</h4>
                            <div class="pagi-container pull-right"><ul class="pagination" bl-fonts-pagination></ul></div>
                        </div>

                        <div class="modal-body">
                            <ul class="fonts-list">
                                <li ng-repeat="font in fonts.paginator.currentItems" style="font-family: {{ font.family }}" ng-click="fonts.apply(font)">
                                    <div class="font-preview">{{ 'fontsLorem' | translate }}</div>
                                    <div class="font-details clearfix">
                                        <div class="pull-left">{{ font.family+', '+font.category }}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <div id="preview-closer" class="hidden" ng-click="closePreview()">
            <i class="icon icon-cancel"></i>
        </div>

        <div class="modal fade" id="publish-modal" bl-export-to-ftp>
            <div class="modal-dialog">
                <div class="modal-content" ng-class="{demo: isDemo || !userCan('publish')}">
                    <div class="modal-header">
                        <button type="button" class="close pull-right" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ 'close' | translate }}</span></button>
                        <h4 class="modal-title">{{ 'publishToRemoteFtp' | translate }}</h4>
                    </div>
                    <div class="modal-body">

                        <div ng-if="isDemo" class="alert alert-info demo-alert">Publishing to remote ftp is disabled on demo site. Normally you would be able to enter your credentials and export the entire project with a single 'publish' click below.</div>
                        <div ng-if="!userCan('publish') && ! isDemo" class="alert alert-info demo-alert">{{ 'noPermToPublish' | translate }}</div>

                        <div class="form-group">
                            <label for="host">{{ 'host' | translate }}</label>
                            <input type="text" class="form-control" ng-model="publishCredentials.host">
                        </div>
                        <div class="form-group">
                            <label for="username">{{ 'username' | translate }}</label>
                            <input type="text" class="form-control" ng-model="publishCredentials.user">
                        </div>
                        <div class="form-group">
                            <label for="password">{{ 'password' | translate }}</label>
                            <input type="password" class="form-control" ng-model="publishCredentials.password">
                        </div>
                        <div class="form-group">
                            <label for="folder">{{ 'folder' | translate }}</label>
                            <input type="text" class="form-control" ng-model="publishCredentials.root">
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4 pull-left">
                                <label for="port">{{ 'port' | translate }}</label>
                                <input type="text" class="form-control" ng-model="publishCredentials.port">
                            </div>
                            <div class="form-group col-sm-2 pull-right">
                                <div class="checkbox">
                                    <label>
                                        <input ng-model="publishCredentials.ssl" type="checkbox"> {{ 'ssl' | translate }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger error"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger close-modal">{{ 'close' | translate }}</button>
                        <button class="btn btn-success publish" ng-disabled="isDemo">{{ 'publish' | translate }}</button>
                    </div>
                    <div class="loader"><div class="inner"><i class="fa fa-spinner fa-spin"></i><div class="text">{{ 'publishing' | translate }}</div></div></div>
                </div>
            </div>
        </div>

        <script>
            var keys    = {},
                version = 1.0,
                locales = ['en'],
                trans   = {},
                selectedLocale = 'en'
        </script>

        <script src="<?= base_url('assets/plugins/pBuilder') ?>/public/js/builder.min.js?v3"></script>
        <script src="<?= base_url('assets/plugins/pBuilder') ?>/public/js/vendor/ace/ace.js"></script>

    </div>

</body>

<script type="text/javascript">
    $(document).delegate('[bl-tooltip="save"]','click', function(){
        console.log(localStorage.get('architect-project'));
    })
</script>
</html>
