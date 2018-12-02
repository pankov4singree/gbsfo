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