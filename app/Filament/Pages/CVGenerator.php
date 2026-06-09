<?php

namespace App\Filament\Pages;

use App\Ai\Agents\ComprehensiveCVAnalysisAgent;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\CVAnalysis;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Skill;
use App\Services\CVGeneratorService;
use BackedEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Throwable;

class CVGenerator extends Page
{
    use WithFileUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public function __construct()
    {
        $this->cvService = app(CVGeneratorService::class);
    }

    protected CVGeneratorService $cvService;

    protected static ?string $title = 'CV Builder & AI Analyzer';

    protected static ?string $slug = 'cv-generator';

    public static function getNavigationGroup(): ?string
    {
        return 'CV Management';
    }

    protected string $view = 'filament.pages.cv-generator';

    // Navigation and Tab state
    #[Url(as: 'tab')]
    public string $activeTab = 'overview'; // Options: overview, profile, experience, education, skills, certificates, aimatch, preview

    // Profile Settings state
    public ?string $profile_name = '';

    public ?string $profile_title = '';

    public ?string $profile_biography = '';

    public ?string $profile_email = '';

    public ?string $profile_phone = '';

    public ?string $profile_location = '';

    public ?string $profile_github_url = '';

    public ?string $profile_linkedin_url = '';

    public ?string $profile_twitter_url = '';

    public ?string $profile_website_url = '';

    public $profile_avatar = null; // Can be string path or TemporaryUploadedFile

    public ?string $profile_avatar_path = '';

    // Experience CRUD state
    public $experiences = [];

    public bool $showExperienceModal = false;

    public ?int $editingExperienceId = null;

    public string $exp_company = '';

    public string $exp_position = '';

    public string $exp_employment_type = 'Full-time';

    public string $exp_location = '';

    public ?string $exp_start_date = '';

    public ?string $exp_end_date = '';

    public ?string $exp_description = '';

    public array $exp_achievements = [];

    public int $exp_order = 0;

    public string $new_achievement = '';

    public array $selected_exp_skills = [];

    public $all_skills_list = [];

    // Education CRUD state
    public $educations = [];

    public bool $showEducationModal = false;

    public ?int $editingEducationId = null;

    public string $edu_institution = '';

    public string $edu_degree = '';

    public string $edu_field_of_study = '';

    public ?string $edu_start_date = '';

    public ?string $edu_end_date = '';

    public ?string $edu_grade = '';

    public ?string $edu_activities = '';

    public int $edu_order = 0;

    // Skills CRUD state
    public $skills = [];

    public bool $showSkillModal = false;

    public ?int $editingSkillId = null;

    public string $skill_name = '';

    public ?int $skill_category_id = null;

    public int $skill_proficiency = 80;

    public float $skill_years_experience = 0.0;

    public $categories = [];

    // Certificates CRUD state
    public $certificates = [];

    public bool $showCertificateModal = false;

    public ?int $editingCertificateId = null;

    public string $cert_name = '';

    public string $cert_issuer = '';

    public ?string $cert_credential_id = '';

    public ?string $cert_credential_url = '';

    public ?string $cert_issue_date = '';

    public ?string $cert_expiry_date = '';

    public bool $cert_is_active = true;

    // AI Analysis & Preview state
    public ?array $analysisResult = null;

    public bool $isAnalyzing = false;

    public string $selectedTemplate = 'ats';

    public ?string $jobDescription = null;

    public string $targetRole = 'Devops Engineer';

    public ?CVAnalysis $currentAnalysis = null;

    public $history = [];

    public $allAnalyses = [];

    public bool $showAnalysisDetailModal = false;

    public ?array $selectedAnalysisDetail = null;

    public function mount(): void
    {
        $this->loadAllData();
    }

    public function switchTab(string $tab): void
    {
        if ($tab === 'analyses') {
            $tab = 'overview';
        }

        $this->activeTab = $tab;
    }

