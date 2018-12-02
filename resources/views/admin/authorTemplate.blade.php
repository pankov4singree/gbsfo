@extends('layouts.backend.app')

@section('content')
    <div class="container" id="author" ng-controller="AuthorCtrl" ng-init="Author = {{$author}}">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><%Author.first_name%> <%Author.last_name%></div>

                    <div class="panel-body">
                        <div class="form-group" id="first-name">
                            <label for="first-name">First Name</label>
                            <input name="first-name" class="form-control" id="first-name" placeholder="First Name"
                                   ng-model="Author.first_name">
                        </div>
                        <div class="form-group" id="last-name">
                            <label for="last-name">Name</label>
                            <input name="last-name" class="form-control" id="last-name" placeholder="Last Name"
                                   ng-model="Author.last_name">
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
