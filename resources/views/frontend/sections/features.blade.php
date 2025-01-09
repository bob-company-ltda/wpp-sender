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
