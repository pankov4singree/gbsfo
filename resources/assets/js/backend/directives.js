app.directive('fileModel', function () {

    var default_file = {
        name: "Select Photo"
    };

    return {
        restrict: 'A',
        link: function ($scope, $element, $attrs) {

            $scope.file = default_file;

            $element.bind('change', function () {
                $scope.$apply(function () {
                    if ($element[0].files.length) {
                        $scope.file = $element[0].files[0];
                    } else {
                        $scope.file = default_file
                    }
                });
            });
        }
    };
});
app.directive('a', function () {
    return {
        restrict: 'E',
        link: function (scope, elem, attrs) {
            if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                elem.on('click', function (e) {
                    e.preventDefault();
                });
            }
        }
    };
});