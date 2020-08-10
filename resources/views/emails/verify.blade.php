@component('mail::message')

Thank you for registering!

@component('mail::button', ['url' => $url])
	<div style="background: red !important;">
		Verify Email
	</div>
@endcomponent

Regards,<br>
{{ config('app.name') }}

@endcomponent