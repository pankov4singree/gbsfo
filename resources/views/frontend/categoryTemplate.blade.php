@extends('layouts.frontend.app')

@section('content')
    <div class="container" id="category" ng-controller="CategoryCtrl" ng-init="Category = {{$category}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><%Category.name%>
                        @auth
                            {!! Auth::user()->buildAdminLink('admin.categories', 'Categories') !!}
                        @endauth
                    </div>

                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, book) in Books" id="book-<%book.id%>" class="book">
                                    <div class="info">
                                        <a href="<%book.routes.view%>"><%book.name%></a>
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