    public function updatedTargetRole(string $value): void
    {
        $profile = Profile::where('is_primary', true)->first() ?? Profile::first();

        if ($profile) {
            $analysis = $profile->cvAnalyses()
                ->where('job_title_targeted', $value)
                ->latest()
                ->first();

            if ($analysis) {
                $this->currentAnalysis = $analysis;
                $this->analysisResult = [
                    'ats_score' => $analysis->ats_score,
                    'format_score' => $analysis->format_score,
                    'about_me_score' => $analysis->about_me_score ?? 0,
                    'impact_score' => $analysis->impact_score ?? 0,
                    'role_alignment_score' => $analysis->role_alignment_score ?? 0,
                    'security_score' => $analysis->security_score ?? 0,
                    'reliability_score' => $analysis->reliability_score ?? 0,
                    'cloud_score' => $analysis->cloud_score ?? 0,
                    'keywords' => [
                        'found' => $analysis->keywords_found ?? [],
                        'missing' => $analysis->keywords_missing ?? [],
                        'weak_evidence' => $analysis->weak_evidence ?? [],
                    ],
                    'suggestions' => $analysis->suggestions ?? [],
                    'strengths' => $analysis->strengths ?? [],
                    'critical_gaps' => $analysis->critical_gaps ?? [],
                    'missing_metrics' => $analysis->missing_metrics ?? [],
                    'rewrite_priorities' => $analysis->rewrite_priorities ?? [],
                    'experience_review' => $analysis->experience_review ?? [],
                    'industry_match' => $analysis->industry_match ?? [],
                    'summary' => $analysis->summary,
                    'job_match_score' => $analysis->job_match_score,
                    'job_title_targeted' => $analysis->job_title_targeted,
                    'target_seniority' => $analysis->target_seniority,
                ];
                if ($analysis->summary) {
                    $this->jobDescription = 'Target Role: '.($analysis->job_title_targeted ?? 'Unknown')."\nSummary: ".$analysis->summary;
                } else {
                    $this->jobDescription = '';
                }
            } else {
                $this->currentAnalysis = null;
                $this->analysisResult = null;
                $this->jobDescription = '';
            }
        }
    }

    public function loadAllData(): void
    {
        $profile = Profile::where('is_primary', true)->first() ?? Profile::first();

        if ($profile) {
            // Load profile properties
            $this->profile_name = $profile->name;
            $this->profile_title = $profile->title;
            $this->profile_biography = $profile->biography;
            $this->profile_email = $profile->email;
            $this->profile_phone = $profile->phone;
            $this->profile_location = $profile->location;
            $this->profile_github_url = $profile->github_url;
            $this->profile_linkedin_url = $profile->linkedin_url;
            $this->profile_twitter_url = $profile->twitter_url;
            $this->profile_website_url = $profile->website_url;
            $this->profile_avatar_path = $profile->avatar;
            $this->selectedTemplate = $profile->cv_template ?? 'ats';

            // Load analysis info
            $this->currentAnalysis = $profile->latestAnalysis;
            $this->history = $profile->cvAnalyses()->latest()->take(10)->get();

            if ($this->currentAnalysis) {
                if ($this->currentAnalysis->job_title_targeted && in_array($this->currentAnalysis->job_title_targeted, ['Devops Engineer', 'Cloud Engineer', 'Site Reliability Engineer'])) {
                    $this->targetRole = $this->currentAnalysis->job_title_targeted;
                }

                $this->analysisResult = [
                    'ats_score' => $this->currentAnalysis->ats_score,
                    'format_score' => $this->currentAnalysis->format_score,
                    'about_me_score' => $this->currentAnalysis->about_me_score ?? 0,
                    'impact_score' => $this->currentAnalysis->impact_score ?? 0,
                    'role_alignment_score' => $this->currentAnalysis->role_alignment_score ?? 0,
                    'security_score' => $this->currentAnalysis->security_score ?? 0,
                    'reliability_score' => $this->currentAnalysis->reliability_score ?? 0,
                    'cloud_score' => $this->currentAnalysis->cloud_score ?? 0,
                    'keywords' => [
                        'found' => $this->currentAnalysis->keywords_found ?? [],
                        'missing' => $this->currentAnalysis->keywords_missing ?? [],
                        'weak_evidence' => $this->currentAnalysis->weak_evidence ?? [],
                    ],
                    'suggestions' => $this->currentAnalysis->suggestions ?? [],
                    'strengths' => $this->currentAnalysis->strengths ?? [],
                    'critical_gaps' => $this->currentAnalysis->critical_gaps ?? [],
                    'missing_metrics' => $this->currentAnalysis->missing_metrics ?? [],
                    'rewrite_priorities' => $this->currentAnalysis->rewrite_priorities ?? [],
                    'experience_review' => $this->currentAnalysis->experience_review ?? [],
                    'industry_match' => $this->currentAnalysis->industry_match ?? [],
                    'summary' => $this->currentAnalysis->summary,
                    'job_match_score' => $this->currentAnalysis->job_match_score,
                    'job_title_targeted' => $this->currentAnalysis->job_title_targeted,
                    'target_seniority' => $this->currentAnalysis->target_seniority,
                ];
            }
        }

        // Load Experience, Education, Skills, Categories, Certificates
        $this->experiences = Experience::orderBy('order', 'asc')->orderBy('start_date', 'desc')->get();
        $this->educations = Education::orderBy('start_date', 'desc')->orderBy('order', 'asc')->get();
        $this->skills = Skill::with('category')->get();
        $this->categories = Category::all();
        $this->all_skills_list = Skill::orderBy('name', 'asc')->get();
        $this->certificates = Certificate::orderBy('issue_date', 'desc')->get();
        $this->allAnalyses = CVAnalysis::with('profile')->latest()->get();
    }

