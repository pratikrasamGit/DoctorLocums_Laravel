<div class="col-xl-12">
<div id="usr-password" class="dashboard-box">
<div class="headline">
    <h3><i class="icon-feather-check-circle"></i> NURSE LIABILITY INSURANCE</h3>
</div>
<div class="content with-padding">
    <div class="row">
        <div class="col-xl-12">
        <div class="checkbox">
        <input type="checkbox" name="is_verified_nli" id="is_verified_nli" {{$nurse->is_verified_nli ? 'checked' : '' }}>
        <label for="is_verified_nli"><span class="checkbox-icon"></span> {{ __('NURSE LIABILITY INSURANCE') }}</label>
        </div>
        @if ($errors->has('is_verified_nli'))
        <small class="invalid-feedback">{{ $errors->first('is_verified_nli') }}</small>
        @endif
        </div>
    </div>
</div>
</div>
</div>