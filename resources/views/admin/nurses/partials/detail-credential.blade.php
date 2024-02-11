<!-- Dashboard Box -->
<div class="col-xl-12">
    <div class="dashboard-box margin-top-30">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-line-awesome-files-o"></i> National Certifications & Credentials</h3>
        </div>
        <div class="content with-padding padding-bottom-0">
            <div class="row">
                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>Search For Credential</h5>
                        {{Form::select('type', $credentials, $certification->type, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('type'), 
                                ['id'=>'type','class' => 'selectform','placeholder' => 'Select Credential']))}}
                        @if ($errors->has('type'))
                        <small class="invalid-feedback">{{ $errors->first('type') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Effective Date</h5>
                        {{Form::date('effective_date', $certification->effective_date, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('effective_date'),
                                ['class' => 'with-border']
                                ))}}
                        @if ($errors->has('effective_date'))
                        <small class="invalid-feedback">{{ $errors->first('effective_date') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="submit-field">
                        <h5>Expiration Date</h5>
                        {{Form::date('expiration_date', $certification->expiration_date, 
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('expiration_date'),
                                ['class' => 'with-border']
                                ))}}
                        @if ($errors->has('expiration_date'))
                        <small class="invalid-feedback">{{ $errors->first('expiration_date') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>Upload</h5>
                        {{Form::file('certificate_image',
                                \App\Providers\AppServiceProvider::fieldAttr($errors->has('certificate_image'),
                                ['class' => 'file-upload']
                                ))}}
                        @if ($errors->has('certificate_image'))
                        <small class="invalid-feedback">{{ $errors->first('certificate_image') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>