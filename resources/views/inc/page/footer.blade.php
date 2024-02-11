@yield('popup')
</div>
<!-- Wrapper / End -->
<!-- Scripts
================================================== -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-3.3.2.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/mmenu.min.js') }}"></script>
<script src="{{ asset('js/tippy.all.min.js') }}"></script>
<script src="{{ asset('js/simplebar.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/snackbar.js') }}"></script>
<script src="{{ asset('js/clipboard.min.js') }}"></script>
<script src="{{ asset('js/counterup.min.js') }}"></script>
<script src="{{ asset('js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.selectform').select2({
            width: '100%'
        });
    });
</script>

<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
    // Snackbar for user status switcher
    $('.status-switch label').click(function() {
        Snackbar.show({
            text: 'Your status has been changed!',
            pos: 'bottom-center',
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: '#fff',
            backgroundColor: '#383838'
        });
    });

    // Snackbar for copy to clipboard button
    $('.copy-url-button').click(function() {
        Snackbar.show({
            text: 'Copied to clipboard!',
        });
    });
</script>

<!-- Google Autocomplete -->
<script>
    function initAutocomplete() {
        var options = {
            types: ['(cities)'],
            // componentRestrictions: {country: "us"}
        };

        var input = document.getElementById('autocomplete-input');
        var autocomplete = new google.maps.places.Autocomplete(input, options);
    }
</script>

<script type="text/javascript" id="pap_x2s6df8d" src="https://nurseify.postaffiliatepro.com/scripts/76jow0"></script>
<script type="text/javascript">
    PostAffTracker.setAccountId('default1');
    try {
        PostAffTracker.track();
    } catch (err) {}
</script>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA79miT_WdRMx999ohM1pnras6_15UOJWQ&libraries=places"></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4RPFSIF8JjM8EpuScHICcbMZsCLTcgjE&libraries=places">
</script>
<script src="{{ asset('js/infobox.min.js') }}"></script>
<script src="{{ asset('js/markerclusterer.js') }}"></script>
<script src="{{ asset('js/maps.js') }}"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script type="text/javascript">
    tippy('#vbadge', {
        //content: "Lorem Ipsum is simply dummy text of the",
        content(reference) {
            const id = reference.getAttribute('data-template');
            const template = document.getElementById(id);
            return template.innerHTML;
        },
        allowHTML: true,
        maxWidth: 350,
        interactive: true,
        allowHTML: true,
        theme: 'light',
    });
</script>
@yield('notification')

<script type="text/javascript">
    var st = "{{ isset($_GET['state']) ? $_GET['state'] : '' }}";
    var ct = "{{ isset($_GET['city']) ? $_GET['city'] : '' }}";
    if (typeof st !== 'undefined' && st != "") {
        var ct_id = (typeof ct !== 'undefined' && ct != "") ? ct : "";
        loadCities(st, ct_id);
    }

    function loadStates(country_id = "", state_id = "") {
        var country_id = this.value;
        $("#state-dropdown").html('');
        $.ajax({
            url: "{{ url('get-states-by-country') }}",
            type: "POST",
            data: {
                country_id: country_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('#state-dropdown').html('<option value="">Select State</option>');
                $.each(result.states, function(key, value) {
                    $("#state-dropdown").append('<option value="' + value.id +
                        '">' + value.name + '</option>');
                });
                $('#city-dropdown').html(
                    '<option value="">Select State First</option>');
            }
        });
    }

    function loadCities(state_id = "", city_id = "") {
        $("#city-dropdown").html('');
        $.ajax({
            url: "{{ url('get-cities-by-state') }}",
            type: "POST",
            data: {
                state_id: state_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('#city-dropdown').html('<option value="">Select City</option>');
                var selected_city = "";
                $.each(result.cities, function(key, value) {
                    selected_city = (value.id == city_id) ? "selected" : "";
                    $("#city-dropdown").append('<option value="' + value.id + '" ' + selected_city +
                        '>' + value.name + '</option>');
                });
            }
        });
    }
</script>
@include('inc.rating_js')
</body>

</html>
