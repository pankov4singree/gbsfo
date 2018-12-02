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