@extends('layouts.frontend.app')

@section('content')
    <div class="container" id="categories" ng-controller="CategoriesCtrl">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Categories
                        @auth
                            {!! Auth::user()->buildAdminLink('admin.categories', 'Categories') !!}
                        @endauth
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, category) in Categories" id="category-<%key%>" class="category">
                                    <div class="info" data-category="<%key%>">
                                        <a href="<%category.routes.view%>"><%category.name%></a>
                                    </div>
                                    <ul ng-if="category.subitems" ng-init="parent_cat = category"
                                        ng-include="'/angular-template/frontend/category-angular.html'"></ul>
                                </li>
                            </uL>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
