@if(isset($errors) && count($errors) > 0)
@foreach($errors->all() as $error)
    <div class="notification error closeable">
    <p>{{$error}}</p>
    <a class="close" href="#"></a>
    </div>
    @endforeach
@endif

@if(session('success'))
<div class="notification success closeable">
    <p>{{session('success')}}</p>
    <a class="close" href="#"></a>
</div>
@endif

@if(session('error'))
<div class="notification error closeable">
    <p>{{session('error')}}</p>
    <a class="close" href="#"></a>
</div>
@endif

@if(session('captcha'))
<div class="notification error closeable">
    <p>{{session('captcha')}}</p>
    <a class="close" href="#"></a>
</div>
@endif

@if (session('status'))
<div class="notification success closeable">
    <p>{{session('status')}}</p>
    <a class="close" href="#"></a>
</div>
@endif