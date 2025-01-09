@extends('layouts.main.app')
@section('head')
@php
    $buttons = [];
    if (getUserPlanData('cloudapi_limit') == true) {
        $buttons[] = [
            'name' => '<i class="fa fa-plus"></i>&nbsp' . __('Create CloudApi'),
            'url' => route('user.cloudapi.create'),
        ];
    }
@endphp

@include('layouts.main.headersection', [
    'title' => __('CloudApi'),
    'buttons' => $buttons
])

@endsection
@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
 @if(count($cloudapis ?? []) > 0)
  @foreach($cloudapis ?? [] as $cloudapi)
 <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-three widget-best-seller">
                    <div class="widget-heading mb-0"></div>
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fab fa-whatsapp font-45 text-success"></i>
                        </div>
                        <h3 class="badge badge-default">@if(!empty($cloudapi->phone))
                        <a href="{{ route('user.cloudapi.hook',$cloudapi->uuid) }}">
                        {{ $cloudapi->phone  }}
                        </a>
                        @endif</h3>
                        <p class="strong"> {{ $cloudapi->name }}</p>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-centered table-nowrap mb-2">
                            <tbody>
                            <tr>
                                <td style="width: 30%;"><p class="mb-0"> {{ __('Total Messages') }}</p></td>
                                <td style="width: 25%;"><h5 class="mb-0"> {{ number_format($cloudapi->smstransaction_count) }}</h5></td>
                            </tr>
                            <tr>
                                <td><p class="mb-0"> {{ __('Status') }}</p></td>
                                <td><h6 class="mb-0 badge badge-success text-white"> {{ $cloudapi->status == 1 ? __('Active') : __('Inactive')  }} </h6></td>
                                
                            </tr>
                            <tr>
                                <td><p class="mb-0"> {{__('Graph API')}}</p></td>
                                <td><h5 class="mb-0"> {{__('21.0')}}</h5></td>
                                
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget dashboard-table">
                            <div class="widget-content">
                                <a href="{{ route('user.cloudapi.hook',$cloudapi->uuid) }}" class="box">
                                    <div class="box-body">
                                                <span class="text-warning font-45">
                                                    <i class="fa fa-chart-bar"></i>
                                                </span>
                                        <div class="text-warning stronger font-17 mb-2"> {{ __('Meta Webhook') }}</div>
                                        <div class="text-dark"> {{ __('Configure it from here') }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="widget dashboard-table">
                            <div class="widget-content">
                                <a href="{{ url('/user/cloudapi/chats/'.$cloudapi->uuid) }}" class="box">
                                    <div class="box-body">
                                                <span class="text-green font-45">
                                                    <i class="fa fa-comments"></i>
                                                </span>
                                        <div class="text-green stronger font-17 mb-2"> {{ __('Messages') }}</div>
                                        <div class="text-dark"> {{ __('Start the Conversation') }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="widget dashboard-table">
                            <div class="widget-content">
                                <a href="{{ route('user.cloudapi.show',$cloudapi->uuid) }}" class="box">
                                    <div class="box-body">
                                                <span class="text-info font-45">
                                                    <i class="fa fa-globe-americas"></i>
                                                </span>
                                        <div class="text-info stronger font-17 mb-2"> {{ __('Logs') }}</div>
                                        <div class="text-dark"> {{ __('View Message Logs') }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget widget-chart-two bg-green">
                            <i class="fab fa-whatsapp top-right-big" style="font-size: 110px; position: absolute; opacity: 0.3; right: 0px; padding:10px;"></i>
                            <div class="widget-heading pt-4 mb-0">
                                <h5 class="text-white pt-5"> {{ __('Profile & Keys') }}</h5>
                            </div>
                            <div class="widget-content mt-4 pr-3 pl-3">
                                <p class="py-15 font-15 text-white mb-4"> {{ __('Edit Your WhatsApp Profile') }} <br> {{ __('and Account Token') }}<br> {{ __('and Keys') }}
                                </p>
                                <a href="{{ route('user.cloudapi.edit',$cloudapi->uuid) }}" class="btn btn-sm btn-white text-primary"> {{ __('Edit WhatsApp') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="widget widget-chart-two bg-danger">
                            <i class="fa fa-ban top-right-big" style="font-size: 110px; position: absolute; opacity: 0.3; right: 0px; color:#fff; padding:10px;"></i>
                            <div class="widget-heading pt-4 mb-0">
                                <h5 class="text-white pt-5"> {{ __('Remove  API') }}</h5>
                            </div>
                            <div class="widget-content mt-4 pr-3 pl-3">
                                <p class="py-15 font-15 text-white mb-4"> {{ __('Do You really') }} <br> {{ __('Want to remove') }}<br> {{ __('Your WhatsApp API ?') }}
                                </p>
                                <a href="javascript:void(0)" data-action="{{ route('user.cloudapi.destroy',$cloudapi->uuid) }}" class="btn btn-sm btn-white text-danger delete-confirm"> {{ __('Remove Now') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 @endforeach           
@else
<div class="col-md-12 mt-4 mb-4">
                        <div class="widget widget-chart-one bg-gradient-secondary">
                            <div class="widget-heading">
                                <h5 class="text-white"> {{ __('Alert') }}</h5>
                                <div class="dot-right-icon"><i class="las la-ellipsis-h"></i></div>
                            </div>
                            <div class="widget-content">
                                <h6 class="text-white"> {{ __('Opps There Is No CloudApi Found....') }}</h6>
                                <span class="text-white font-13"> {{ __('Configure WhatsApp API ') }}  </span>
                            </div>
                        </div>
                    </div>
            
                    @endif
        </div>
    </div>

<input type="hidden" id="base_url" value="{{ url('/') }}">
@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/cloudapi.js') }}"></script>
@endpush
