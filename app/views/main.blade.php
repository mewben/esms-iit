@extends('layouts.default')

@section('content')
  <script>
    window.EsmsUiENV = {"environment":"production","baseURL":"","locationType":"hash","EmberENV":{"FEATURES":{}},"APP":{},"ApiHost":"/","Api":"api/v1"};
    window.EmberENV = window.EsmsUiENV.EmberENV;
    window.sem = {{ json_encode($sem) }};
    window.currentDate = '{{$currentDate}}';
  </script>
  <script src="assets/vendor-49b0784051ff2fcef9a3b5dfb8636c7a.js"></script>
  <script src="assets/esms-ui-99caf80d60f4aa8ed4f5af059fd3b60c.js"></script>
  <script>
    window.EsmsUi = require('esms-ui/app')['default'].create(EsmsUiENV.APP);
  </script>
@stop