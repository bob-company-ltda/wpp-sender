@extends('layouts.main.app')
@section('head')
@include('layouts.main.headersection',['buttons'=>[
	[
		'name'=>'<i class="fa fa-backward"></i>&nbsp'. __('Back To Templates'),
		'url'=>url('/user/template'),
		'is_button'=>false
	],	
	[
		'name'=>'<i class="fa fa-backward"></i>&nbsp'. __('Back To Contacts'),
		'url'=>url('/user/contact'),
		'is_button'=>false
	]
]])
@endsection
@section('content')
<div class="row justify-content-center">
   <div class="col-12">
      <div class="row d-flex justify-content-between flex-wrap">
         <div class="col-sm-4">
            <div class="card card-stats">
               <div class="card-body">
                  <div class="row">
                     <div class="col">
                        <span class="h2 font-weight-bold mb-0 total-transfers total_sent">
                        0
                        </span>
                     </div>
                     <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                           <i class="fi fi-rs-rocket-lunch mt-2"></i>
                        </div>
                     </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                  </p>
                  <h5 class="card-title  text-muted mb-0">{{ __('Total Queued') }}</h5>
                  <p></p>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="card card-stats">
               <div class="card-body">
                  <div class="row">
                     <div class="col">
                        <span class="h2 font-weight-bold mb-0 total_records" id="total_records">{{ number_format(count($contacts)) }}</span>
                     </div>
                     <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                           <i class="fi  fi-rs-address-book mt-2"></i>
                        </div>
                     </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                  </p>
                  <h5 class="card-title  text-muted mb-0">{{ __('Total Contacts') }}</h5>
                  <p></p>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="card card-stats">
               <div class="card-body">
                  <div class="row">
                     <div class="col">
                        <span class="h2 font-weight-bold mb-0 completed-transfers total-faild">
                        0
                        </span>
                     </div>
                     <div class="col-auto">
                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                           <i class="fi fi-rs-circle-cross mt-2"></i>
                        </div>
                     </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                  </p>
                  <h5 class="card-title  text-muted mb-0">{{ __('Total Failed') }}</h5>
                  <p></p>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row mb-3">
               <div class="col-12">
                  <div class="float-left">
                     <h4><span class="total_sent">0</span>/<span class="total_records">{{ count($contacts) }}</span></h4>
                  </div>
                  <div class="float-right">
                     <button class="btn  btn-neutral btn-sm  send_now" type="button"><i class="ni ni-send"></i>&nbsp&nbsp{{ __('Send To All') }}</button>
                  </div>
                  <div class="custom-control custom-checkbox" style="padding-left: 4.75rem;">
    <input type="checkbox" class="custom-control-input group-input" id="save-to-chat-master" name="save-to-chat-master" value="true" data-class="role-save-to-chat-management-checkbox">
    <label class="custom-control-label" for="save-to-chat-master">Save in Live Chat</label>
    <small style="font-size: 60%; color: red;">(Caution: Large datasets may impact server performance.)</small>
</div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12 table-responsive">
                  <table class="table col-12">
                     <thead>
                        <tr>
                            <th class="col-3">#</th>
                           <th class="col-3">{{ __('Receiver (To)') }}</th>
                           <th class="col-3">{{ __('CloudApi (From)') }}</th>
                           <th class="col-3">{{ __('Template') }}</th>
                           <th class="col-2">{{ __('Status') }}</th>
                           <th class="col-1">{{ __('Actions') }}</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($contacts as $key => $contact)
                        <tr class="contact_form_row{{ $key }}">
                           <form action="{{ url('user/sent-message-with-template') }}" id="form-ar-{{ $key }}" method="POST" class="bulk_form form-{{  $key }}" data-key="{{ $key }}">
                              @csrf
                              <input type="hidden" name="contact" value="{{ $contact->id }}">
                              <input type="hidden" name="savetochat" id="savetochat-{{ $key }}" value="">
                              
                              @php
                $replacedBody = urldecode($body);
                $replacedHeaderParm = $headerParm;

                
                for ($i = 1; $i <= 7; $i++) {
                    $paramPlaceholder = "{param$i}";

                    
                    if (strpos($replacedBody, $paramPlaceholder) !== false) {
                        $replacedBody = str_replace($paramPlaceholder, $contact->{"param$i"}, $replacedBody);
                    }

                    
                    if (strpos($replacedHeaderParm, $paramPlaceholder) !== false) {
                        $replacedHeaderParm = str_replace($paramPlaceholder, $contact->{"param$i"}, $replacedHeaderParm);
                    }
                }
            @endphp
                              
                              <input type="hidden" name="headerParam" value="{{ $replacedHeaderParm }}">
                             <input type="hidden" name="body" value="{{ $replacedBody }}">
                             <td><div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input group-input" id="save-to-chat-{{ $key }}" name="save-to-chat" value="true" data-class="role-save-to-chat-management-checkbox">
                                <label class="custom-control-label" for="save-to-chat-{{ $key }}"></label></div></td>
                              <td>{{ $contact->name.' - '.$contact->phone }}</td>
                              <td>
                                 <select class="form-control" name="cloudapi">
                                 @foreach($cloudapis as $row)
                                 <option value="{{ $row->id }}" {{ $row->id ==  $cloudapi->id ? 'selected' : ''}}>{{ $row->name. ' - '. $row->phone }}</option>
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select class="form-control" name="template">
                                 @foreach($templates as $template_row)
                                 <option value="{{ $template_row->id }}" {{ $template_row->id ==  $template->id ? 'selected' : ''}}>{{ $template_row->title }}</option>
                                 @endforeach
                                 </select>
                              </td>
                              <td>
                                 <span class="badge badge-warning badge_{{ $key }} sendable">{{ __('Waiting') }}</span>
                              </td>
                              <td>
                                 <div class="btn-group mb-2 float-right">
                                    <button class="btn btn-neutral btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Action') }}
                                    </button>
                                    <div class="dropdown-menu">
                                       <a class="dropdown-item has-icon send-message submit-button" data-form=".form-{{  $key }}" href="javascript:void(0)"><i class="ni ni-send"></i>{{ __('Send Now') }}</a>
                                       <a class="dropdown-item has-icon delete-form" href="javascript:void(0)" data-action=".contact_form_row{{ $key }}"><i class="fas fa-trash"></i>{{ __('Remove') }}</a>
                                    </div>
                                 </div>
                              </td>
                           </form>
                           <script>
            document.addEventListener('DOMContentLoaded', function() {
                var masterCheckbox = document.getElementById('save-to-chat-master');
                var userNotesCheckbox = document.getElementById('save-to-chat-{{ $key }}');
                var savetochatInput = document.getElementById('savetochat-{{ $key }}');

                masterCheckbox.addEventListener('change', function() {
                    // Set the checked attribute based on the checkbox state
                    userNotesCheckbox.checked = this.checked;
                    userNotesCheckbox.value = this.checked ? 'true' : 'false';

                    // Update the value of the hidden input
                    savetochatInput.value = this.checked ? 'true' : 'false';

                    // Output the value to the console
                    console.log('User Notes Value:', this.checked);
                });

                userNotesCheckbox.addEventListener('change', function() {
                    // Set the checked attribute based on the checkbox state
                    userNotesCheckbox.checked = this.checked;
                    userNotesCheckbox.value = this.checked ? 'true' : 'false';

                    // Update the value of the hidden input
                    savetochatInput.value = this.checked ? 'true' : 'false';

                    // Output the value to the console
                    console.log('User Notes Value:', this.checked);
                });
            });
        </script>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/pages/user/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/pages/user/template-bulk.js') }}"></script>
@endpush