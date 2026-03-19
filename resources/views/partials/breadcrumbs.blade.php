@php
    $segments = Request::segments();
    $url = '';
@endphp

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}"><span class="fas fa-home me-1"></span>Home</a></li>
        @foreach($segments as $segment)
            @php $url .= '/' . $segment; @endphp
            @if($segment !== 'dashboard') {{-- Skip dashboard as it's our "Home" --}}
                @if(!$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{ url($url) }}">{{ Str::title(str_replace('-', ' ', $segment)) }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::title(str_replace('-', ' ', $segment)) }}
                    </li>
                @endif
            @endif
        @endforeach
    </ol>
</nav>
