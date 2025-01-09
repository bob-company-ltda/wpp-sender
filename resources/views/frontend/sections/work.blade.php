<section class="row_am how_it_works" id="how_it_work">
    <div class="container">
        <div class="how_it_inner">
            <div class="section_title">
                <h2>{{ $work->work_header ?? __('work_header') }}</h2>
                <p>{{ $work->work_subheader ?? __('work_subheader') }}</p>
            </div>
            <div class="step_block">
                <ul>
                    @foreach ([
                        ['title' => $work->step_title_1 ?? '', 'subtitle' => $work->step_subtitle_1 ?? '', 'description' => $work->step_description_1 ?? '', 'image' => $work->step_image_1 ?? '', 'number' => '01'],
                        ['title' => $work->step_title_2 ?? '', 'subtitle' => $work->step_subtitle_2 ?? '', 'description' => $work->step_description_2 ?? '', 'image' => $work->step_image_2 ?? '', 'number' => '02'],
                        ['title' => $work->step_title_3 ?? '', 'subtitle' => $work->step_subtitle_3 ?? '', 'description' => $work->step_description_3 ?? '', 'image' => $work->step_image_3 ?? '', 'number' => '03']
                    ] as $step)
                        <li>
                            <div class="step_text">
                                <h4>{{ $step['title'] }}</h4>
                                <span>{{ $step['subtitle'] }}</span>
                                <p>{{ $step['description'] }}</p>
                            </div>
                            <div class="step_number">
                                <h3>{{ $step['number'] }}</h3>
                            </div>
                            <div class="step_img">
                                <img src="{{ asset($step['image']) }}" alt="image">
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
