@extends('layouts.default')

@section('content')
  <script>
    window.EsmsUiENV = {"environment":"production","baseURL":"","locationType":"hash","EmberENV":{"FEATURES":{}},"APP":{},"ApiHost":"/","Api":"api/v1"};
    window.EmberENV = window.EsmsUiENV.EmberENV;
    window.sem = {{ json_encode($sem) }};
    window.currentDate = '{{$currentDate}}';
    window.menu = {{ json_encode($menu) }};
  </script>
  <script src="assets/vendor.js?v=0.3.3"></script>
  <script src="assets/esms-ui.js?v=0.3.3"></script>
  <script>
    window.EsmsUi = require('esms-ui/app')['default'].create(EsmsUiENV.APP);
  </script>
@stop