    // ==========================================
    // PROFILE MANAGEMENT
    // ==========================================
    public function saveProfile(): void
    {
        $profile = Profile::where('is_primary', true)->first() ?? Profile::first() ?? new Profile;

        $profileData = [
            'name' => $this->profile_name,
            'title' => $this->profile_title,
            'biography' => $this->profile_biography,
            'email' => $this->profile_email,
            'phone' => $this->profile_phone,
            'location' => $this->profile_location,
            'github_url' => $this->profile_github_url,
            'linkedin_url' => $this->profile_linkedin_url,
            'twitter_url' => $this->profile_twitter_url,
            'website_url' => $this->profile_website_url,
            'is_primary' => true,
            'cv_template' => $this->selectedTemplate,
        ];

        // Process avatar file upload if exists
        if ($this->profile_avatar && method_exists($this->profile_avatar, 'store')) {
            $path = $this->profile_avatar->store('avatars', 'public');
            $profileData['avatar'] = $path;
            $this->profile_avatar_path = $path;
            $this->profile_avatar = null;
        }

        $profile->fill($profileData);
        $profile->save();

        Notification::make()
            ->title('Profile Saved')
            ->body('Your professional details have been updated successfully.')
            ->success()
            ->send();

        $this->loadAllData();
    }

    // ==========================================
    // EXPERIENCE CRUD
    // ==========================================
    public function openExperienceModal(?int $id = null): void
    {
        $this->editingExperienceId = $id;

        if ($id) {
            $exp = Experience::findOrFail($id);
            $this->exp_company = $exp->company;
            $this->exp_position = $exp->position;
            $this->exp_employment_type = $exp->employment_type ?? 'Full-time';
            $this->exp_location = $exp->location ?? '';
            $this->exp_start_date = $exp->start_date ? $exp->start_date->format('Y-m-d') : '';
            $this->exp_end_date = $exp->end_date ? $exp->end_date->format('Y-m-d') : '';
            $this->exp_description = $exp->description ?? '';
            $this->exp_achievements = $exp->achievements ?? [];
            $this->exp_order = $exp->order;
            $this->selected_exp_skills = $exp->skills->pluck('id')->toArray();
        } else {
            $this->exp_company = '';
            $this->exp_position = '';
            $this->exp_employment_type = 'Full-time';
            $this->exp_location = '';
            $this->exp_start_date = '';
            $this->exp_end_date = '';
            $this->exp_description = '';
            $this->exp_achievements = [];
            $this->exp_order = 0;
            $this->selected_exp_skills = [];
        }

        $this->new_achievement = '';
        $this->showExperienceModal = true;
    }

    public function addAchievement(): void
    {
        if (trim($this->new_achievement) !== '') {
            $this->exp_achievements[] = trim($this->new_achievement);
            $this->new_achievement = '';
        }
    }

    public function removeAchievement(int $index): void
    {
        if (isset($this->exp_achievements[$index])) {
            unset($this->exp_achievements[$index]);
            $this->exp_achievements = array_values($this->exp_achievements);
        }
    }

