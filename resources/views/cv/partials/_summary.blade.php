 {{-- Professional Summary Partial
     Variables: $biography, $section_class, $heading_class, $content_class
 --}}
@if($biography ?? false)
    <div class="{{ $section_class ?? 'section' }}">
        <h2 class="{{ $heading_class ?? '' }}">Professional Summary</h2>
        <div class="{{ $content_class ?? 'summary' }}">
            {{ strip_tags($biography) }}
        </div>
    </div>
@endif
