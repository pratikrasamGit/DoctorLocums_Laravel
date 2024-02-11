@extends('layouts.page')

@section('content')
	<div class="bg-body-light">
		<div class="content content-full">
			<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
				<h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">File Not Found</h1>
				<nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/">Dashboard</a></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="content">
		<div class="card">
		<div class="card-body">
			<p class="error-details">
				Sorry, the page you are looking for is either missing or private.
			</p>
			<div class="error-actions">
				<a href="/" class="btn btn-primary btn-lg">
					<i class="fas fa-home"></i> Take Me Home
				</a>
			</div>
		</div>
	</div>
	</div>
@endsection

