<section class="row_am faq_section">
      <!-- container start -->
      <div class="container">
        <div class="section_title" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300">
          <!-- h2 -->
          <h2>{{ __('Frequently asked questions') }} 📣</h2>
          <!-- p -->
        </div>
        <!-- faq data -->
        <div class="faq_panel">
          <div class="accordion" id="accordionExample">
          @foreach($faqs as $key => $faq)
          @if($faq->slug != 'top')
            <div class="card" data-aos="fade-up" data-aos-duration="1500">
              <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                  <button type="button" class="btn btn-link active" data-toggle="collapse" data-target="#collapse{{ $faqKey }}">
                  	<i class="icon_faq icofont-plus"></i></i> {{ $faq->title ?? '' }}</button>
                </h2>
              </div>
              <div id="collapse{{ $faqKey }}" class="collapse show" aria-labelledby="heading{{ $faqKey }}" data-parent="#accordionExample">
                <div class="card-body">
                  <p>{{ $faq->excerpt->value ?? '' }}</p>
                </div>
              </div>
            </div>
            @endif
            @endforeach

          </div>
        </div>
      </div>
      <!-- container end -->
    </section>