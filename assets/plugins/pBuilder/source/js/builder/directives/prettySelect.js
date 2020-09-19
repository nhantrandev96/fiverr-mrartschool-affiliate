angular.module('builder.directives').directive('blPrettySelect', ['$parse', '$rootScope', function($parse, $rootScope) {

    //extend jquery ui widget so we can use different
    //styles for every select option
    $.widget('builder.prettyselect', $.ui.selectmenu, {
        _renderItem: function(ul, item) {
            var li = $('<li>', {text: item.label});

            //grab any styles stored on options and apply them
            $.each(item.element.data(), function(i,v) {
                li.css(i, v);
            });

            return li.appendTo(ul);
        }
    });

    return {
        restrict: 'A',
        link: function($scope, el, attrs) {

            //initiate select plugin on element
            el.prettyselect({
                width: attrs.width ? attrs.width : 100,
                appendTo: attrs.appendTo ? attrs.appendTo : $rootScope.inspectorCont
            });

            //hide select menu on inspector scroll
            $scope.inspectorCont.on('scroll', function() {
                el.prettyselect('close');
            });

            //get object reference to bind select value to
            var model = $parse(attrs.blPrettySelect);

            //assign new value to object on the scope we got above
            el.on('prettyselectchange', function(e, ui) {
                $scope.$apply(function() {
                    model.assign($scope, ui.item.value);
                });
            });

            //set up two way binding between select and model we got above
            $scope.$watch(attrs.blPrettySelect, function(elVal) {
                if ( ! elVal) { return true; };

                for (var i = el.get(0).options.length - 1; i >= 0; i--) {
                    var selVal = el.get(0).options[i].value.removeQoutes();

                    if (selVal == elVal || selVal.match(new RegExp('^.*?'+elVal+'.*?$'))) {
                        return el.val(selVal).prettyselect('refresh');
                    }
                }
            });
        }
    }
}]);