<section class="row_am testimonial_section"> 
    <div class="container">
        <div class="section_title">
            <h2>{{ $home->testimonial->title ?? '' }}</h2>
        </div>
        <div class="testimonial_block">
            <div id="testimonial_slider" class="owl-carousel owl-theme">
                @foreach($testimonials as $testimonial)
                    <div class="item">
                        <div class="testimonial_slide_box">
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    <span><i class="icofont-star"></i></span>
                                @endfor
                            </div>
                            <p class="review">“ {{ Str::limit($testimonial->excerpt->value ?? '', 150) }} ”</p>
                            <div class="testimonial_img">
                                <img src="{{ asset($testimonial->preview->value ?? 'assets/frontend/images/default-avatar.png') }}" alt="testimonial image">
                            </div>
                            <h3>{{ $testimonial->title ?? 'Anonymous' }}</h3>
                            <span class="designation">({{ $testimonial->slug ?? 'Guest' }})</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="total_review">
                <div class="rating">
                    @for($i = 0; $i < 5; $i++)
                        <span><i class="icofont-star"></i></span>
                    @endfor
                    <p>5.0 / 5.0</p>
                </div>
            </div>

            <div class="avtar_faces">
                <img src="{{ asset('assets/frontend/images/avtar_testimonial.png') }}" alt="avatars image">
            </div>
        </div>
    </div>
</section>
