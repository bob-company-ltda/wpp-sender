@if ($childrens)
<li><a href="{{ url($childrens->href) }}  @if(!empty($childrens->target)) target="{{ $childrens->target }}" @endif">{{ $childrens->text }}</a></li>
@endif