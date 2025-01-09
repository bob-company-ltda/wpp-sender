<section class="row_am trusted_section">
      <div class="container">
        <div class="section_title">
          <h2>{{ __('trusted_by', ['companies' => 'multiple']) }}</h2>
        </div>
        
        <div class="company_logos">
    <div id="company_slider" class="owl-carousel owl-theme">
        @forelse($brands as $brand)
            @if($brand->lang == 'partner')
                <div class="item">
                    <div class="logo-brand">
                        <img src="{{ asset($brand->slug) }}" alt="{{ $brand->name ?? 'Brand Logo' }}" loading="lazy">
                    </div>
                </div>
            @endif
        @empty
            <p>No brands available</p>
        @endforelse
    </div>
</div>
      </div>
    </section>