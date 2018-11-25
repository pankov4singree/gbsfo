'use strict';

var app = angular.module('app', ['ngSanitize'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);

app.controller('CategoriesCtrl', function ($scope, $http) {

    $scope.Categories = [];

    $http.post('/api/categories', {'parent_id': 0, 'exclude_id': []}).then(function (response) {
        if (response.status == 200)
            $scope.Categories = response.data;
        else
            $scope.Categories = [];
    }).catch(function (response) {
        $scope.Categories = [];
    });

    $scope.deleteItem = function (category, parent_cat) {
        $http.delete('/api/categories/delete/' + category.id).then(function (response) {
            if (Object.keys(category.subitems).length) {
                if (typeof parent_cat.subitems != typeof undefined) {
                    for(var item in category.subitems){
                        parent_cat.subitems[item] = category.subitems[item];
                    }
                    delete parent_cat.subitems[category.id];
                } else {
                    for(var item in category.subitems){
                        $scope.Categories[item] = category.subitems[item];
                    }
                    delete $scope.Categories[category.id];
                }
            }
        }).catch(function (response) {
            alert('Нельзя удалить категорию');
        });
    }
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