    public function saveExperience(): void
    {
        $this->validate([
            'exp_company' => 'required|string|max:255',
            'exp_position' => 'required|string|max:255',
            'exp_start_date' => 'required|date',
            'exp_end_date' => 'nullable|date|after_or_equal:exp_start_date',
        ]);

        $order = $this->editingExperienceId
            ? (Experience::find($this->editingExperienceId)->order ?? 0)
            : (Experience::max('order') ?? 0) + 1;

        $exp = Experience::updateOrCreate(
            ['id' => $this->editingExperienceId],
            [
                'company' => $this->exp_company,
                'position' => $this->exp_position,
                'employment_type' => $this->exp_employment_type,
                'location' => $this->exp_location,
                'start_date' => Carbon::parse($this->exp_start_date),
                'end_date' => $this->exp_end_date ? Carbon::parse($this->exp_end_date) : null,
                'description' => $this->exp_description,
                'achievements' => $this->exp_achievements,
                'order' => $order,
            ]
        );

        $exp->skills()->sync($this->selected_exp_skills);

        Notification::make()
            ->title($this->editingExperienceId ? 'Experience Updated' : 'Experience Added')
            ->success()
            ->send();

        $this->showExperienceModal = false;
        $this->loadAllData();
    }

    public function deleteExperience(int $id): void
    {
        $exp = Experience::findOrFail($id);
        $exp->skills()->detach();
        $exp->delete();

        Notification::make()
            ->title('Experience Deleted')
            ->success()
            ->send();

        $this->loadAllData();
    }

    public function reorderExperiences(int $dragId, int $dropId): void
    {
        $orderedIds = Experience::orderBy('order', 'asc')->orderBy('start_date', 'desc')->pluck('id')->toArray();

        $dragIndex = array_search($dragId, $orderedIds);
        $dropIndex = array_search($dropId, $orderedIds);

        if ($dragIndex !== false && $dropIndex !== false && $dragIndex !== $dropIndex) {
            // Remove the dragged ID
            array_splice($orderedIds, $dragIndex, 1);
            // Insert it at the drop ID position
            array_splice($orderedIds, $dropIndex, 0, $dragId);

            // Save new orders
            foreach ($orderedIds as $index => $id) {
                Experience::where('id', $id)->update(['order' => $index + 1]);
            }

            $this->loadAllData();

            Notification::make()
                ->title('Order Updated')
                ->success()
                ->send();
        }
    }

    // ==========================================
    // EDUCATION CRUD
    // ==========================================
    public function openEducationModal(?int $id = null): void
    {
        $this->editingEducationId = $id;

        if ($id) {
            $edu = Education::findOrFail($id);
            $this->edu_institution = $edu->institution;
            $this->edu_degree = $edu->degree;
            $this->edu_field_of_study = $edu->field_of_study;
            $this->edu_start_date = $edu->start_date ? $edu->start_date->format('Y-m-d') : '';
            $this->edu_end_date = $edu->end_date ? $edu->end_date->format('Y-m-d') : '';
            $this->edu_grade = $edu->grade ?? '';
            $this->edu_activities = $edu->activities ?? '';
            $this->edu_order = $edu->order;
        } else {
            $this->edu_institution = '';
            $this->edu_degree = '';
            $this->edu_field_of_study = '';
            $this->edu_start_date = '';
            $this->edu_end_date = '';
            $this->edu_grade = '';
            $this->edu_activities = '';
            $this->edu_order = 0;
        }

        $this->showEducationModal = true;
    }

    public function saveEducation(): void
    {
        $this->validate([
            'edu_institution' => 'required|string|max:255',
            'edu_degree' => 'required|string|max:255',
            'edu_field_of_study' => 'required|string|max:255',
            'edu_start_date' => 'required|date',
            'edu_end_date' => 'nullable|date|after_or_equal:edu_start_date',
        ]);

        Education::updateOrCreate(
            ['id' => $this->editingEducationId],
            [
                'institution' => $this->edu_institution,
                'degree' => $this->edu_degree,
                'field_of_study' => $this->edu_field_of_study,
                'start_date' => Carbon::parse($this->edu_start_date),
                'end_date' => $this->edu_end_date ? Carbon::parse($this->edu_end_date) : null,
                'grade' => $this->edu_grade,
                'activities' => $this->edu_activities,
                'order' => $this->edu_order,
            ]
        );

        Notification::make()
            ->title($this->editingEducationId ? 'Education Updated' : 'Education Added')
            ->success()
            ->send();

        $this->showEducationModal = false;
        $this->loadAllData();
    }

