{{-- Social Links Partial
     Variables: $social_links, $contact_class, $link_class, $separator
 --}}
@if(isset($social_links) && count($social_links) > 0)
    <div class="{{ $contact_class ?? 'contact-info' }}">
        @foreach($social_links as $platform => $url)
            <span>
                <a href="{{ $url }}" class="{{ $link_class ?? 'contact-link' }}" target="_blank" rel="noopener noreferrer">
                    {{ ucfirst($platform) }}
                </a>
            </span>
            @if(!$loop->last){{ $separator ?? ' | ' }}@endif
        @endforeach
    </div>
@endif
