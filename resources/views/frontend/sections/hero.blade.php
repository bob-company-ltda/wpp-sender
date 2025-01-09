<section class="banner_section">
      <div class="container">
        <div class="anim_line">
        @for ($i = 0; $i < 9; $i++)
        <span><img src="{{ asset('assets/frontend/images/anim_line.png') }}" alt="anim_line"></span>
        @endfor
        </div>
        <div class="row">
          <div class="col-lg-6 col-md-12">
            <div class="banner_text">
              <h1>{!! filterXss($banner->banner_header ?? __('header_banner')) !!}</h1>
              <p>{!! filterXss($banner->banner_description ?? __('header_description')) !!}</p>
            </div>
            <ul class="app_btn">
              <li>
                <a href="{{ url('/pricing') }}"><i class="icofont-globe"></i>
                {!! filterXss($banner->btnfirst ?? __('explore_btn')) !!}
                </a>
              </li>
              <li>
                <a href="{{ !Auth::check() ? url('/login') : url('/login') }}"><i class="icofont-user"></i>
                {{ !Auth::check() ? __('sign_in') : __('Dashboard') }}
                </a>
              </li>
            </ul>
            <div class="used_app">
                @php
                $images = ['used01.png', 'used02.png', 'used03.png', 'used04.png'];
                @endphp
              <ul>
                @foreach ($images as $image)
                <li><img src="{{ asset('assets/frontend/images/' . $image) }}" alt="image"></li>
                @endforeach
              </ul>
              <p>{!! filterXss($banner->usedthis ?? __('used_by')) !!}</p>
            </div>
          </div>
          <div class="col-lg-6 col-md-12">
            <div class="banner_slider">
              <div class="left_icon">
                <img src="{{ asset('assets/frontend/images/message_icon.png')}}" alt="image" >
              </div>
              <div class="right_icon">
                <img src="{{ asset('assets/frontend/images/shield_icon.png')}}" alt="image" >
              </div>
              <div id="frmae_slider" class="owl-carousel owl-theme">
                  @php
                    $sliderImages = [
                        $banner->phone_image_1 ?? '',
                        $banner->phone_image_2 ?? '',
                        $banner->phone_image_3 ?? '',
                    ];
                    @endphp
                @foreach ($sliderImages as $image)
                @if($image)
                    <div class="item">
                        <div class="slider_img">
                            <img src="{{ asset($image) }}" alt="image">
                        </div>
                    </div>
                @endif
                @endforeach
              </div>
              <div class="slider_frame">
                <img src="{{ asset('assets/frontend/images/mobile_frame_svg.svg')}}" alt="image" >
              </div>
            </div>
          </div>
        </div>
      </div>
</section>