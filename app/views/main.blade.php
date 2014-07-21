@extends('layouts.default')

@section('content')
  <script>
    window.EsmsUiENV = {"environment":"production","baseURL":"","locationType":"hash","EmberENV":{"FEATURES":{}},"APP":{},"ApiHost":"/","Api":"api/v1"};
    window.EmberENV = window.EsmsUiENV.EmberENV;
    window.sem = {{ json_encode($sem) }};
    window.currentDate = '{{$currentDate}}';
  </script>
  <script src="assets/vendor-49b0784051ff2fcef9a3b5dfb8636c7a.js"></script>
  <script src="assets/esms-ui-3868d20a59d345e569c307f7d48c2e14.js"></script>
  <script>
    window.EsmsUi = require('esms-ui/app')['default'].create(EsmsUiENV.APP);
  </script>
@stop