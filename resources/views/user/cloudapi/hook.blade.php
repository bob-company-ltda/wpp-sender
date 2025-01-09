@extends('layouts.main.app')

@section('head')
@include('layouts.main.headersection',['buttons'=>[
    [
        'name'=>'Back',
        'url'=> route('user.cloudapi.index'),
    ]
]])
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cloudapi.css') }}">
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-8">
        <div class="card card-neutral">
            <div class="card-header">
                <h4>{{ __('Configure The Webhook in Meta ') }}</h4>
                <div class="card-header-action none loggout_area">
                    
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center hook-area">
                    <div class="justify-content-center">
                        &nbsp&nbsp
                        <div class="spinner-grow text-primary" role="status">
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <br>
                        <p><strong>{{ __('Callback Url Loading.....') }}</strong></p>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <label for="webhookInput">{{ __('Callback Webhook URL') }}</label>
                    <div class="input-group">
                        <input type="text" id="webhookInput" class="form-control" value="{{ url('send-webhook/' . $cloudapi->uuid) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" onclick="copyToClipboard()">{{ __('Copy') }}</button>
                        </div>
                    </div>
                </div>
                <div id="connectionStatus" class="text-center mt-3"></div>
            </div>
            <div class="card-footer">
                <div class="alert bg-gradient-red server_disconnect none text-white" role="alert">
                    {{ __('Opps! Server Disconnected 😭') }}
                </div>

                <div class="alert bg-gradient-green logged-alert none text-white" role="alert">
                    {{ __('WhatsApp API Connected ') }} <img src="{{ asset('uploads/firework.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="card card-neutral none helper-box">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-2 mt-2">
                        <a href="{{ url('/user/cloudapi/chats/'.$cloudapi->uuid) }}" class="btn btn-neutral col-12">
                            <i class="fi fi-rs-paper-plane"></i>&nbsp {{ __('My Chat list') }}
                        </a>
                    </div>
                    <div class="col-sm-6 mt-3">
                        <a href="{{ url('/user/sent-text-message') }}" class="btn btn-neutral col-12">
                            <i class="fi fi-rs-paper-plane"></i>&nbsp {{ __('Send a message') }}
                        </a>
                    </div>
                    <div class="col-sm-6 mt-3">
                        <a href="{{ url('/user/bulk-message/create') }}" class="btn btn-neutral col-12">
                            <i class="fi fi-rs-rocket-lunch"></i>&nbsp {{ __('Send bulk message') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('How To Do?') }}</h4>
                <div class="card-header-action">
                    <a href="#" class="btn btn-sm btn-neutral">
                        <i class="fas fa-lightbulb"></i>&nbsp{{ __('Watch Now') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <img src="{{ asset('uploads/steps.png') }}" class="w-100">
            </div>
            <div class="card-footer">
                <div class="activities">
                    <div class="activity">
                        <div class="activity-icon bg-green text-white shadow-green">
                            <i class="ni ni-mobile-button text-white"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">{{ __('Step 1') }}</span>
                                <span class="bullet"></span>
                            </div>
                            <p>{{ __('Open Your WhatsApp Business Meta Dashboard') }}</p>
                        </div>
                    </div>
                    <div class="activity">
                        <div class="activity-icon  bg-green text-white shadow-green">
                            <i class="ni ni-active-40 text-white"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">{{ __('Step 2') }}</span>
                                <span class="bullet"></span>
                            </div>
                            <p>{{ __('Tap Configure Under WhatsApp Option and select Edit') }}</p>
                        </div>
                    </div>
                    <div class="activity">
                        <div class="activity-icon  bg-green text-white shadow-green">
                            <i class="ni ni-active-40 text-white"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">{{ __('Step 3') }}</span>
                                <span class="bullet"></span>
                            </div>
                            <p>{{ __('Enter Callback URL(Your Webhook URL) and Verify Token using 123456') }}</p>
                        </div>
                    </div>
                    <div class="activity">
                        <div class="activity-icon bg-green text-white shadow-green">
                            <i class="fa fa-qrcode  text-white"></i>
                        </div>
                        <div class="activity-detail">
                            <div class="mb-2">
                                <span class="text-job text-primary">{{ __('Step 4') }}</span>
                                <span class="bullet"></span>
                            </div>
                            <p>{{ __('After Verification, Goto Manage Section and Subscribe Messages atleast.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="api_status" value="{{ $cloudapi->status }}">
<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="cloudapi_id" value="{{ $cloudapi->uuid }}">

@endsection

@push('js')
<script src="{{ asset('assets/js/pages/user/api.js') }}"></script>
<script>
    function copyToClipboard() {
        var webhookInput = document.getElementById("webhookInput");
        webhookInput.select();
        webhookInput.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Webhook URL copied to clipboard!");
    }
</script>
@endpush
