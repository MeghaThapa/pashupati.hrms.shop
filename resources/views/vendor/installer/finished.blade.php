@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('final_templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('final_title') }}
@endsection

@section('container')

	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ trans('final_migration') }}</small></strong></p>
		<pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
	@endif

	<p><strong><small>{{ trans('final_console') }}</small></strong></p>
	<pre><code>{{ $finalMessages }}</code></pre>

	<p><strong><small>{{ trans('final_log') }}</small></strong></p>
	<pre><code>{{ $finalStatusMessage }}</code></pre>

	<p><strong><small>{{ trans('final_env') }}</small></strong></p>
	<pre><code>{{ $finalEnvFile }}</code></pre>

    <p><strong><small>{{ trans('final_success') }}</small></strong></p>
    <pre>
		<code>
			{{ trans('final_email') }}: {{ trans('superadmin@productify.com') }}
			{{ trans('final_password') }}: {{ trans('productify2022') }}
		</code>
	</pre>

    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('final_exit') }}</a>
    </div>

@endsection
