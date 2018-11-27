@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="CategoriesCtrl">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Categories<a class="pull-right" href="{{ route('admin.category.create') }}">Create new category</a></div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <uL>
                                <li ng-repeat="(key, category) in Categories" id="category-<%key%>" class="category">
                                    <div class="info" data-category="<%key%>">
                                        <a ng-if="category.routes.edit" href="<%category.routes.edit%>"><%category.name%></a>
                                        <div class="control">
                                            <a href="#" ng-click="deleteItem(category, {});">Удалить</a>
                                        </div>
                                    </div>
                                    <ul ng-if="category.subitems" ng-init="parent_cat = category"
                                        ng-include="'/angular-template/admin/category-angular.html'"></ul>
                                </li>
                            </uL>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
