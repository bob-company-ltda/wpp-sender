@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection')
@endsection
@section('content')
    <!-- Main Body Starts -->
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 layout-spacing">
                <div class="widget bg-light-primary border-0 br-30 mb-4">
                    <div class="widget-content align-items-center">
                        <div class="mb-4">
                            <img src="{{ Auth::user()->avatar == null ? 'https://ui-avatars.com/api/?name='.Auth::user()->name : asset(Auth::user()->avatar) }}" alt="avatar" height="120px" width="120px" class="rounded-circle img-thumbnail">
                            <span class="analytic-ic-1"><i class="fa fa-medal"></i></span>
                            <span class="analytic-ic-2"><i class="fa fa-certificate"></i></span>
                            <span class="analytic-ic-3"><i class="fa fa-star"></i></span>
                            <span class="analytic-ic-4"><i class="fa fa-hourglass-start"></i></span>
                        </div>
                        <p class="light mb-2">{{__('Welcome Back!')}}</p>
                        <h4 class="stronger mb-4">{{Auth::user()->name;}}</h4>
                        @if(getUserPlanData('mechanism') ==1)
                        <a href="{{route('user.device.index')}}" class="btn btn-dark btn-rounded">{{__('Open WhatsApp Device')}}</a>
                        @else
                        <a href="{{route('user.cloudapi.index')}}" class="btn btn-dark btn-rounded">{{__('Open WhatsApp API')}}</a>
                        @endif
                    </div>
                </div>
                <div class="widget">
                    <div class="widget-heading fm">
                        <h5 class="mb-4">{{__('Frequecny Meter')}}</h5>
                        <div class="dropdown custom-dropdown-icon">
        <a class="dropdown-toggle p-0 border-0 font-25" href="#" role="button" id="totalUserDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span><i class="fa fa-ellipsis-h"></i></span>
            </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="totalUserDropdown" id="meter">
            <a class="dropdown-item" data-value="1" href="javascript:void(0);">{{__('This Day')}}</a>
            <a class="dropdown-item" data-value="7" href="javascript:void(0);">{{__('This Week')}}</a>
            <a class="dropdown-item" data-value="30" href="javascript:void(0);">{{__('This Month')}}</a>
            <a class="dropdown-item" data-value="365" href="javascript:void(0);">{{__('Year')}}</a>
        </div>
    </div>
                    </div>
                    <div class="widget-content">
                        <div class="single-expense bg-light-info mb-2">
                            <div class="d-inline-flex align-items-center">
                                        <span class="icon-container">
                                            <i class="fa fa-robot"></i>
                                        </span>
                                <div>
                                    <h6 class="mb-1 font-15 strong">{{__('Chatbot Replies')}}</h6>
                                    
                                </div>
                            </div>
                            <span class="stronger" id="chatbot-count"><img src="{{ asset('uploads/loader.gif') }}"></span>
                        </div>
                        <div class="single-expense bg-light-warning mb-2">
                            <div class="d-inline-flex align-items-center">
                                        <span class="icon-container">
                                            <i class="fa fa-server"></i>
                                        </span>
                                <div>
                                    <h6 class="mb-1 font-15 strong">{{__('Bulk Messages')}}</h6>
                                </div>
                            </div>
                            <span class="stronger" id="bulk-count"><img src="{{ asset('uploads/loader.gif') }}"></span>
                        </div>
                        <div class="single-expense bg-light-primary">
                            <div class="d-inline-flex align-items-center">
                                        <span class="icon-container">
                                            <i class="fa fa-paper-plane"></i>
                                        </span>
                                <div>
                                    <h6 class="mb-1 font-15 strong">{{__('Single Send')}}</h6>
                                </div>
                            </div>
                            <span class="stronger" id="single-count"><img src="{{ asset('uploads/loader.gif') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-chart-one mb-4">
                    <div class="widget-heading mb-0">
    <div>
        <h5>{{__('Message Analysis')}}</h5>
        <div class="w-numeric-title mt-1" id="analysis"><span class="light text-primary font-12"><span id="message-analysis">0</span>{{__(' Message has sent')}}</span> {{__('in last 30 Days')}}</div>
    </div>
    <div class="dropdown custom-dropdown-icon">
        <a class="dropdown-toggle p-0 border-0 font-25" href="#" role="button" id="totalUserDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><i class="fa fa-ellipsis-h"></i></span></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="totalUserDropdown" id="period">
            <a class="dropdown-item" data-value="1" href="javascript:void(0);">{{__('This Day')}}</a>
            <a class="dropdown-item" data-value="7" href="javascript:void(0);">{{__('This Week')}}</a>
            <a class="dropdown-item" data-value="30" href="javascript:void(0);">{{__('This Month')}}</a>
            <a class="dropdown-item" data-value="365" href="javascript:void(0);">{{__('Year')}}</a>
        </div>
    </div>
