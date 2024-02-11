<div class="row">
<div class="col-xl-6">
    <div class="submit-field">
        <h5>First Name</h5>
        {{Form::text('first_name', $user->first_name, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('first_name'), 
        ['class' => 'with-border', 'maxlength' => 100]))}}
        @if ($errors->has('first_name'))
        <small class="invalid-feedback">{{ $errors->first('first_name') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Last Name</h5>
        {{Form::text('last_name', $user->last_name, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('last_name'), 
        ['class' => 'with-border', 'maxlength' => 100]))}}
        @if ($errors->has('last_name'))
        <small class="invalid-feedback">{{ $errors->first('last_name') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Email</h5>
        {{Form::text('email', $user->email, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('email'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('email'))
        <small class="invalid-feedback">{{ $errors->first('email') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-6">
    <div class="submit-field">
        <h5>Mobile</h5>
        {{Form::number('mobile', $user->mobile, 
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('mobile'), 
        ['class' => 'with-border', 'maxlength' => 15]))}}
        @if ($errors->has('mobile'))
        <small class="invalid-feedback">{{ $errors->first('mobile') }}</small>
        @endif
    </div>
</div>
</div>