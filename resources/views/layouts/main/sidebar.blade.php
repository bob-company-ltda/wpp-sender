@if(Request::is('admin/*'))

 @include('layouts.main.admin')

@elseif(Request::is('superadmin/*'))

 @include('layouts.main.superadmin')

@else

 @include('layouts.main.user')

@endif