    public function deleteEducation(int $id): void
    {
        Education::findOrFail($id)->delete();

        Notification::make()
            ->title('Education Deleted')
            ->success()
            ->send();

        $this->loadAllData();
    }

    // ==========================================
    // SKILLS CRUD
    // ==========================================
    public function openSkillModal(?int $id = null): void
    {
        $this->editingSkillId = $id;

        if ($id) {
            $skill = Skill::findOrFail($id);
            $this->skill_name = $skill->name;
            $this->skill_category_id = $skill->category_id;
            $this->skill_proficiency = $skill->proficiency ?? 80;
            $this->skill_years_experience = (float) ($skill->years_experience ?? 0.0);
        } else {
            $this->skill_name = '';
            $this->skill_category_id = $this->categories->first()?->id;
            $this->skill_proficiency = 80;
            $this->skill_years_experience = 0.0;
        }

        $this->showSkillModal = true;
    }

    public function saveSkill(): void
    {
        $this->validate([
            'skill_name' => 'required|string|max:255',
            'skill_category_id' => 'required|exists:categories,id',
            'skill_proficiency' => 'required|integer|min:0|max:100',
            'skill_years_experience' => 'required|numeric|min:0',
        ]);

        Skill::updateOrCreate(
            ['id' => $this->editingSkillId],
            [
                'name' => $this->skill_name,
                'category_id' => $this->skill_category_id,
                'proficiency' => $this->skill_proficiency,
                'years_experience' => $this->skill_years_experience,
            ]
        );

        Notification::make()
            ->title($this->editingSkillId ? 'Skill Updated' : 'Skill Added')
            ->success()
            ->send();

        $this->showSkillModal = false;
        $this->loadAllData();
    }

    public function deleteSkill(int $id): void
    {
        Skill::findOrFail($id)->delete();

        Notification::make()
            ->title('Skill Deleted')
            ->success()
            ->send();

        $this->loadAllData();
    }

    // ==========================================
    // CERTIFICATES CRUD
    // ==========================================
    public function openCertificateModal(?int $id = null): void
    {
        $this->editingCertificateId = $id;

        if ($id) {
            $cert = Certificate::findOrFail($id);
            $this->cert_name = $cert->name;
            $this->cert_issuer = $cert->issuer;
            $this->cert_credential_id = $cert->credential_id ?? '';
            $this->cert_credential_url = $cert->credential_url ?? '';
            $this->cert_issue_date = $cert->issue_date ? $cert->issue_date->format('Y-m-d') : '';
            $this->cert_expiry_date = $cert->expiry_date ? $cert->expiry_date->format('Y-m-d') : '';
            $this->cert_is_active = $cert->is_active;
        } else {
            $this->cert_name = '';
            $this->cert_issuer = '';
            $this->cert_credential_id = '';
            $this->cert_credential_url = '';
            $this->cert_issue_date = '';
            $this->cert_expiry_date = '';
            $this->cert_is_active = true;
        }

        $this->showCertificateModal = true;
    }

    public function saveCertificate(): void
    {
        $this->validate([
            'cert_name' => 'required|string|max:255',
            'cert_issuer' => 'required|string|max:255',
            'cert_issue_date' => 'required|date',
            'cert_expiry_date' => 'nullable|date|after_or_equal:cert_issue_date',
        ]);

        Certificate::updateOrCreate(
            ['id' => $this->editingCertificateId],
            [
                'name' => $this->cert_name,
                'issuer' => $this->cert_issuer,
                'credential_id' => $this->cert_credential_id,
                'credential_url' => $this->cert_credential_url,
                'issue_date' => Carbon::parse($this->cert_issue_date),
                'expiry_date' => $this->cert_expiry_date ? Carbon::parse($this->cert_expiry_date) : null,
                'is_active' => $this->cert_is_active,
            ]
        );

        Notification::make()
            ->title($this->editingCertificateId ? 'Certificate Updated' : 'Certificate Added')
            ->success()
            ->send();

        $this->showCertificateModal = false;
        $this->loadAllData();
    }

