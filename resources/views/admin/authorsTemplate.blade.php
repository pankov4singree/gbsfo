@extends('layouts.app')

@section('content')
    <div class="container" id="categories" ng-controller="AuthorsCtrl">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Authors<a class="pull-right"
                                                            href="{{ route('admin.author.create') }}">Create new
                            author</a></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, author) in Authors" id="author-<%key%>" class="author">
                                    <div class="info">
                                        <a href="<%author.routes.edit%>"><%author.first_name%> <%author.last_name%></a>
                                        <div class="control">
                                            <a ng-show="author.routes.delete" href="#"
                                               ng-click="deleteItem(author);">Удалить</a>
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
