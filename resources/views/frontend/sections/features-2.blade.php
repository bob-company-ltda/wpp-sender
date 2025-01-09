<section class="row_am modern_ui_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="ui_text">
                    <div class="section_title">
                        <h2>{{ $overview->overview_header ?? __('overview_header') }}</h2>
                        <p>{{ $overview->overview_subheader ?? __('overview_subheader') }}</p>
                    </div>
                    <ul class="design_block">
                        @foreach ([
                            ['title' => $overview->overview_title_1 ?? '', 'subtitle' => $overview->overview_subtitle_1 ?? ''],
                            ['title' => $overview->overview_title_2 ?? '', 'subtitle' => $overview->overview_subtitle_2 ?? ''],
                            ['title' => $overview->overview_title_3 ?? '', 'subtitle' => $overview->overview_subtitle_3 ?? '']
                        ] as $item)
                            <li>
                                <h4>{{ $item['title'] }}</h4>
                                <p>{{ $item['subtitle'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ui_images">
                    <div class="left_img">
                        <img class="moving_position_animatin" src="{{ asset($overview->overview_image_1 ?? '') }}" alt="image">
                    </div>
                    <div class="right_img">
                        @foreach ([
                            'assets/frontend/images/secure_data.png',
                            $overview->overview_image_2 ?? '',
                            $overview->overview_image_3 ?? ''
                        ] as $image)
                            <img class="moving_position_animatin right-img" src="{{ asset($image) }}" alt="image">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
