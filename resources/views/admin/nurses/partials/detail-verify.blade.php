<div class="col-xl-12">
<div id="usr-password" class="dashboard-box">
<div class="headline">
    <h3><i class="icon-feather-check-circle"></i> Verify Profile</h3>
</div>
<div class="content with-padding">
    <div class="row">
        <div class="col-xl-12">
        <div class="checkbox">
        <input type="checkbox" name="is_verified" id="is_verified" {{$nurse->is_verified ? 'checked' : '' }}>
        <label for="is_verified"><span class="checkbox-icon"></span> {{ __('Verify Profile') }}</label>
        </div>
        @if ($errors->has('is_verified'))
        <small class="invalid-feedback">{{ $errors->first('is_verified') }}</small>
        @endif
        </div>
    </div>
</div>
</div>
</div>