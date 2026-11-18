{{-- Skills Section Partial — grouped, ATS-safe linear rendering.
     Renders "Category: Skill A, Skill B, Skill C" lines instead of noisy badge
     clouds or progress bars. No SVG, no percentages, linear DOM order.

     Variables: $skills, $heading (default 'Skills'),
                $section_class, $heading_class, $line_class,
                $label_class, $years_class
--}}
@php
    $groupedSkills = $skills->groupBy(
        fn ($skill) => $skill->category_name
            ?? $skill->category?->name
            ?? (is_string($skill->category ?? null) ? $skill->category : 'General')
    );
@endphp
<div class="{{ $section_class ?? 'section' }}">
    @if(($heading ?? 'Skills') !== '')
        <h2 class="{{ $heading_class ?? 'section-title' }}">{{ $heading ?? 'Skills' }}</h2>
    @endif
    @foreach($groupedSkills as $categoryName => $categorySkills)
        <p class="{{ $line_class ?? 'skill-line' }}">
            <span class="{{ $label_class ?? 'skill-line-label' }}">{{ $categoryName }}:</span>
            @foreach($categorySkills as $skill)
                <span>{{ $skill->name }}@if($skill->years_experience > 0)@php
                    $years = floatval($skill->years_experience);
                    $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                @endphp <span class="{{ $years_class ?? 'skill-years' }}">({{ $displayYears }}y)</span>@endif</span>@if(!$loop->last), @endif
            @endforeach
        </p>
    @endforeach
</div>
