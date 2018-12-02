app.directive('fileUploader', function () {

    var default_file = {
        name: "Select Image"
    };

    return {
        restrict: 'E',
        templateUrl: '/angular-template/admin/file-upload.html',
        link: function ($scope, $element, $attrs) {
            $scope.block_id = parseInt(Math.random() * 100);
            if (typeof $attrs.id !== typeof undefined) {
                $scope.block_id += '-' + $attrs.id;
            }
            if (typeof $attrs.model !== typeof undefined) {
                var model = $attrs.model;
            } else {
                alert('attribute "model" is required for file-uploader element');
            }

            if (typeof $attrs.field !== typeof undefined) {
                var field = $attrs.field;
            } else {
                alert('attribute "field" is required for file-uploader element');
            }

            $scope.file = default_file;
            $scope.image_url = $scope[model][field];

            $element.find('input').bind('change', function () {
                var file_input = this;
                $scope.$apply(function () {
                    if (file_input.files.length) {
                        $scope.file = file_input.files[0];
                        $scope.image_url = URL.createObjectURL($scope.file);
                    } else {
                        $scope.file = default_file;
                        $scope.image_url = $scope[model][field];
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