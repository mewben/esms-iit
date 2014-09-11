<html>
	<head></head>
	<body>
		@if ($data)
		<table>
			<tbody>
				@foreach($data['data'] as $v)
				<tr>
					<td>{{$v->i}}.</td>
					<td>{{$v->paydate}}</td>
					<td>{{$v->refno}}</td>
					<td>{{$v->payee}} - {{$v->studid}}</td>
					<td>{{$v->acctcode}}</td>
					<td>{{$v->acctname}}</td>
					<td>{{$v->amount}}</td>
					<td>=SUM(G{{$v->s1}}:G{{$v->s2}})</td>
				</tr>
					@if (isset($v->adtl))
					@foreach($v->adtl as $a)
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>{{$a->acctcode}}</td>
						<td>{{$a->acctname}}</td>
						<td>{{$a->amount}}</td>
					</tr>
					@endforeach
					@endif
				@endforeach
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6"><strong>TOTAL:</strong></td>
					<td>=SUM(G1:G{{$data['meta']['total']}})</td>
					<td>=SUM(H1:H{{$data['meta']['total']}})</td>
				</tr>
			</tbody>
		</table>
		@endif
	</body>
</html>