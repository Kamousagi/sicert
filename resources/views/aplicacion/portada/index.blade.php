@extends('layout.aplicacion')


@section('content')

<div class="row alerts-container ng-scope" data-ng-controller="AlertsCtrl" data-ng-show="alerts.length"><div class="col-xs-12"><!-- ngRepeat: alert in alerts --><div class="alert ng-isolate-scope alert-success alert-dismissable" ng-class="['alert-' + (type || 'warning'), closeable ? 'alert-dismissable' : null]" role="alert" data-ng-repeat="alert in alerts" type="success" close="closeAlert($index)">
        <button ng-show="closeable" type="button" class="close" ng-click="close()">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        <div ng-transclude=""><span class="ng-binding ng-scope">Thanks for visiting! Feel free to create pull requests to improve the dashboard!</span></div>
    </div><!-- end ngRepeat: alert in alerts --><div class="alert ng-isolate-scope alert-danger alert-dismissable" ng-class="['alert-' + (type || 'warning'), closeable ? 'alert-dismissable' : null]" role="alert" data-ng-repeat="alert in alerts" type="danger" close="closeAlert($index)">
        <button ng-show="closeable" type="button" class="close" ng-click="close()">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        <div ng-transclude=""><span class="ng-binding ng-scope">Found a bug? Create an issue with as many details as you can.</span></div>
    </div><!-- end ngRepeat: alert in alerts --></div></div>

@endsection