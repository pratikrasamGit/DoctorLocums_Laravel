<script>
    $(".facility_rating").click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        rateFacility(id);
    });

    function rateFacility(id = '') {
        $('body').addClass('modal-open');

        $('.my_modal').load("{{ url('get-facility-rating') }}", {
            facility_id: id,
            _token: '{{ csrf_token() }}'
        }, function(response, status, request) {
            this; // dom element
            /* console.log('response', response);
            console.log('status', status);
            console.log('request', request); */
            if (status == "success") {} else {
                alert("Failed to retrive rating data, Please try again later")
                $('body').removeClass('modal-open');
            }
        });
    }

    function submitFacRating() {
        $.ajax({
            type: "POST",
            url: "{{ url('update-facility-rating') }}",
            data: $('#fac_form').serialize(),
            dataType: "json",
            success: function(response) {
                /* console.log(response); */
                alert(response.message);
                close_modal();
            }
        });
    }

    $(".nurse_rating").click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        rateNurse(id);
    });

    function rateNurse(id = '') {
        $('body').addClass('modal-open');

        $('.my_modal').load("{{ url('get-nurse-rating') }}", {
            nurse_id: id,
            _token: '{{ csrf_token() }}'
        }, function(response, status, request) {
            this; // dom element
            /* console.log('response', response);
            console.log('status', status);
            console.log('request', request); */
            if (status == "success") {} else {
                alert("Failed to retrive rating data, Please try again later")
                $('body').removeClass('modal-open');
            }
        });
    }

    function submitNurseRating() {
        $.ajax({
            type: "POST",
            url: "{{ url('update-nurse-rating') }}",
            data: $('#nurse_form').serialize(),
            dataType: "json",
            success: function(response) {
                /* console.log(response); */
                alert(response.message);
                close_modal();
            }
        });
    }

    function close_modal() {
        $('body').removeClass('modal-open');
    }
</script>
