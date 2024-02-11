<div class="popup_modal">
    <button class="close_modal" onclick="close_modal()">x</button>
    <h4>Rate the Facility </h4>
    <?php
    /* echo '<pre>';
        print_r($user);
        echo '</pre>'; */
    ?>
    <h5 class="number_select_hdng">Overall</h5>
    <form method="POST" action="#" id="fac_form">
        @method('post')
        @csrf
        <div>
            <div id="">
                <div style="width: 100%; display: flex;">
                    {{-- setup 6 --}}
                    <div class="rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                <?= isset($ratings['over_all']) && $ratings['over_all'] == $i ? 'checked' : '' ?> />
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                    {{-- <h1>Rating: <span id="rating">0</span></h1> --}}
                    {{-- setup 6 --}}
                </div>

            </div>
        </div>

        <div class="number_select">
            <h5 class="number_select_hdng">Onboarding</h5>
            <div class="rating-group">

                @for ($i = 1; $i <= 10; $i++)
                    <div class="number_label">
                        <input
                            class="number_label_input a <?= isset($ratings['on_board']) && $ratings['on_board'] == $i ? 'active' : '' ?>"
                            id="{{ $i }}" value="{{ $i }}" readonly>
                    </div>
                @endfor

            </div>
        </div>

        <div class="number_select">
            <h5 class="number_select_hdng">Nurse Teamwork</h5>
            <div class="rating-group">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="number_label">
                        <input
                            class="number_label_input b <?= isset($ratings['nurse_team_work']) && $ratings['nurse_team_work'] == $i ? 'active' : '' ?>"
                            id="{{ $i }}" value="{{ $i }}" readonly>
                    </div>
                @endfor
            </div>
        </div>

        <div class="number_select">
            <h5 class="number_select_hdng">Leadership Support</h5>
            <div class="rating-group">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="number_label">
                        <input
                            class="number_label_input c <?= isset($ratings['leadership_support']) && $ratings['leadership_support'] == $i ? 'active' : '' ?>"
                            id="{{ $i }}" value="{{ $i }}" readonly>
                    </div>
                @endfor
            </div>
        </div>

        <div class="number_select">
            <h5 class="number_select_hdng">Tools to do my job</h5>
            <div class="rating-group">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="number_label">
                        <input
                            class="number_label_input d <?= isset($ratings['tools_todo_my_job']) && $ratings['tools_todo_my_job'] == $i ? 'active' : '' ?>"
                            id="{{ $i }}" value="{{ $i }}" readonly>
                    </div>
                @endfor
            </div>
        </div>
        <textarea name="experience"
            placeholder="Tell us your experience with this Facility"><?= isset($ratings['experience']) && $ratings['experience'] != '' ? $ratings['experience'] : '' ?></textarea>

        {{ Form::hidden('over_all', '0') }}
        {{ Form::hidden('onboarding', '0') }}
        {{ Form::hidden('nurse_team_work', '0') }}
        {{ Form::hidden('leadership_support', '0') }}
        {{ Form::hidden('tools_todo_my_job', '0') }}
        {{ Form::hidden('facility_id', $facility_id) }}
    </form>

    {{ Form::button('Submit', ['type' => 'submit', 'class' => 'm_btn', 'onclick' => 'submitFacRating()']) }}
</div>

<script>
    $('input').click(function() {
        $('#rating').html($(this).val());
    });
    $(".a").click(function() {
        $('input[name="onboarding"]').val($(this).val());
        $('.a').removeClass('active');
        $(this).toggleClass('active');
    });

    $(".b").click(function() {
        $('input[name="nurse_team_work"]').val($(this).val());
        $('.b').removeClass('active');
        $(this).toggleClass('active');
    });

    $(".c").click(function() {
        $('input[name="leadership_support"]').val($(this).val());
        $('.c').removeClass('active');
        $(this).toggleClass('active');
    });

    $(".d").click(function() {
        $('input[name="tools_todo_my_job"]').val($(this).val());
        $('.d').removeClass('active');
        $(this).toggleClass('active');
    });
</script>
