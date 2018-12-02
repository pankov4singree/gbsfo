@extends('layouts.frontend.app')

@section('content')
    <div class="container" id="authors" ng-controller="AuthorsCtrl">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Authors
                        @auth
                            {!! Auth::user()->buildAdminLink('admin.authors', 'Authors') !!}
                        @endauth
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, author) in Authors" id="author-<%author.id%>" class="author">
                                    <div class="info">
                                        <a href="<%author.routes.view%>"><%author.first_name%> <%author.last_name%></a>
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