</div>
                    <div class="widget-content">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent">
                                <div id="totalTokensChart"></div>
                            </div>
                            @if(getUserPlanData('mechanism') ==1)
                            <p></p>
                            @else
                            @if(isset($pluginConfigs['read_receipt']) && $pluginConfigs['read_receipt']['status'] === 1)
                            @if(getUserPlanData('read_receipt') == true)
                            <div class="token-container" id="DeliveryStatus">
                                <div class="single-token-count">
                                    <span class="bg-success"><i class="fa fa-check-circle font-20"></i></span>
                                    <h6 class="text-success-teal strong">{{__('Seen')}}</h6>
                                    <p class="text-success-teal mb-0" id="read-count"></p>
                                </div>
                                <div class="single-token-count">
                                    <span class="bg-primary"><i class="fa fa-check"></i></span>
                                    <h6 class="text-primary strong">{{__('Delivered')}}</h6>
                                    <p class="text-primary mb-0" id="delivered-count"></p>
                                </div>
                                <div class="single-token-count">
                                    <span class="bg-danger"><i class="fa fa-minus-circle"></i></span>
                                    <h6 class="text-danger strong">{{__('Failed')}}</h6>
                                    <p class="text-danger mb-0" id="failed-count"></p>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="widget p-3">
                    <div class="widget-content ">
                        <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started/">
                        <div class="d-flex align-items-center">
                            <div class="overflow-hidden br-20 mr-3">
                                <img src="https://img.freepik.com/premium-psd/3d-render-meta-social-media-icons-facebook-whatsapp-instagram-template-ui-ux-web-designs_549761-58.jpg" height="110px" width="150px" style="border-radius:20px;"/>
                            </div>
                            <div>
                                <h5 class="mb-2 strong text-dark font-17">{{__('How to configure WhatsApp Cloud API')}}</h5>
                                <div class="font-13 mb-3">{{__('126,985 Views')}}</div>
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/1024px-2023_Facebook_icon.svg.png" class="avatar-xs rounded-circle mr-2"/>
                                    <div>
                                        <p class="mb-0 font-13 light">{{__('Facebook')}} <span class="online ml-1"></span></p>
                                        <p class="mb-0 font-10 light">{{__('Read this')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget mb-4">
                    <div class="widget-heading">
                        <h5 class="">{{__('Plan Credits & Limits')}}</h5>
                        <span class="w-numeric-title">{{__('Active till ')}}{{ \Carbon\Carbon::parse(Auth::user()->will_expire)->format('d M Y') ?? '' }}</span>
                    </div>
                    <div class="widget-content">
                        <div class="customer-issues">
                            <div class="customer-issue-list">
                                <div class="customer-issue-details">
                                    <div class="customer-issues-info">
                                        <h6 class="mb-2 font-12 text-success-teal">{{__('Message Limits')}}</h6>
                                        @if($plan->messages_limit < 0)
                                        <p class="issues-count mb-2 font-12 text-success-teal">Unlimited</p>
                                        @else
                                        <p class="issues-count mb-2 font-12 text-success-teal">{{ $plan->messages_limit }}</p>
                                        @endif
                                    </div>
                                    <div class="customer-issues-stats">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-success position-relative" role="progressbar" style="width: 69%" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100"><span class="success-teal"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-issue-list">
                                <div class="customer-issue-details">
                                    <div class="customer-issues-info">
                                        <h6 class="mb-2 font-12 text-primary">{{__('Contact Limits')}}</h6>
                                        @if($plan->contact_limit < 0)
                                        <p class="issues-count mb-2 font-12 text-primary">Unlimited</p>
                                        @else
                                        <p class="issues-count mb-2 font-12 text-primary">{{ $plan->contact_limit }}</p>
                                        @endif
                                    </div>
                                    <div class="customer-issues-stats">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-secondary  position-relative" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><span class="secondary"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-issue-list">
                                <div class="customer-issue-details">
                                    <div class="customer-issues-info">
                                        <h6 class="mb-2 font-12 text-warning">{{__('Template Limits')}}</h6>
                                        @if($plan->template_limit < 0)
                                        <p class="issues-count mb-2 font-12 text-warning">Unlimited</p>
                                        @else
                                        <p class="issues-count mb-2 font-12 text-warning">{{ $plan->template_limit }}</p>
                                        @endif
                                    </div>
                                    <div class="customer-issues-stats">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-warning position-relative" role="progressbar" style="width: 11%" aria-valuenow="11" aria-valuemin="0" aria-valuemax="100"><span class="warning"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($pluginConfigs['wallet_system']) && $pluginConfigs['wallet_system']['status'] === 1)
            @if(getUserPlanData('wallet_system') == true)
                            <div class="customer-issue-list" style="padding: 10px; background: #aaffd3; border-radius: 8px;">
                                <div class="customer-issue-details">
                                    <div class="customer-issues-info">
                                        <h6 class="mb-2 font-12">{{__('Meta Credits')}}<br>{{amount_format($creditRate)}}{{__('/Conversation')}}</h6>
                                        
                                        <p class="issues-count mb-2 font-12"><strong>{{ amount_format($credit) }}</strong></p>
                                    </div>
                                    <div class="customer-issues-stats">
                                        <div class="progress" style="box-shadow:none;">
                                            @php $pr = ($credit*100)/10000 @endphp
                                            <div class="progress-bar bg-dark position-relative" role="progressbar" style="width: {{$pr}}%" aria-valuenow="{{ $credit }}" aria-valuemin="0" aria-valuemax="10000"><span class="warning"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="widget widget-best-seller">
                    <div class="widget-heading mb-4">
                        <h5>{{__('Most Used Templates')}}</h5>
                        <span class="w-numeric-title">{{ $templates->title ?? 'Not Calculated Yet' }}</span>
                    </div>
                    <div class="bs-content row">
                        <div class="col-md-6">
                            <img src="{{ asset('assets/img/trophy.png') }}" class="best-seller-trophy"/>
                        </div>
                        <div class="col-md-6 text-right">
                            <img src="{{ asset('assets/img/meta.png') }}" class="avatar-sm rounded-circle">
                            <h1 class="mb-0">{{ $mostUsedTemplateId->template_count ?? 0 }}</h1>
                            <p class="mb-4">{{__('time Used')}}</p>
                            <a href="{{route('user.template.create')}}" class="btn bg-green btn-sm text-white">{{__('Create')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <a class="widget quick-category">
                    <div class="quick-category-head">
                                <span class="quick-category-icon qc-primary rounded-circle">
                                    <i class="fa fa-server"></i>
                                </span>
                        <div class="ml-auto">
                            <div class="quick-comparison bg-blue">
                                <span> {{ __('Meta') }}</span>
                               
                            </div>
                        </div>
                    </div>
                    <div class="quick-category-content">
                        <h3 id="total-device"><img src="{{ asset('uploads/loader.gif') }}"></h3>
                        <p class="font-17 text-primary mb-0"> {{ __('Connected APIs') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <a class="widget quick-category">
                    <div class="quick-category-head">
                                <span class="quick-category-icon qc-warning rounded-circle">
                                    <i class="fa fa-paper-plane"></i>
                                </span>
                        <div class="ml-auto">
                            <div class="quick-comparison bg-green">
                                <span> {{ __('WhatsApp') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="quick-category-content">
                        <h3 id="total-messages"><img src="{{ asset('uploads/loader.gif') }}"></h3>
                        <p class="font-17 text-warning mb-0"> {{ __('All Messages') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <a class="widget quick-category">
                    <div class="quick-category-head">
                                <span class="quick-category-icon qc-secondary rounded-circle">
                                    <i class="fa fa-calendar"></i>
                                </span>
                        <div class="ml-auto">
                            <div class="quick-comparison qcompare-danger">
                                <span> {{ __('Campaigns') }}</span>
                                
                            </div>
                        </div>
                    </div>
                    <div class="quick-category-content">
                        <h3 id="total-schedule"><img src="{{ asset('uploads/loader.gif') }}"></h3>
                        <p class="font-17 text-primary mb-0"> {{ __('Ongoing Campaign') }}</p>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <a class="widget quick-category">
                    <div class="quick-category-head">
                                <span class="quick-category-icon qc-success-teal rounded-circle">
                                    <i class="fa fa-address-book"></i>
                                </span>
                        <div class="ml-auto">
                            <div class="quick-comparison bg-dark">
                                <span> {{ __('Recepient') }}</span>
                                
                            </div>
                        </div>
                    </div>
                    <div class="quick-category-content">
                        <h3 id="total-contacts"><img src="{{ asset('uploads/loader.gif') }}"></h3>
                        <p class="font-17 text-success-teal mb-0"> {{ __('Total Contacts') }}</p>
                    </div>
                </a>
            </div>
            @if(isset($pluginConfigs['user_notes']) && $pluginConfigs['user_notes']['status'] === 1)
            @if(getUserPlanData('user_notes') == true)
            @php
            $notes = \App\Includes\UserNotes\Models\Notes::where('user_id', Auth::id())->take(3)->get();
            @endphp

        @if($notes->isNotEmpty())
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-chart-one">
                    <div class="widget-heading">
                        <div>
                            <h5 class="">{{ __('My Notes') }}</h5>
                            <span class="w-numeric-title">{{ __('Overview of Notes') }}</span>
                        </div>
                    </div>
                    <div class="widget-content">
                        @foreach($notes->sortByDesc('updated_at')->take(3) as $note)
                            <div class="mb-2 border-bottom border-light pb-2">
                                <span class="text-primary font-15">{{ $note->title }}</span>
                                <span class="float-right text-success-teal font-12">
                                    <i class="fa fa-clock"></i> {{ $note->updated_at->diffForHumans() }}
                                </span>
                                <h6 class="text-muted font-12 mt-1 mb-2">
                                    {{ __('Started on : ') }}{{ $note->created_at->format('j F') }}
                                </h6>
                                <p class="font-12 mb-0 text-muted">
                                    {{ \Illuminate\Support\Str::limit($note->description, 70) }}
                                </p>
                            </div>
                        @endforeach
                        <a href="{{ route('user.notes.index') }}" class="btn btn-block btn-secondary">
                            {{ __('Create new Notes') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <p>{{ __('No notes found.') }}</p>
        @endif
    @endif
@endif

            <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-one">
                    <div class="widget-heading">
                        <h5 class=""> {{ __('Top Conversations and Leads') }}</h5>
                    </div>
                    <div class="widget-content">
                        @foreach ($chatMessages as $key => $message)
                        <div class="weekly-bestsellers">
                            <div class="t-item">
                                <div class="t-company-name">
                                    <div class="t-icon">
                                        <div class="image-container">
                                            <img class="rounded-circle avatar-xs" src="{{asset('assets/img/whatsapp.png')}}" alt="profile">
                                        </div>
                                    </div>
                                    <div class="t-name">
                                        <h4> {{ $message->phone_number }}</h4>
                                        <p class="meta-date" style="color:
                                            @if($message->follow_up == 1) black
                                            @elseif($message->follow_up == 2) yellow
                                            @elseif($message->follow_up == 3) blue
                                            @elseif($message->follow_up == 4) red
                                            @elseif($message->follow_up == 5) green
                                            @else black
                                            @endif">
                                                @if($message->follow_up == 1) New Lead
                                                @elseif($message->follow_up == 2) Pending Lead
                                                @elseif($message->follow_up == 3) Positive Lead
                                                @elseif($message->follow_up == 4) Negative Lead
                                                @elseif($message->follow_up == 5) Converted Lead
                                                @else Add Label
                                                @endif
                                            </p>

                                    </div>
                                </div>
                                <div class="t-rate rate-inc">
                                    <p><span> {{ $key+1 }}</span> <i class="fa fa-arrow-up"></i></p>
                                </div>
                            </div>
                        </div>
                       @endforeach
                       @if(getUserPlanData('mechanism') ==1)
                       <a href="/user/device" class="btn btn-block bg-green text-white"> {{ __('Start Conversation') }}</a>
                       @else
                        <a href="{{route('user.cloudapi.index') }}" class="btn btn-block bg-green text-white"> {{ __('Start Conversation') }}</a>
                        @endif
                    </div>
                </div>
            </div>
            @if(getUserPlanData('mechanism') ==1)
            <p></p>
            @else
            @if(isset($pluginConfigs['welcome_qr']) && $pluginConfigs['welcome_qr']['status'] === 1)
            @if(getUserPlanData('welcome_qr') == true)
            <script>
            window.phone = @json($phone);
            </script>
            @dynamicInclude('WelcomeQR', 'qrcode')
        @endif
    @endif
    @endif
        </div>
    </div>
    
    
            
    <!-- Main Body Ends -->
<input type="hidden" id="static-data" value="{{ route('user.dashboard.static') }}"> 
<input type="hidden" id="base_url" value="{{ url('/') }}"> 
@endsection
@push('js')
<script src="{{ asset('assets/vendor/chart.js/dist/chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/canvas-confetti/confetti.browser.min.js') }}"></script>
@endpush
