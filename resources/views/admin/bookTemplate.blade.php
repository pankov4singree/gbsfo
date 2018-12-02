@extends('layouts.backend.app')

@section('content')
    <div class="container" id="book" ng-controller="BookCtrl" ng-init="Book = {{$book}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><%Book.name%></div>

                    <div class="panel-body">
                        <div class="form-group" id="book-name">
                            <label for="name">Name</label>
                            <input name="name" class="form-control" id="book-name" placeholder="Name"
                                   ng-model="Book.name">
                        </div>
                        <div class="form-group">
                            <file-uploader model="Book" field="photo" id="custom-uploader"></file-uploader>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Categories</h2>
                                <ul ng-if="Authors" id="categories">
                                    <li ng-repeat="(key, category) in Categories">
                                        <label><input type="checkbox" name="category[]"
                                                      ng-click="toggleSelection(category.id, 'category_ids')"
                                                      value="<%category.id%>"
                                                      ng-checked="Book.category_ids.indexOf(category.id) > -1">
                                            <%category.name%></label>
                                        <ul ng-if="category.subitems"
                                            ng-include="'/angular-template/admin/book-categories.html'"></ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h2>Authors</h2>
                                <ul ng-if="Authors" id="authors">
                                    <li ng-repeat="(key, author) in Authors">
                                        <label><input type="checkbox" name="author[]"
                                                      ng-click="toggleSelection(author.id, 'author_ids')"
                                                      value="<%author.id%>"
                                                      ng-checked="Book.author_ids.indexOf(author.id) > -1">
                                            <%author.first_name%> <%author.last_name%></label>
                                    </li>
                                </ul>
                            </div>
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