    public function deleteCertificate(int $id): void
    {
        Certificate::findOrFail($id)->delete();

        Notification::make()
            ->title('Certificate Deleted')
            ->success()
            ->send();

        $this->loadAllData();
    }

    // ==========================================
    // TEMPLATE SELECTION
    // ==========================================
    public function selectTemplate(string $template): void
    {
        if (! array_key_exists($template, config('cv-templates'))) {
            Notification::make()
                ->title('Invalid Template')
                ->body('Template not found.')
                ->danger()
                ->send();

            return;
        }

        $profile = Profile::where('is_primary', true)->first() ?? Profile::first();
        if ($profile) {
            $profile->update(['cv_template' => $template]);
        }

        $this->selectedTemplate = $template;

        Notification::make()
            ->title('Template Selected')
            ->body('Now using: '.ucfirst($template))
            ->success()
            ->send();

        $this->loadAllData();
    }

    // ==========================================
    // ANALYSIS AND AI UTILITIES
    // ==========================================
    public function analyze(): void
    {
        $this->isAnalyzing = true;

        try {
            $profile = Profile::where('is_primary', true)->first() ?? Profile::first();

            if (! $profile) {
                Notification::make()
                    ->title('Profile Not Found')
                    ->body('Please create a primary profile first.')
                    ->danger()
                    ->send();
                $this->isAnalyzing = false;

                return;
            }

            // Sync any profile changes before running analysis
            $this->saveProfile();

            $cvContent = $this->buildCVContent($profile);

            $agent = new ComprehensiveCVAnalysisAgent($cvContent, $this->jobDescription, $this->targetRole);
            $result = $this->analyzeWithLaravelAi($agent);

            if ($result === [] || ($result['error'] ?? false) === true) {
                return;
            }

            $this->analysisResult = [
                'ats_score' => $result['ats_score'] ?? 0,
                'format_score' => $result['format_score'] ?? 0,
                'about_me_score' => $result['about_me_score'] ?? 0,
                'impact_score' => $result['impact_score'] ?? 0,
                'role_alignment_score' => $result['role_alignment_score'] ?? 0,
                'security_score' => $result['security_score'] ?? 0,
                'reliability_score' => $result['reliability_score'] ?? 0,
                'cloud_score' => $result['cloud_score'] ?? 0,
                'keywords' => $result['keywords'] ?? ['found' => [], 'missing' => [], 'weak_evidence' => []],
                'suggestions' => $result['suggestions'] ?? [],
                'strengths' => $result['strengths'] ?? [],
                'critical_gaps' => $result['critical_gaps'] ?? [],
                'missing_metrics' => $result['missing_metrics'] ?? [],
                'rewrite_priorities' => $result['rewrite_priorities'] ?? [],
                'experience_review' => $result['experience_review'] ?? [],
                'industry_match' => $result['industry_match'] ?? [],
                'summary' => $result['summary'] ?? null,
                'job_match_score' => $result['job_match_score'] ?? null,
                'job_title_targeted' => $result['job_title_targeted'] ?? null,
                'target_seniority' => $result['target_seniority'] ?? null,
            ];

            $analysis = $profile->cvAnalyses()->create([
                'ats_score' => $result['ats_score'] ?? 0,
                'format_score' => $result['format_score'] ?? 0,
                'about_me_score' => $result['about_me_score'] ?? 0,
                'impact_score' => $result['impact_score'] ?? 0,
                'role_alignment_score' => $result['role_alignment_score'] ?? 0,
                'security_score' => $result['security_score'] ?? 0,
                'reliability_score' => $result['reliability_score'] ?? 0,
                'cloud_score' => $result['cloud_score'] ?? 0,
                'keywords_found' => $result['keywords']['found'] ?? [],
                'keywords_missing' => $result['keywords']['missing'] ?? [],
                'weak_evidence' => $result['keywords']['weak_evidence'] ?? [],
                'suggestions' => $result['suggestions'] ?? [],
                'strengths' => $result['strengths'] ?? [],
                'critical_gaps' => $result['critical_gaps'] ?? [],
                'missing_metrics' => $result['missing_metrics'] ?? [],
                'rewrite_priorities' => $result['rewrite_priorities'] ?? [],
                'experience_review' => $result['experience_review'] ?? [],
                'industry_match' => $result['industry_match'] ?? [],
                'job_match_score' => $result['job_match_score'] ?? null,
                'summary' => $result['summary'] ?? null,
                'job_title_targeted' => $result['job_title_targeted'] ?? null,
                'target_seniority' => $result['target_seniority'] ?? null,
            ]);

            $this->currentAnalysis = $analysis;
            $this->history = $profile->cvAnalyses()->latest()->take(10)->get();

            Notification::make()
                ->title('Analysis Complete')
                ->body('Your CV has been analyzed successfully.')
                ->success()
                ->send();

            // Auto switch to overview/dashboard tab to see new scores unless we are on aimatch
            if ($this->activeTab !== 'aimatch') {
                $this->activeTab = 'overview';
            }

        } catch (Throwable $e) {
            $this->notifyAnalysisFailure($e);
        } finally {
            $this->isAnalyzing = false;
        }
    }

