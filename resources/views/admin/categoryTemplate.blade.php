@extends('layouts.app')

@section('content')
    <div class="container" ng-controller="CategoryCtrl" ng-init="category = {{$category}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Category</div>

                    <div class="panel-body">
                        <div class="panel">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input name="name" class="form-control" id="name" placeholder="Name"
                                       ng-model="category.name">
                            </div>
                            <div class="form-group">
                                <label for="parent">Parent</label>
                                <ul id='parent' ng-if="Parents">
                                    <li ng-repeat="(key, category) in Parents" id="parent-<%key%>" class="parent"><%
                                        category.name %>
                                        <ul ng-if="category.subitems"
                                            ng-include="'/angular-template/admin/parent-angular.html'"></ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
