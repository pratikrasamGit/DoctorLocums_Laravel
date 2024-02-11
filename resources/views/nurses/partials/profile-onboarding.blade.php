@extends(
'nurses/profile/edit',
[
'nurse' => $nurse,
'subtitle' => 'Onboarding Schedule',
'activetab' => 'scheduleOnboarding'
]
)
@section('inner-content')
<div class="row">
<div class="col-xl-12">
    <div class="dashboard-box margin-top-0">
        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-material-outline-account-circle"></i> My Onboarding Schedule</h3>
        </div>
        <div class="content with-padding padding-bottom-0">
        <iframe data-v-1957a982="" width="100%" height="500" src="https://www.vcita.com/v/wdkfhg23lenm7bcq//online_scheduling?&amp;o=ZGlyZWN0&amp;s=https%3A%2F%2Flive.vcita.com%2Fsite%2Fwdkfhg23lenm7bcq%2Fonline-scheduling%3Fo%3DZGlyZWN0%26topUrl%3DaHR0cHM6Ly9saXZlLnZjaXRhLmNvbS9zaXRlL3dka2ZoZzIzbGVubTdiY3Evb25saW5lLXNjaGVkdWxpbmc%253D%26isWidget%3Dfalse&amp;topUrl=aHR0cHM6Ly9saXZlLnZjaXRhLmNvbS9zaXRlL3dka2ZoZzIzbGVubTdiY3Evb25saW5lLXNjaGVkdWxpbmc%3D&amp;isWidget=false&amp;&amp;vitrage_iframe=true&amp;portal_iframe=true" frameborder="0" class="flex"></iframe>
        </div>
    </div>
</div>
</div>
@endsection