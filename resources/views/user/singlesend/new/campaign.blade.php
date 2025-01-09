<div class="campign_elements">
<div id="form-group-template_id" class="form-group focused">
    <label class="form-control-label">Template</label><br> 
    <select class="form-control" required="" name="template_text" id="template_text">
        <option value="none">{{ __('No Templated Selected')}}</option>
            @foreach($templates as $template)
            <option value="{{ $template->title }}">{{ $template->title }}</option>
    	    @endforeach
    </select>
    </div>
    
    <div id="form-group-contact_id" class="form-group focused">
        <label class="form-control-label">Message To</label><br> 
        <input id="phone" type="number" class="form-control" name="phone" placeholder="{{ __('Phone number with country code') }}" value="" required="" autofocus="" minlength="10" maxlength="15" />
            </div>


</div>