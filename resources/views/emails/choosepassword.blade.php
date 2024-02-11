@component('mail::message')

<p>
@lang('You are receiving this email because we created your account on Nurseify to choose new password.')
</p>
<p>
@component('mail::button', ['url' => $chooseLink])
	@lang('Choose Password')
@endcomponent
</p>
<p>@lang('This link is valid for :time hours.', ['time' => $valid_for])</p>
<p>@lang('If you did not request, no further action is required.')</p>

@endcomponent
