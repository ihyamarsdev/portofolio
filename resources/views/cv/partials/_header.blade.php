{{-- CV Header Partial
     Variables: $profile, $header_class, $name_class, $title_class, $contact_class
 --}}
<div class="{{ $header_class ?? 'header' }}">
    <div class="{{ $name_class ?? 'name' }}">{{ $profile->name ?? 'YOUR NAME' }}</div>
    <div class="{{ $title_class ?? 'title' }}">{{ $profile->title ?? 'Professional Title' }}</div>
    <div class="{{ $contact_class ?? 'contact-info' }}">
        <span>{{ $profile->email ?? 'email@example.com' }}</span>
        @if($profile->phone ?? false)<span>{{ $profile->phone }}</span>@endif
        @if($profile->location ?? false)<span>{{ $profile->location }}</span>@endif
    </div>
    @if(isset($profile->social_links) && count($profile->social_links) > 0)
        @include('cv.partials._social_links', ['social_links' => $profile->social_links, 'contact_class' => $contact_class ?? null])
    @endif
</div>
