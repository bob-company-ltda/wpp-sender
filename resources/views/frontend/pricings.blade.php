<section class="row_am pricing_section" id="pricing">
    <div class="container">
        <div class="section_title">
            <h2>{{ __('price_header') }}</h2>
            <p>{{ __('price_subheader') }}</p>
        </div>
        <div class="toggle_block">
            <span class="month active">{{__('monthly')}}</span>
            <div class="tog_block">
                <span class="tog_btn"></span>
            </div>
            <span class="years">{{__('yearly')}}</span>
            <span class="offer">{{__('offer')}}</span>
        </div>

        @foreach (['monthly' => 30, 'yearly' => '>30'] as $planType => $dayCondition)
            <div class="pricing_pannel {{ $planType }}_plan {{ $planType == 'monthly' ? 'active' : '' }}">
                <div class="row">
                    @foreach($plans ?? [] as $plan)
                        @if(($dayCondition === 30 && $plan->days == 30) || ($dayCondition === '>30' && $plan->days > 30))
                            <div class="col-md-4">
                                <div class="pricing_block {{ $plan->is_recommended ? 'highlited_block' : '' }}">
                                    <div class="icon">
                                        <span class="icon {{ $plan->labelcolor }}"><i class="{{ $plan->iconname }}"></i></span>
                                    </div>
                                    <div class="pkg_name">
                                        <h3>{{ $plan->title }}</h3>
                                        <span>
                                            {{ 
                                                $plan->days == 30 ? 'Per month' : 
                                                ($plan->days == 90 ? 'Per quarter' : 
                                                ($plan->days == 180 ? 'Per half year' : 'Per year'))
                                            }}
                                        </span>
                                    </div>
                                    <span class="price">{{ amount_format($plan->price, 'icon') }}</span>
                                    <ul class="benifits">
                                        @foreach($plan->data ?? [] as $key => $data)
                                            <li>
                                                <p>{{ ucfirst(str_replace('_', ' ', planData($key, $data)['title'])) }}
                                                    <i class="{{ $data == 'true' ? 'icofont-check true' : ($data == 'false' ? 'icofont-close false' : '') }}"></i>
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ url('/register', $plan->id) }}" class="btn white_btn">
                                        {{ $plan->is_trial ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
