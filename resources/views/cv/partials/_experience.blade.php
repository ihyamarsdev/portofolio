{{-- Experience Section Partial
     Variables: $experiences, $section_class, $heading_class, $item_class
              $position_class, $company_class, $location_class, $date_class
              $description_class, $list_class
 --}}
<div class="{{ $section_class ?? 'section' }}">
    <h2 class="{{ $heading_class ?? '' }}">Professional Experience</h2>
    @foreach($experiences as $experience)
        <div class="{{ $item_class ?? 'item' }}">
            <div class="item-header">
                <span class="{{ $position_class ?? 'position' }}">{{ $experience->position }}</span>
                <span class="{{ $date_class ?? 'date' }}">
                    {{ $experience->start_date->format('M Y') }} –
                    {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                </span>
            </div>
            <div class="company-location">
                <span class="{{ $company_class ?? 'company' }}">{{ $experience->company }}</span>,
                <span class="{{ $location_class ?? 'location' }}">{{ $experience->location }}</span>
            </div>
            @if($experience->description)
                <div class="{{ $description_class ?? 'description' }}">
                    {!! nl2br(e(strip_tags($experience->description))) !!}
                </div>
            @endif
            @if($experience->achievements && count($experience->achievements) > 0)
                <ul class="{{ $list_class ?? '' }}">
                    @foreach($experience->achievements as $achievement)
                        <li>{{ $achievement }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach
</div>
