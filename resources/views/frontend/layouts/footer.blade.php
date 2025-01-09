<footer>
    <div class="top_footer" id="contact">
        <div class="anim_line dark_bg">
            @for ($i = 0; $i < 9; $i++)
                <span><img src="{{ asset('assets/frontend/images/anim_line.png') }}" alt="anim_line"></span>
            @endfor
        </div>
        <div class="container">
            <div class="row">
                {{-- About Section --}}
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="abt_side">
                        <div class="logo">
                            <img src="{{ asset(get_option('primary_data', true)->footer_logo ?? '') }}" alt="image">
                        </div>
                        <ul>
                            @php
                                $contact_email = get_option('primary_data', true)->contact_email ?? '';
                                $contact_phone = get_option('primary_data', true)->contact_phone ?? '';
                            @endphp
                            <li><a href="mailto:{{ $contact_email }}">{{ $contact_email }}</a></li>
                            <li><a href="tel:{{ $contact_phone }}">{{ $contact_phone }}</a></li>
                        </ul>
                        <ul class="social_media">
                            @foreach (['facebook', 'twitter', 'instagram', 'linkedin'] as $social)
                                @if (!empty(get_option('primary_data', true)->socials->$social))
                                    <li><a href="{{ get_option('primary_data', true)->socials->$social }}">
                                        <i class="icofont-{{ $social }}"></i>
                                    </a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Useful Links --}}
                <div class="col-lg-3 col-md-6 col-12 d-none d-lg-block d-xl-block">
                    <div class="links">
                        <h3>Useful Links</h3>
                        <ul>{!! PrintExtraMenu('main-menu') !!}</ul>
                    </div>
                </div>

                {{-- Other Pages --}}
                <div class="col-lg-3 col-md-6 col-12 d-none d-lg-block d-xl-block">
                    <div class="links">
                        <h3>Other Pages</h3>
                        <ul>{!! PrintPages() !!}</ul>
                    </div>
                </div>

                {{-- Try Out Section --}}
                <div class="col-lg-2 col-md-6 col-12">
                    <div class="try_out">
                        <h3>Let’s Try Out</h3>
                        <ul class="app_btn">
                            @php
                                $btnsecond = get_option('banner', true, true)->btnsecond ?? '';
                                $btnfirst = get_option('banner', true, true)->btnfirst ?? '';
                            @endphp
                            <li><a href="{{ url('/login') }}"><i class="icofont-user"></i> {{ $btnsecond }}</a></li>
                            <li><a href="{{ url('/pricing') }}"><i class="icofont-globe"></i> {{ $btnfirst }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Footer --}}
    <div class="bottom_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-white">&copy; Copyrights 2024. All rights reserved.</p>
                </div>
                <div class="col-md-6">
                    <p class="developer_text text-white">Design & developed with <i class="icofont-heart"></i></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Go to Top --}}
    <div class="go_top">
        <span><img src="{{ asset('assets/frontend/images/go_top.png') }}" alt="image"></span>
    </div>
</footer>
