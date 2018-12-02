@extends('layouts.frontend.app')

@section('content')
    <div class="container" id="book" ng-controller="BookCtrl" ng-init="Book = {{$book}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><%Book.name%>
                        @auth
                            {!! Auth::user()->buildAdminLink('admin.books', 'Books') !!}
                        @endauth
                    </div>

                    <div class="panel-body">
                        <div ng-if="Book.authors" class="authors">
                            <h2>Authors</h2>
                            <ul>
                                <li ng-repeat="author in Book.authors">
                                    <a href="<%author.routes.view%>"><%author.first_name%> <%author.last_name%></a>
                                </li>
                            </ul>
                        </div>
                        <div ng-if="Book.categories" class="categories">
                            <h2>Categories</h2>
                            <ul>
                                <li ng-repeat="category in Book.categories">
                                    <a href="<%category.routes.view%>"><%category.name%></a>
                                </li>
                            </ul>
                        </div>
                        <div ng-if="Book.photo" class="image">
                            <img class="img-thumbnail" ng-src="<%Book.photo%>" alt="<%Book.name%>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
