{{-- Skills Section Partial
     Variables: $skills, $section_class, $heading_class, $container_class
              $category_class, $category_title_class, $skill_item_class
 --}}
@php
    $groupedSkills = $skills->groupBy(fn($skill) => $skill->category?->name ?? 'General');
@endphp
<div class="{{ $section_class ?? 'section' }}">
    <h2 class="{{ $heading_class ?? '' }}">Skills</h2>
    <div class="{{ $container_class ?? 'skills-container' }}">
        @foreach($groupedSkills as $categoryName => $categorySkills)
            <div class="{{ $category_class ?? 'skill-category' }}">
                <strong class="{{ $category_title_class ?? 'category-title' }}">{{ $categoryName }}</strong>
                <div class="skill-items">
                    @foreach($categorySkills as $skill)
                        <div class="{{ $skill_item_class ?? 'skill-item' }}">
                            <span class="skill-name">{{ $skill->name }}</span>
                            @if($skill->years_experience > 0)
                                @php
                                    $years = floatval($skill->years_experience);
                                    $displayYears = fmod($years, 1) > 0 ? number_format($years, 1) : intval($years);
                                    $yearText = $years == 1 ? 'yr' : 'yrs';
                                @endphp
                                <span class="skill-years">{{ $displayYears }} {{ $yearText }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
