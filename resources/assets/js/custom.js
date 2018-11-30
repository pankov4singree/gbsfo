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
                        alert('Updated');
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
                alert('You can\'t delete category');
            }
        }).catch(function (response) {
            alert('You can\'t delete category');
        });
    }
}]);

app.controller('AuthorsCtrl', ['$scope', '$http', 'author', function ($scope, $http, $author) {

    $scope.showLoadMore = false;
    $scope.Authors = [];
    $scope.currentPage = 1;

    $scope.loadAuthors = function () {
        $author.getAuthors({'page': $scope.currentPage}).then(function (response) {
            if (response.status == 200) {
                $scope.pushAuthors(response.data);
            } else
                alert('Something went wrong');

        }).catch(function (response) {
            alert('Something went wrong');
        });
    };

    $scope.loadAuthors();

    $scope.pushAuthors = function (response) {
        $scope.Authors = $scope.Authors.concat(response.data);
        $scope.showLoadMore = response.current_page < response.last_page;
        $scope.currentPage++;
    };

    $scope.loadMore = function () {
        $scope.loadAuthors();
    };

    $scope.deleteItem = function (author) {
        $http.delete('/api/authors/delete/' + author.id).then(function (response) {
            if (response.data.status == 'success') {
                for (var item in $scope.Authors) {
                    if ($scope.Authors[item].id == author.id) {
                        $scope.Authors.splice(item, 1);
                    }
                }
            } else {
                alert('You can\'t delete author');
            }
        }).catch(function (response) {
            alert('You can\'t delete author');
        });
    }

}]);

app.controller('AuthorCtrl', ['$scope', '$http', 'author', function ($scope, $http, $author) {
    $scope.buttonTitle = 'Create';

    $scope.$watch('Author', function () {
        if ($scope.Author.id != 0) {
            $scope.buttonTitle = 'Update';
        }
    });

    $scope.save = function () {
        if ($author.validateAuthor($scope.Author) && !$author.block_save) {
            $author.block_save = true;
            if ($scope.Author.id != 0) {
                $author.updateAuthor($scope.Author).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        alert('Updated');
                        $author.block_save = false;
                    } else {
                        $author.showAuthorError(response.data.errors)
                    }
                }).catch(function (response) {
                    $author.showAuthorError(response.data.errors)
                });
            } else {
                $author.createAuthor($scope.Author).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        location.href = response.data.url;
                    } else {
                        $author.showAuthorError(response.data.errors);
                    }
                }).catch(function (response) {
                    $author.showAuthorError(response.data.errors)
                });
            }
        }
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
                alert('Field "name" is required');
                return false;
            }
            return true;
        },
        showCategoryError: function (errors) {
            category.block_save = false;
            var error_text = "Something went wrong. Save failed";
            if (Object.keys(errors).length) {
                for (var error_block in errors) {
                    if (errors[error_block].length) {
                        for (var i = 0; i < errors[error_block].length; i++) {
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

app.factory('author', function ($http) {
    var author = {
        block_save: false,
        getAuthors: function (object) {
            return $http.post('/api/authors', object);
        },
        createAuthor: function (object) {
            return $http.post('/api/authors/create', object);
        },
        updateAuthor: function (object) {
            return $http.put('/api/authors/update', object);
        },
        validateAuthor: function (object) {
            if (object.first_name == '') {
                alert('Field "First Name" is required');
                return false;
            }
            if (object.last_name == '') {
                alert('Field "Last Name" is required');
                return false;
            }
            return true;
        },
        showAuthorError: function (errors) {
            category.block_save = false;
            var error_text = "Something went wrong. Save failed";
            if (Object.keys(errors).length) {
                for (var error_block in errors) {
                    if (errors[error_block].length) {
                        for (var i = 0; i < errors[error_block].length; i++) {
                            error_text += "\n" + errors[error_block][i];
                        }
                    }
                }
            }
            alert(error_text);
        }
    };
    return author;
});

app.factory('book', function ($http) {
    var book = {
        block_save: false,
        getBooks: function (object) {
            return $http.post('/api/books', object);
        },
        createBook: function (object) {
            var form = new FormData();
            for (var key in object) {
                if (object[key] instanceof Array) {
                    form.append(key, JSON.stringify(object[key]));
                } else {
                    form.append(key, object[key]);
                }
            }
            return $http.post('/api/books/create', form, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            });
        },
        updateBook: function (object) {
            var form = new FormData();
            for (var key in object) {
                if (object[key] instanceof Array) {
                    form.append(key, JSON.stringify(object[key]));
                } else {
                    form.append(key, object[key]);
                }
            }
            return $http.post('/api/books/update', form, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            });
        },
        validateBook: function (object) {
            if (object.name == '') {
                alert('Field "Name" is required');
                return false;
            }
            return true;
        },
        showBookError: function (errors) {
            category.block_save = false;
            var error_text = "Something went wrong. Save failed";
            if (Object.keys(errors).length) {
                for (var error_block in errors) {
                    if (errors[error_block].length) {
                        for (var i = 0; i < errors[error_block].length; i++) {
                            error_text += "\n" + errors[error_block][i];
                        }
                    }
                }
            }
            alert(error_text);
        }
    };
    return book;
});

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

app.controller('BookCtrl', ['$scope', 'category', 'author', 'book', function ($scope, $category, $author, $book) {

    $scope.buttonTitle = 'Create';
    $scope.Authors = [];
    $scope.Categories = [];

    $category.getCategories({'parent_id': 0, 'exclude_ids': []}).then(function (response) {
        if (response.status == 200)
            $scope.Categories = response.data;
        else
            alert('Something went wrong with load categories');
    }).catch(function (response) {
        alert('Something went wrong with load categories');
    });

    $author.getAuthors({'page': 0}).then(function (response) {
        if (response.status == 200) {
            $scope.Authors = response.data;
        } else
            alert('Something went wrong with load authors');
    }).catch(function (response) {
        alert('Something went wrong with load authors');
    });

    $scope.toggleSelection = function (id, field) {
        var idx = $scope.Book[field].indexOf(id);
        if (idx > -1) {
            $scope.Book[field].splice(idx, 1);
        } else {
            $scope.Book[field].push(id);
        }
    };

    $scope.$watch('Book', function () {
        if ($scope.Book.id != 0) {
            $scope.buttonTitle = 'Update';
        }

    });

    $scope.save = function () {
        $scope.Book.photo = $scope.file;
        if ($book.validateBook($scope.Book) && !$book.block_save) {
            if ($scope.Book.id != 0) {
                $book.updateBook($scope.Book).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        alert('Updated');
                        $book.block_save = false;
                    } else {
                        $book.showBookError(response.data.errors)
                    }
                }).catch(function (response) {
                    $book.showBookError(response.data.errors)
                });
            } else {
                $book.createBook($scope.Book).then(function (response) {
                    if (response.status == 200 && response.data.save == true) {
                        location.href = response.data.url
                    } else {
                        $book.showBookError(response.data.errors)
                    }
                }).catch(function (response) {
                    $book.showBookError(response.data.errors)
                });
                ;
            }
        }
    };

}]);

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