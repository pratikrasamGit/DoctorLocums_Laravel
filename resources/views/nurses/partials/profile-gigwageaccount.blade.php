@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Setup Direct Deposit Account',
'activetab' => 'gigWage'
]
)
@section('inner-content')
    @if (!$nurse->is_gig_invite)
        {!! Form::open(['action' => ['ProfileController@createGigwageAccountPost', $nurse->id], 'method' => 'POST']) !!}
        <div class="row">
            <div class="col-xl-12">
                <div class="dashboard-box margin-top-0">
                    <!-- Headline -->
                    <div class="headline">
                        <h3><i class="icon-material-outline-account-circle"></i> Direct Deposit Account</h3>
                    </div>
                    <div class="content with-padding padding-bottom-0">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>First Name</h5>
                                    {{                                     Form::text('first_name', $nurse->user->first_name, \App\Providers\AppServiceProvider::fieldAttr($errors->has('first_name'), ['class' => 'with-border', 'disabled' => 'disabled'])) }}
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Last Name</h5>
                                    {{                                     Form::text('last_name', $nurse->user->last_name, \App\Providers\AppServiceProvider::fieldAttr($errors->has('last_name'), ['class' => 'with-border', 'disabled' => 'disabled'])) }}
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="submit-field">
                                    <h5>Email</h5>
                                    {{                                     Form::text('email', $nurse->user->email, \App\Providers\AppServiceProvider::fieldAttr($errors->has('email'), ['class' => 'with-border', 'disabled' => 'disabled'])) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                {{ Form::button('Send Invite', ['type' => 'submit', 'class' => 'button ripple-effect big margin-top-30']) }}
            </div>
        </div>
        {!! Form::close() !!}
    @else
        <div class="row">
            <div class="col-xl-12">
                <div class="dashboard-box margin-top-0">
                    <!-- Headline -->
                    <div class="headline">
                        <h3><i class="icon-material-outline-account-circle"></i> Direct Deposit Account</h3>
                    </div>
                    <div class="content with-padding">
                        <div class="notification warning closeable">
                            <p>Notice: The invitation to set up your direct deposit has already been sent to our payroll provider.  Please check your email for additional guidance (Note: Please also check your SPAM folder for emails from GigWage).</p>
                        </div>
                        <p>Notice: If you need us to resend the invitation to GigWage please <a
                                onclick="return confirm('Do you really want to Resent?');"
                                href="{{ route('invite-gigwage-account', [$nurse->id, $nurse->gig_account_id]) }}">click
                                here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
