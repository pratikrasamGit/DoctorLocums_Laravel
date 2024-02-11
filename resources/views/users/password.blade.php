<div class="col-xl-12">
<div id="usr-password" class="dashboard-box">
    <div class="headline">
        <h3><i class="icon-material-outline-lock"></i> Password & Security</h3>
    </div>
    <div class="content with-padding">
        <div class="row">
            <div class="col-xl-4">
                <div class="submit-field">
                    <h5>New Password</h5>
                    {{Form::password('password',
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('password'), 
                                ['class' => 'with-border', 'maxlength' => 15, 'autocomplete' => 'new-password']))}}
                    @if ($errors->has('password'))
                    <small class="invalid-feedback">{{ $errors->first('password') }}</small>
                    @endif
                    <small class="notice">Note : Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="submit-field">
                    <h5>Repeat New Password</h5>
                    {{Form::password('password_confirmation', ['class' => 'with-border form-control'])}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>