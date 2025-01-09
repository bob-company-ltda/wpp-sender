<section class="row_am free_app_section" id="getstarted">
    <div class="container">
        <div class="free_app_inner"> 
            <!-- Animated Lines -->
            <div class="anim_line dark_bg">
                @for ($i = 0; $i < 9; $i++)
                    <span><img src="{{ asset('assets/frontend/images/anim_line.png') }}" alt="anim_line"></span>
                @endfor
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="free_text">
                        <div class="section_title">
                            <h2>{{ $download->download_header ?? __('area_header') }}</h2>
                            <p>{{ $download->download_subheader ?? __('area_subheader') }}</p>
                        </div>
                        <ul class="app_btn">
                            <li>
                                <a href="{{ url('/pricing') }}"><i class="icofont-globe"></i> {!! filterXss($banner->btnfirst) ?? __('explore_btn') !!}</a>
                            </li>
                            <li>
                                <a href="{{ url('/login') }}"><i class="icofont-user"></i> {!! filterXss($banner->btnsecond) ?? __('sign_in') !!}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="free_img">
                        <img src="{{ asset($download->hero_image_1 ?? 'assets/frontend/images/default_image.png') }}" alt="app image">
                        <img class="mobile_mockup" style="width:50%" src="{{ asset($download->hero_image_2 ?? 'assets/frontend/images/default_image.png') }}" alt="mobile mockup">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
