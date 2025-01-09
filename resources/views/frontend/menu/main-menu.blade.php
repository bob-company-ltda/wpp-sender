@if(!empty($data))
@foreach ($data['data'] ?? []  as $row)
@if (isset($row->children))
<li class="nav-item has_dropdown">
    <a class="nav-link" href="#">{{ $row->text }}</a>
    	<span class="drp_btn"><i class="icofont-rounded-down"></i></span>
            <div class="sub_menu">
                <ul>
				@foreach($row->children as $childrens)
			 	@include('frontend.menu.submenu', ['childrens' => $childrens])
			    @endforeach
                </ul>
            </div>
</li>
@endif
 
 <li class="item-hasChildern nav-item">
    <div class="nameMenujwn {{ __($row->text ?? '') }} default">
        <a class="nav-link" href="{{ url($row->href ?? '') }}">{{ __($row->text ?? '') }}</a>
    </div>
</li>
@endforeach
@endif

<li class="nav-item none">
<a class="nav-link dark_btn" href="{{ !Auth::check() ? url('/pricing') : url('/login') }}">{{ !Auth::check() ? __('Get Started') : __('Dashboard') }}</a>
</li>