<div class="row">
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Facebook</h5>
        {{Form::text('facebook', $facility->facebook,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('facebook'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('facebook'))
            <small class="invalid-feedback">{{ $errors->first('facebook') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Twitter</h5>
        {{Form::text('twitter', $facility->twitter,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('twitter'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('twitter'))
            <small class="invalid-feedback">{{ $errors->first('twitter') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Linkedin</h5>
        {{Form::text('linkedin', $facility->linkedin,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('linkedin'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('linkedin'))
            <small class="invalid-feedback">{{ $errors->first('linkedin') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Instagram</h5>
        {{Form::text('instagram', $facility->instagram,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('instagram'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('instagram'))
            <small class="invalid-feedback">{{ $errors->first('instagram') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Pinterest</h5>
        {{Form::text('pinterest', $facility->pinterest,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('pinterest'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('pinterest'))
            <small class="invalid-feedback">{{ $errors->first('pinterest') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>TikTok</h5>
        {{Form::text('tiktok', $facility->tiktok,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('tiktok'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('tiktok'))
            <small class="invalid-feedback">{{ $errors->first('tiktok') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Sanpchat</h5>
        {{Form::text('sanpchat', $facility->sanpchat,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('sanpchat'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('sanpchat'))
            <small class="invalid-feedback">{{ $errors->first('sanpchat') }}</small>
        @endif
    </div>
</div>
<div class="col-xl-12">
    <div class="submit-field">
        <h5>Youtube</h5>
        {{Form::text('youtube', $facility->youtube,
        \App\Providers\AppServiceProvider::fieldAttr($errors->has('youtube'), 
        ['class' => 'with-border', 'maxlength' => 255]))}}
        @if ($errors->has('youtube'))
            <small class="invalid-feedback">{{ $errors->first('youtube') }}</small>
        @endif
    </div>
</div>
</div>