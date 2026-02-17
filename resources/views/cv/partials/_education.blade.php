{{-- Education Section Partial
     Variables: $educations, $section_class, $heading_class, $item_class
              $institution_class, $degree_class, $date_class, $meta_class
 --}}
<div class="{{ $section_class ?? 'section' }}">
    <h2 class="{{ $heading_class ?? '' }}">Education</h2>
    @foreach($educations as $education)
        <div class="{{ $item_class ?? 'item' }}">
            <div class="item-header">
                <span class="{{ $institution_class ?? 'institution' }}">{{ $education->institution }}</span>
                <span class="{{ $date_class ?? 'date' }}">
                    {{ $education->start_date->format('Y') }} –
                    {{ $education->end_date ? $education->end_date->format('Y') : 'Present' }}
                </span>
            </div>
            <div class="{{ $degree_class ?? 'degree' }}">
                {{ $education->degree }} in {{ $education->field_of_study }}
            </div>
            @if($education->grade || $education->activities)
                <div class="{{ $meta_class ?? 'meta-info' }}">
                    @if($education->grade)
                        <span class="meta-label">GPA/Grade:</span> {{ $education->grade }}
                    @endif
                    @if($education->grade && $education->activities)
                        <span style="margin: 0 8px;">•</span>
                    @endif
                    @if($education->activities)
                        <span class="meta-label">Activities:</span> {{ $education->activities }}
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>
