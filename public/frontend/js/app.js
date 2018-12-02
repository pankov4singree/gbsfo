var app = angular.module('app', ['ngSanitize'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}]);
app.controller('CategoryCtrl', ['$scope', '$http', 'book', function ($scope, $http, $book) {
    $scope.showLoadMore = false;
    $scope.Books = [];
    $scope.currentPage = 1;

    $scope.loadBooks = function () {
        $book.getBooks({'page': $scope.currentPage, 'category_id': $scope.Category.id}).then(function (response) {
            if (response.status == 200) {
                $scope.pushBooks(response.data);
            } else
                alert('Something went wrong');

        }).catch(function (response) {
            alert('Something went wrong');
        });
    };

    $scope.$watch('Category', function () {
        $scope.loadBooks();
    });

    $scope.pushBooks = function (response) {
        $scope.Books = $scope.Books.concat(response.data);
        $scope.showLoadMore = response.current_page < response.last_page;
        $scope.currentPage++;
    };

    $scope.loadMore = function () {
        $scope.loadBooks();
    };
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

}]);
app.controller('AuthorCtrl', ['$scope', '$http', 'book', function ($scope, $http, $book) {
    $scope.showLoadMore = false;
    $scope.Books = [];
    $scope.currentPage = 1;

    $scope.loadBooks = function () {
        $book.getBooks({'page': $scope.currentPage, 'author_id': $scope.Author.id}).then(function (response) {
            if (response.status == 200) {
                $scope.pushBooks(response.data);
            } else
                alert('Something went wrong');

        }).catch(function (response) {
            alert('Something went wrong');
        });
    };

    $scope.$watch('Author', function () {
        $scope.loadBooks();
    });

    $scope.pushBooks = function (response) {
        $scope.Books = $scope.Books.concat(response.data);
        $scope.showLoadMore = response.current_page < response.last_page;
        $scope.currentPage++;
    };

    $scope.loadMore = function () {
        $scope.loadBooks();
    };
}]);

app.controller('BooksCtrl', ['$scope', 'book', '$http', function ($scope, $book, $http) {

    $scope.showLoadMore = false;
    $scope.Books = [];
    $scope.currentPage = 1;

    $scope.loadBooks = function () {
        $book.getBooks({'page': $scope.currentPage}).then(function (response) {
            if (response.status == 200) {
                $scope.pushBooks(response.data);
            } else
                alert('Something went wrong');

        }).catch(function (response) {
            alert('Something went wrong');
        });
    };

    $scope.loadBooks();

    $scope.pushBooks = function (response) {
        $scope.Books = $scope.Books.concat(response.data);
        $scope.showLoadMore = response.current_page < response.last_page;
        $scope.currentPage++;
    };

    $scope.loadMore = function () {
        $scope.loadBooks();
    };

}]);
app.controller('BookCtrl', ['$scope', 'category', 'author', 'book', function ($scope, $category, $author, $book) {}]);
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
                if (Array.isArray(object[key])) {
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
            book.block_save = false;
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