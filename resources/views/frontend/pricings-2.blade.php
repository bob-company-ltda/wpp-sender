<section class="waf1">
  <div class="container-fluid pad100">
    <div class="row text-center ">
      <div class="rpt">
        <h1 class="h1 ">
          <div class="default">
            <b class="shoutout engg">{{ __('Pricing to suite all size of business') }}</b>
          </div>
        </h1>
        <div class="default">
          <h2 class="h2">{{ __('*We help companies of all sizes') }}</h2>
        </div>
      </div>
    </div>
    
    <div class="desktop">
      <div class="row adjustbxflex center space rqw">
        <ul class="tabbs adjustbxflex ">
          <li class="tab oone price_clr default ">
            <button class="btn" href="#billAnnuallyWacloud" data-target="#billAnnuallyWacloud"  data-toggle="tab">Annual</button>
          </li>
          <li class="tab active ttwo price_clr default">
            <button class="btn" href="#billMonthlyWacloud" data-target="#billMonthlyWacloud" data-toggle="tab" aria-expanded="false">Monthly</button>
          </li>
        </ul>
      </div>
      
      <div class=" waf4 row item4 space">
        <div class="tab-content ">
          <div class="tab-pane fade in" id="billAnnuallyWacloud">
            <div class="default as">
              <ul class="adjustbxflex">
                  @foreach($plans ?? [] as $plan)
                  @if($plan->days > 30)
                <li class=" @if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif">
                    @if($plan->is_recommended == 1)
                    <img src="{{asset('assets/img/pop-tag.webp')}}" class="populr" alt="popular">
                    @endif
                  <div class="text-center">
                    <div class="clr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">
                      <h2 class="h4">
                        <strong>{{ $plan->title }}</strong>
                      </h2>
                      <span>To effectively connect, update &amp; significantly boost sales through WhatsApp campaigns </span>
                    </div>
                    <div class="pr_space aqa">
                      <h2 class="h4">
                        <strong>{{ amount_format($plan->price,'icon') }}</strong>
                      </h2>
                      <span>{{ $plan->days == 30 ? 'Per month' : ($plan->days == 90 ? 'Per quarter' : ($plan->days == 180 ? 'Per half year' : 'Per year')) }}</span>
                    </div>
                    
                    <div class="pr_desktop">
                      <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                    </div>
                    <div class="pr_space">
                      <h2 class="h4">
                        <strong>Official WhatsApp API</strong>
                      </h2>
                    </div>
                    
                    <div class="pr_space aqa ftr">
                        @foreach($plan->data ?? [] as $key => $data)
                        <span>{{ ucfirst(str_replace('_',' ',planData($key,$data)['title'])) }}<i class="@if($data == 'true') icofont-check true @elseif($data == 'false') icofont-close false @endif"></i></span>
                        @endforeach
                    </div>
                    
                    <div class="nonw">
                      <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                    </div>
                    
                  </div>
                </li>
                @endif
                @endforeach
              </ul>
              <ul class="adjustbxflex aw">
                   @foreach($plans ?? [] as $plan)
                   @if($plan->days > 30)
                <li class="adjustbxflex">
                  <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
          
          
          <div class="tab-pane fade active in" id="billMonthlyWacloud">
            <div class="default as">
              <ul class="adjustbxflex">
                  @foreach($plans ?? [] as $plan)
                  @if($plan->days == 30)
                <li class="@if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif">
                    @if($plan->is_recommended == 1)
                    <img src="{{asset('assets/img/pop-tag.webp')}}" class="populr" alt="popular">
                    @endif
                  <div class="text-center">
                    <div class="clr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">
                      <h2 class="h4">
                        <strong>{{ $plan->title }}</strong>
                      </h2>
                      <span>To effectively connect, update &amp; significantly boost sales through WhatsApp campaigns </span>
                    </div>
                    <div class="pr_space aqa">
                      <h2 class="h4">
                        <strong>{{ amount_format($plan->price,'icon') }}</strong>
                      </h2>
                      @if($plan->days == 30)
                      <span>per month</span>
                      @endif
                    </div>
                    <div class="pr_desktop">
                      <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                    </div>
                    <div class="pr_space">
                      <h2 class="h4">
                        <strong>Official WhatsApp API</strong>
                      </h2>
                    </div>
                    <div class="pr_space aqa ftr">
                        @foreach($plan->data ?? [] as $key => $data)
                        <span>{{ ucfirst(str_replace('_',' ',planData($key,$data)['title'])) }}<i class="@if($data == 'true') icofont-check true @elseif($data == 'false') icofont-close false @endif"></i></span>
                        @endforeach
                    </div>
                    
                    <div class="nonw">
                      <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                    </div>
                  </div>
                </li>
                @endif
                @endforeach
              </ul>
              <ul class="adjustbxflex aw">
                  
                 @foreach($plans ?? [] as $plan)
                 @if($plan->days == 30)
                <li class="adjustbxflex">
                  <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    
    
    <div class="moobile">
      <div class="row adjustbxflex center space rqw">
        <ul class="tabbs adjustbxflex ">
          <li class="tab oone price_clr default ">
            <button class="btn" href="#mbillAnnuallyWacloud" data-target="#mbillAnnuallyWacloud"  data-toggle="tab">Annual</button>
          </li>
          <li class="tab active ttwo  price_clr default ">
            <button class="btn" href="#mbillMonthlyWacloud" data-target="#mbillMonthlyWacloud" data-toggle="tab">Monthly</button>
          </li>
        </ul>
      </div>
      <div class=" waf4 row item4 space">
        <div class="tab-content">
            
            
          <div class="tab-pane-ar fade" id="mbillAnnuallyWacloud">
            <ul class="tabbss adjustbxflex ">
                @foreach($plans ?? [] as $plan)
                  @if($plan->days > 30)
              <li class="tab tab-2  @if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif default ">
                <button class="btn" href="#annualy-{{ $plan->title }}" data-target="#annualy-{{ $plan->title }}" data-toggle="tab">{{ $plan->title }}</button>
              </li>
              @endif
              @endforeach
              <!--  -->
            </ul>
            <div class="tab-content">
                @foreach($plans ?? [] as $plan)
                  @if($plan->days > 30)
              <div class="tab-pane tab-pane-2 fade" id="annualy-{{ $plan->title }}" role="tabpanel">
                <div class="default as">
                  <ul class="adjustbxflex">
                    <li class="@if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif">
                      <div class="text-center">
                        <div class="clr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">
                          <h2 class="h4">
                            <strong>{{ $plan->title }}</strong>
                          </h2>
                          <span>To effectively connect, update &amp; significantly boost sales through WhatsApp campaigns </span>
                        </div>
                        <div class="pr_space aqa">
                          <h2 class="h4">
                            <strong>{{ amount_format($plan->price,'icon') }}</strong>
                          </h2>
                          <span>{{ $plan->days == 30 ? 'Per month' : ($plan->days == 90 ? 'Per quarter' : ($plan->days == 180 ? 'Per half year' : 'Per year')) }}</span>
                        </div>
                        <div class="pr_desktop">
                          <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                        </div>
                        <div class="pr_space">
                          <h2 class="h4">
                            <strong>Official WhatsApp API</strong>
                          </h2>
                        </div>
                        <div class="pr_space aqa ftr">
                            
                          @foreach($plan->data ?? [] as $key => $data)
                        <span>{{ ucfirst(str_replace('_',' ',planData($key,$data)['title'])) }}<i class="@if($data == 'true') icofont-check true @elseif($data == 'false') icofont-close false @endif"></i></span>
                        @endforeach
                        </div>
                        <div class="nonw">
                          <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              @endif
              @endforeach
            </div>
          </div>
          
          <div class="tab-pane-ar fade active in" id="mbillMonthlyWacloud">
            <ul class="tabbss adjustbxflex ">
                 @foreach($plans ?? [] as $plan)
                  @if($plan->days == 30)
                  
              <li class="tab tab-2  @if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif default ">
                <button class="btn" href="#monthly-{{ $plan->title }}" data-target="#monthly-{{ $plan->title }}" data-toggle="tab">{{ $plan->title }}</button>
              </li>
              @endif
              @endforeach
              <!--  -->
            </ul>
            <div class="tab-content">
                @foreach($plans ?? [] as $plan)
                  @if($plan->days == 30)
              <div class="tab-pane tab-pane-2 fade in" id="monthly-{{ $plan->title }}" role="tabpanel">
                <div class="default as">
                  <ul class="adjustbxflex">
                    <li class="@if($plan->labelcolor == 'price-color-1') pink @elseif($plan->labelcolor == 'price-color-2') price_clr @else bl @endif">
                      <div class="text-center">
                        <div class="clr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">
                          <h2 class="h4">
                            <strong>{{ $plan->title }}</strong>
                          </h2>
                          <span>To effectively connect, update &amp; significantly boost sales through WhatsApp campaigns </span>
                        </div>
                        <div class="pr_space aqa">
                          <h2 class="h4">
                            <strong>{{ amount_format($plan->price,'icon') }}</strong>
                          </h2>
                          <span>per month</span>
                        </div>
                        
                        <div class="pr_desktop">
                          <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                        </div>
                        <div class="pr_space">
                          <h2 class="h4">
                            <strong>Official WhatsApp API</strong>
                          </h2>
                        </div>
                        <div class="pr_space aqa ftr">
                            
                          @foreach($plan->data ?? [] as $key => $data)
                        <span>{{ ucfirst(str_replace('_',' ',planData($key,$data)['title'])) }}<i class="@if($data == 'true') icofont-check true @elseif($data == 'false') icofont-close false @endif"></i></span>
                        @endforeach
                        
                        </div>
                        <div class="nonw">
                          <a href="{{ url('/register',$plan->id) }}" class="price_btn btn brdr @if($plan->labelcolor == 'price-color-1') drk_pink @elseif($plan->labelcolor == 'price-color-2') green @else drk_bl @endif">{{ $plan->is_trial == 1 ? __('Free '.$plan->trial_days.' days trial') : __('Sign Up Now') }}</a>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              @endif
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>