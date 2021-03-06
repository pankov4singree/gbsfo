@extends('layouts.backend.app')

@section('content')
    <div class="container" id="books" ng-controller="BooksCtrl">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Books<a class="pull-right" href="{{ route('admin.books.create') }}">Create new book</a></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, book) in Books" id="book-<%book.id%>" class="book">
                                    <div class="info">
                                        <a href="<%book.routes.edit%>"><%book.name%></a>
                                        <div class="control">
                                            <a href="#" ng-click="deleteItem(book);">Удалить</a>
                                        </div>
                                    </div>
                                </li>
                            </uL>
                        </div>
                        <button ng-show="showLoadMore" ng-click="loadMore()" class="btn center-block btn-primary">Load more</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
