<div class="fixed-top">
	@if (Session::has('success'))
	<div class="toast fade show float-right bg-success mt-3 mr-3" id="success-toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="true">
		<div class="toast-header">
			<strong class="mr-auto">Success</strong>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body">
			{!! Session::get('success') !!}
		</div>
	</div>
	@endif
	@if (Session::has('error'))
	<div class="toast fade show float-right bg-danger mt-3 mr-3" id="error-toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
		<div class="toast-header">
			<strong class="mr-auto">Error</strong>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body">
			{!! Session::get('error') !!}
		</div>
	</div>
	@endif
</div>