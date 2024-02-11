            <!-- Dashboard Box -->
<div class="col-xl-12">
    
    <div class="dashboard-box margin-top-0">

        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-material-outline-account-circle"></i> Keywords Information</h3>
        </div>

        <div class="content with-padding padding-bottom-0">        
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="submit-field">
                            {{Form::select('filter', $keywordFilters,  isset($key) ? $key->filter : '', 
                            \App\Providers\AppServiceProvider::fieldAttr($errors->has('filter'), 
                            ['class' => 'selectform with-border']))}}
                            @if ($errors->has('filter'))
                            <small class="invalid-feedback">{{ $errors->first('filter') }}</small>
                            @endif
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="submit-field">
                                <h5>Title</h5>
                                <input type="text" class="with-border" name="title" value="{{isset($key) ? $key->title : ''}}">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="submit-field">
                                <h5>Description</h5>
                                <textarea cols="30" rows="3" name="description" class="with-border">{{isset($key)? $key->description : ''}}</textarea>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="submit-field">
                                <h5>Date & Time</h5>
                                <input type="text" class="with-border" name="dateTime" value="{{isset($key) ? $key->dateTime : ''}}">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Amount</h5>
                                <input type="text" class="with-border" name="amount" value="{{isset($key) ? $key->amount : ''}}">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="submit-field">
                                <h5>Counter</h5>
                                <input type="text" class="with-border" name="count" value="{{isset($key) ? $key->count : ''}}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>