    public function analyzeJobMatch(): void
    {
        if (empty($this->jobDescription)) {
            Notification::make()
                ->title('Job Description Required')
                ->body('Please paste a job description first.')
                ->warning()
                ->send();

            return;
        }

        $this->analyze();
    }

    public function downloadCv()
    {
        $profile = Profile::where('is_primary', true)->first() ?? Profile::first();

        if (! $profile) {
            Notification::make()
                ->title('Profile Not Found')
                ->body('Please create a primary profile first.')
                ->danger()
                ->send();

            return;
        }

        return redirect()->route('cv.download', ['template' => $this->selectedTemplate]);
    }

    public function downloadExport(): void
    {
        Notification::make()
            ->title('Export Options')
            ->body('PDF, DOCX, Text, Markdown exports coming soon!')
            ->info()
            ->send();
    }

    protected function analyzeWithLaravelAi(ComprehensiveCVAnalysisAgent $agent): array
    {
        try {
            $response = $agent->prompt(
                'Analyze the CV content and return structured output matching the schema.',
                provider: config('cv.provider', 'cv'),
                model: config('cv.model'),
                timeout: 120,
            );
        } catch (Throwable $e) {
            $this->notifyAnalysisFailure($e);

            return [];
        }

        if ($response instanceof StructuredAgentResponse) {
            return $response->toArray();
        }

        return json_decode($response->text, true, flags: JSON_THROW_ON_ERROR);
    }

