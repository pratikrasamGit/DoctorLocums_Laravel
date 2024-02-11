@extends('layouts.admin')
@section('title', 'Nurseify - Edit Job')
@section('content')
    <div class="dashboard-headline">
        <h3>Edit Template</h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="/">Dashboard</a></li>
                <li>Edit Template</li>
            </ul>
        </nav>
    </div>

    <div class="card">
        {!! Form::open(['action' => ['EmailTemplateController@update', $et->id], 'method' => 'POST']) !!}
        {{ csrf_field() }}
        <div class="card-body dashboard-box">
            <h4 class="card-title">
                <div class="headline">
                    <h3><i class="icon-feather-folder-plus"></i> {{ 'Update ' . $et->label . ' content' }}</h3>
                </div>
            </h4>
            <div class="content with-padding padding-bottom-10">
                <div class="form-group">
                    <label for="">Subject</label>
                    {!! Form::text($name = 'label', $value = $et->label, ['class' => 'form-control', 'id' => '', 'placeholder' => 'Enter Subject']) !!}
                </div>

                <div class="col-xl-12">
                    <div class="submit-field">
                        <h5>Content</h5>
                        {{ Form::textarea('content', $et->content, \App\Providers\AppServiceProvider::fieldAttr($errors->has('content'), ['id' => 'editor', 'class' => 'with-border', 'cols' => '30', 'rows' => '5'])) }}
                    </div>
                </div>
            </div>
        </div>
        {{ Form::hidden('update_id', $et->id) }}
        {{ Form::hidden('_method', 'PUT') }}
        <div class="col-xl-12">
            {{ Form::button('<i class="icon-feather-plus"></i> Update Template', ['type' => 'submit', 'class' => 'button ripple-effect big margin-top-30']) }}
        </div>
        {!! Form::close() !!}
    </div>

@endsection

@section('footer_js')
    <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
