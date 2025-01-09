<section class="row_am about_app_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about_img">
                    <div class="frame_img">
                        <img class="moving_position_animatin" src="{{ asset(optional($about)->frame_image) }}" alt="image">
                    </div>
                    <div class="screen_img">
                        <img class="moving_animation" src="{{ asset(optional($about)->frame_image_2) }}" alt="image">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about_text">
                    <div class="section_title">
                        <h2>{{ optional($about)->about_header ?? __('about_header') }}</h2>
                        <p>{{ optional($about)->about_subheader ?? __('about_subheader') }}</p>
                    </div>
                    <ul class="app_statstic" id="counter">
    @foreach ([
        ['icon' => 'download.png', 'count' => optional($about)->about_api, 'label' => 'Connected Api'],
        ['icon' => 'followers.png', 'count' => optional($about)->satisfied_user, 'label' => 'Satisfied user'],
        ['icon' => 'reviews.png', 'count' => optional($about)->customer_review ?? '1500', 'label' => 'Reviews'],
        ['icon' => 'countries.png', 'count' => optional($about)->about_countries, 'label' => 'Countries']
    ] as $stat)
        <li>
            <div class="icon">
                <img src="{{ asset('assets/frontend/images/' . $stat['icon']) }}" alt="image">
            </div>
            <div class="text">
                <p><span class="counter-value" data-count="{{ $stat['count'] ?? 0 }}">0</span><span>+</span></p>
                <p>{{ $stat['label'] }}</p>
            </div>
        </li>
    @endforeach
</ul>
                    <a href="{{ !Auth::check() ? url('/pricing') : url('/login') }}" class="btn puprple_btn">START FREE TRIAL</a>
                </div>
            </div>
        </div>
    </div>
</section>
