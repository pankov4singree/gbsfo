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
app.controller('BooksCtrl', ['$scope', 'book', function ($scope, $book) {

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

    $scope.deleteItem = function (book) {
        $http.delete('/api/books/delete/' + book.id).then(function (response) {
            if (response.data.status == 'success') {
                for (var item in $scope.Books) {
                    if ($scope.Books[item].id == book.id) {
                        $scope.Books.splice(item, 1);
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
            }
        }
    };

}]);