    protected function notifyAnalysisFailure(Throwable $exception): void
    {
        $message = match (true) {
            $exception instanceof RequestException => $this->humanizeAiRequestError($exception),
            $exception instanceof ConnectionException => 'AI provider connection failed. Please check the provider/network and try again.',
            default => 'Analysis failed. '.$this->safeErrorMessage($exception->getMessage()),
        };

        Notification::make()
            ->title('Analysis Failed')
            ->body($message)
            ->danger()
            ->persistent()
            ->send();

        Log::error('CV Analysis Error: '.$exception->getMessage(), [
            'status' => $exception instanceof RequestException ? $exception->response->status() : null,
            'body' => $exception instanceof RequestException ? $exception->response->body() : null,
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    protected function humanizeAiRequestError(RequestException $exception): string
    {
        $status = $exception->response->status();
        $body = $exception->response->json();
        $providerMessage = data_get($body, 'error.message') ?? $exception->response->body();
        $providerMessage = $this->safeErrorMessage((string) $providerMessage);

        $prefix = match ($status) {
            401, 403 => 'AI provider authentication failed. Please check API credentials.',
            429 => 'AI provider rate limit reached. Please wait and try again.',
            500, 502, 503, 504 => 'AI provider is temporarily unavailable. Please try again later.',
            default => 'AI request failed.',
        };

        return "{$prefix} Status: {$status}. {$providerMessage}";
    }

    protected function safeErrorMessage(string $message): string
    {
        $message = preg_replace('/Bearer\s+[A-Za-z0-9._\-]+/i', 'Bearer [REDACTED]', $message) ?? $message;
        $message = preg_replace('/api[_-]?key[\"\'\s:=]+[A-Za-z0-9._\-]+/i', 'api_key=[REDACTED]', $message) ?? $message;

        return Str::limit($message, 350);
    }

    public function loadAnalysisIntoWorkspace(int $id): void
    {
        $analysis = CVAnalysis::findOrFail($id);
        $this->currentAnalysis = $analysis;

        $this->analysisResult = [
            'ats_score' => $analysis->ats_score,
            'format_score' => $analysis->format_score,
            'about_me_score' => $analysis->about_me_score ?? 0,
            'impact_score' => $analysis->impact_score ?? 0,
            'role_alignment_score' => $analysis->role_alignment_score ?? 0,
            'security_score' => $analysis->security_score ?? 0,
            'reliability_score' => $analysis->reliability_score ?? 0,
            'cloud_score' => $analysis->cloud_score ?? 0,
            'keywords' => [
                'found' => $analysis->keywords_found ?? [],
                'missing' => $analysis->keywords_missing ?? [],
                'weak_evidence' => $analysis->weak_evidence ?? [],
            ],
            'suggestions' => $analysis->suggestions ?? [],
            'strengths' => $analysis->strengths ?? [],
            'critical_gaps' => $analysis->critical_gaps ?? [],
            'missing_metrics' => $analysis->missing_metrics ?? [],
            'rewrite_priorities' => $analysis->rewrite_priorities ?? [],
            'experience_review' => $analysis->experience_review ?? [],
            'industry_match' => $analysis->industry_match ?? [],
            'summary' => $analysis->summary,
            'job_match_score' => $analysis->job_match_score,
            'job_title_targeted' => $analysis->job_title_targeted,
            'target_seniority' => $analysis->target_seniority,
        ];

        // Fill job description with summary context if available
        if ($analysis->summary) {
            $this->jobDescription = 'Target Role: '.($analysis->job_title_targeted ?? 'Unknown')."\nSummary: ".$analysis->summary;
        }

        // Switch to the AI Job Match tab to show these insights inline!
        $this->activeTab = 'aimatch';

        Notification::make()
            ->title('Analysis Run Loaded')
            ->body('Showing insights for past run: '.($analysis->job_title_targeted ?? 'CV Analysis'))
            ->info()
            ->send();
    }

    public function deleteAnalysis(int $id): void
    {
        CVAnalysis::findOrFail($id)->delete();

        Notification::make()
            ->title('Analysis Run Deleted')
            ->success()
            ->send();

        $this->loadAllData();
    }

    public function openAnalysisDetailModal(int $id): void
    {
        $analysis = CVAnalysis::with('profile')->findOrFail($id);
        $this->selectedAnalysisDetail = [
            'id' => $analysis->id,
            'date' => $analysis->created_at->format('M j, Y H:i'),
            'profile_name' => $analysis->profile?->name ?? 'Unknown',
            'ats_score' => $analysis->ats_score,
            'format_score' => $analysis->format_score,
            'about_me_score' => $analysis->about_me_score ?? 0,
            'impact_score' => $analysis->impact_score ?? 0,
            'role_alignment_score' => $analysis->role_alignment_score ?? 0,
            'security_score' => $analysis->security_score ?? 0,
            'reliability_score' => $analysis->reliability_score ?? 0,
            'cloud_score' => $analysis->cloud_score ?? 0,
            'job_match_score' => $analysis->job_match_score ?? 0,
            'job_title_targeted' => $analysis->job_title_targeted ?? 'General CV Analysis',
            'target_seniority' => $analysis->target_seniority ?? 'Not Clear',
            'summary' => $analysis->summary,
            'keywords' => [
                'found' => $analysis->keywords_found ?? [],
                'missing' => $analysis->keywords_missing ?? [],
                'weak_evidence' => $analysis->weak_evidence ?? [],
            ],
            'suggestions' => $analysis->suggestions ?? [],
            'strengths' => $analysis->strengths ?? [],
            'critical_gaps' => $analysis->critical_gaps ?? [],
            'missing_metrics' => $analysis->missing_metrics ?? [],
            'rewrite_priorities' => $analysis->rewrite_priorities ?? [],
            'experience_review' => $analysis->experience_review ?? [],
            'industry_match' => $analysis->industry_match ?? [],
        ];

        $this->showAnalysisDetailModal = true;
    }

    public function closeAnalysisDetailModal(): void
    {
        $this->showAnalysisDetailModal = false;
        $this->selectedAnalysisDetail = null;
    }

    protected function buildCVContent(Profile $profile): string
    {
        return $this->cvService->buildCVContent($profile);
    }
}
