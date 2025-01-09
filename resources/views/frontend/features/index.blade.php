@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.header')
    <main>

        <section class="row_am features_section" id="features">
    <div class="container">
        <div class="section_title">
            <h2>{!! optional($features)->feature_header ?? __('features_header') !!}</h2>
            <p>{!! optional($features)->feature_subheader ?? __('features_subheader') !!}</p>
        </div>
        <div class="feature_detail">
            @php
        $featuresData = optional($features);
        @endphp
            <div class="left_data feature_box">
    @foreach ([
        ['icon' => 'secure_data.png', 'title' => 'feature_1', 'details' => 'feature_1_details'],
        ['icon' => 'functional.png', 'title' => 'feature_2', 'details' => 'feature_2_details']
    ] as $feature)
        <div class="data_block">
            <div class="icon">
                <img src="{{ asset('assets/frontend/images/' . $feature['icon']) }}" alt="image">
            </div>
            <div class="text">
                <h4>{{ $featuresData->{$feature['title']} }}</h4>
                <p>{{ $featuresData->{$feature['details']} }}</p>
            </div>
        </div>
    @endforeach
</div>
            <div class="right_data feature_box">
    @foreach ([
        ['icon' => 'live-chat.png', 'title' => 'feature_3', 'details' => 'feature_3_details'],
        ['icon' => 'support.png', 'title' => 'feature_4', 'details' => 'feature_4_details']
    ] as $feature)
        <div class="data_block">
            <div class="icon">
                <img src="{{ asset('assets/frontend/images/' . $feature['icon']) }}" alt="image">
            </div>
            <div class="text">
                <h4>{{ $featuresData->{$feature['title']} }}</h4>
                <p>{{ $featuresData->{$feature['details']} }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="feature_img">
    <img src="{{ asset($featuresData->feature_image ?? '') }}" alt="image">
</div>
        </div>
    </div>
</section>


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

    </main>
@endsection
