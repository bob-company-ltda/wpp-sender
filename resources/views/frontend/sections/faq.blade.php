<section class="row_am faq_section">
    <div class="container">
        <div class="section_title">
            <h2>{{ __('Frequently asked questions') }} 📣</h2>
        </div>
        <div class="faq_panel">
            <div class="accordion" id="accordionExample">
                @foreach($faqs as $key => $faq)
                    @continue($faq->slug == 'top')
                    <div class="card">
                        <div class="card-header" id="heading{{ $key }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link {{ $key == 0 ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="{{ $key == 0 ? 'true' : 'false' }}">
                                    <i class="icon_faq icofont-plus"></i> {{ $faq->title }}
                                </button>
                            </h2>
                        </div>
                        <div id="collapse{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                            <div class="card-body">
                                <p>{{ $faq->excerpt->value ?? 'No information available.' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
