@extends('layouts.backend.app')

@section('content')
    <div class="container" id="category" ng-controller="CategoryCtrl" ng-init="Category = {{$category}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Category <%Category.name%></div>

                    <div class="panel-body">
                        <div class="form-group" id="category-name">
                            <label for="name">Name</label>
                            <input name="name" class="form-control" id="name" placeholder="Name"
                                   ng-model="Category.name">
                        </div>
                        <div class="form-group" id="parent-field">
                            <label for="parent">Parent</label>
                            <span ng-click="showParents = !showParents" class="form-control"
                                  ng-bind-html="parentCategory.name"></span>
                            <ul class="form-control" ng-show="showParents" id='parent' ng-if="Parents">
                                <li id="parent-0" class="parent">
                                    <a href="#" ng-click="resetCategory();">Without category</a>
                                    <ul>
                                        <li ng-if="category.id != Category.id"
                                            ng-repeat="(key, category) in Parents"
                                            ng-init="Category.parent == category.id ? setParent(category) : {}"
                                            id="parent-<%key%>" class="parent">
                                            <a href="#" ng-click="setParent(category);"><% category.name %></a>
                                            <ul ng-if="category.subitems"
                                                ng-include="'/angular-template/admin/parent-angular.html'"></ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button class="pull-right btn btn-primary" ng-click="save();"><% buttonTitle %></button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
