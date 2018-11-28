'use strict';

var app = angular.module('app', ['ngSanitize'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);

app.controller('CategoryCtrl', ['$scope', '$http', 'category', function ($scope, $http, $category) {

    $scope.showParents = false;
    $scope.defaultParent = {
        id: 0,
        name: 'Without category'
    };
    $scope.buttonTitle = 'Create';
    $scope.parentCategory = $scope.defaultParent;

    $scope.$watch('Category', function () {

        var exclude_ids = [];
        if ($scope.Category.id != 0) {
            exclude_ids = [$scope.Category.id];
            $scope.buttonTitle = 'Update';
        }
        $category.getCategories({'parent_id': 0, 'exclude_ids': exclude_ids}).then(function (response) {
            if (response.status == 200)
                $scope.Parents = response.data;
            else
                $scope.Parents = [];
        }).catch(function (response) {
            $scope.Parents = [];
        });
    });

    $scope.setParent = function (category) {
        $scope.parentCategory = category;
        $scope.showParents = false;
    };

    $scope.resetCategory = function () {
        $scope.parentCategory = $scope.defaultParent;
        $scope.showParents = false;
    };

    $scope.save = function () {
        $scope.Category.parent = $scope.parentCategory.id;
        if ($category.validateCategory($scope.Category) && !$category.block_save) {
            $category.block_save = true;
            if ($scope.Category.id != 0) {
                $category.updateCategory($scope.Category).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        alert('Обновлено');
                        $category.block_save = false;
                    } else {
                        $category.showCategoryError(response.data.errors)
                    }
                }).catch(function (response) {
                    $category.showCategoryError(response.data.errors)
                });
            } else {
                $category.createCategory($scope.Category).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        location.href = response.data.url;
                    } else {
                        $category.showCategoryError(response.data.errors);
                    }
                }).catch(function (response) {
                    $category.showCategoryError(response.data.errors)
                });
            }
        }
    }

}]);

app.controller('CategoriesCtrl', ['$scope', '$http', 'category', function ($scope, $http, $category) {

    $category.getCategories({'parent_id': 0, 'exclude_ids': []}).then(function (response) {
        if (response.status == 200)
            $scope.Categories = response.data;
        else
            $scope.Categories = [];
    }).catch(function (response) {
        $scope.Categories = [];
    });

    $scope.deleteItem = function (category, parent_cat) {
        $http.delete('/api/categories/delete/' + category.id).then(function (response) {
            if (response.data.status == 'success') {
                if (Object.keys(category.subitems).length) {
                    if (typeof parent_cat.subitems != typeof undefined) {
                        for (var item in category.subitems) {
                            parent_cat.subitems[item] = category.subitems[item];
                        }
                    } else {
                        for (var item in category.subitems) {
                            $scope.Categories[item] = category.subitems[item];
                        }
                    }
                }
                if (typeof parent_cat.subitems != typeof undefined) {
                    delete parent_cat.subitems[category.id];
                } else {
                    delete $scope.Categories[category.id];
                }
            } else {
                alert('Нельзя удалить категорию');
            }
        }).catch(function (response) {
            alert('Нельзя удалить категорию');
        });
    }
}]);

app.factory('category', function ($http) {
    var category = {
        block_save: false,
        getCategories: function (object) {
            return $http.post('/api/categories', object);
        },
        createCategory: function (object) {
            return $http.post('/api/categories/create', object);
        },
        updateCategory: function (object) {
            return $http.put('/api/categories/update', object);
        },
        validateCategory: function (object) {
            if (object.name == '') {
                alert('Нельзя сохранить категорию. Поле "название" пустое');
                return false;
            }
            return true;
        },
        showCategoryError: function (errors) {
            category.block_save = false;
            var error_text = "Что-то пошло не так. Сохранение не выполнено";
            if(Object.keys(errors).length){
                for(var error_block in errors){
                    if(errors[error_block].length){
                        for(var i = 0; i < errors[error_block].length; i++){
                            error_text += "\n" + errors[error_block][i];
                        }
                    }
                }
            }
            alert(error_text);
        }
    };
    return category;
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