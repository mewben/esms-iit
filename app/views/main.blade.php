@extends('layouts.default')

@section('content')
  <script>
    window.EsmsUiENV = {"environment":"production","baseURL":"","locationType":"hash","EmberENV":{"FEATURES":{}},"APP":{},"ApiHost":"/","Api":"api/v1"};
    window.EmberENV = window.EsmsUiENV.EmberENV;
    window.sem = {{ json_encode($sem) }};
    window.currentDate = '{{$currentDate}}';
  </script>
  <script src="assets/vendor.js"></script>
  <script src="assets/esms-ui.js"></script>
  <script>
    window.EsmsUi = require('esms-ui/app')['default'].create(EsmsUiENV.APP);
  </script>
@stop