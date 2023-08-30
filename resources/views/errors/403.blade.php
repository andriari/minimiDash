@extends('layouts.app')

@section('title', 'Restricted Access!')

@section('content')
<div class="row">
	<div class="col">
		<div class="alert alert-danger" role="alert">
			<h4 class="alert-heading">Restricted Access!</h4>
			<p>You do not have permission to access this menu.</p>
		</div>
	</div>
</div>
@endsection