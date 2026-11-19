<?php

return [
    'input' => [
        'title' => 'Job Target',
        'hint' => 'Select a role and paste a job description for match analysis.',
        'target_role' => 'Target Role',
        'jobdesc' => 'Job Description',
        'optional' => 'optional',
        'jobdesc_placeholder' => 'Paste job requirements or description here...',
        'jobdesc_hint' => 'Fill this in to get an evidence-based Job Match score. Leave empty if you only want to assess ATS, formatting, and domain.',
        'run_analysis' => 'Run AI Analysis',
        'roles' => [
            'devops' => 'DevOps Engineer',
            'devsecops' => 'DevSecOps Engineer',
            'cloud' => 'Cloud Engineer',
            'sre' => 'Site Reliability Engineer',
        ],
    ],

    'progress' => [
        'title' => 'Analysis Process',
        'hint_running' => 'In progress — estimated 30–90 seconds.',
        'hint_done' => 'Latest analysis completed.',
        'hint_ready' => 'Ready to run.',
        'steps' => [
            'profile' => [
                'title' => 'Preparing CV profile',
                'desc' => 'Loading profile, work experience, and skills.',
            ],
            'requirements' => [
                'title' => 'Extracting requirements',
                'desc' => 'Parsing job description into requirement list.',
            ],
            'evidence' => [
                'title' => 'Matching evidence',
                'desc' => 'Verifying evidence in CV using STAR methodology.',
            ],
            'scoring' => [
                'title' => 'Calculating scores',
                'desc' => 'Computing objective score and compiling recommendations.',
            ],
        ],
    ],

    'history' => [
        'title' => 'Recent Analyses',
        'hint' => 'Click to load previous results.',
        'default_title' => 'CV Analysis',
        'load_aria' => 'Load analysis for :role from :date',
    ],

    'summary' => [
        'ring_job_match' => 'Job Match',
        'ring_role_fitment' => 'Role Fitment',
        'match_for' => 'Match for :role',
        'seniority' => 'Seniority: :level',
        'domain' => 'Domain :name: :score%',
        'job_match' => 'Job Match: :score%',
        'role_taglines' => [
            'devops engineer' => 'Balance of CI/CD delivery, infrastructure-as-code, and platform reliability.',
            'devsecops engineer' => 'Security embedded in pipelines: hardening, secret management, and compliance.',
            'cloud engineer' => 'Cloud architectural depth, service selection, and cost-efficient infrastructure.',
            'site reliability engineer' => 'Uptime engineering: SLOs, incident response, observability, and capacity planning.',
        ],
    ],

    'metrics' => [
        'title' => 'Assessment Metrics',
        'hint' => 'Core metrics for this role are marked.',
        'core_badge' => 'core',
        'items' => [
            'role_alignment_score' => [
                'label' => 'Role Fit',
                'desc' => 'Skill & responsibility coverage for target role',
            ],
            'security_score' => [
                'label' => 'Security',
                'desc' => 'Hardening, secret management & vulnerability practices',
            ],
            'reliability_score' => [
                'label' => 'Reliability',
                'desc' => 'Uptime, incident response & SLO practices',
            ],
            'cloud_score' => [
                'label' => 'Cloud',
                'desc' => 'Cloud service depth & architecture',
            ],
            'ats_score' => [
                'label' => 'ATS Score',
                'desc' => 'Keyword parsing & section compatibility',
            ],
            'format_score' => [
                'label' => 'Format',
                'desc' => 'Structure quality, headings & layout',
            ],
            'about_me_score' => [
                'label' => 'Summary',
                'desc' => 'Summary positioning & strength',
            ],
            'impact_score' => [
                'label' => 'Impact',
                'desc' => 'Quantified outcomes & achievements',
            ],
            'job_match_score' => [
                'label' => 'Job Match',
                'desc' => 'Evidence-based match vs job description',
            ],
        ],
    ],
    'exec' => [
        'readiness' => 'HR Readiness',
        'ats' => 'ATS Compatible',
        'coverage' => 'Match Coverage',
        'density' => 'A4 Density',
        'words' => ':count words',
        'priority_title' => 'Priority Fixes',
        'priority_hint' => 'Top concrete fixes, highest impact first.',
        'before' => 'Before',
        'after' => 'After',
        'deep_dive' => 'Full Breakdown',
        'deep_dive_hint' => 'Rubric, requirements, keywords and evidence tiers.',
        'no_priority' => 'No critical fixes detected — your CV is in good shape.',
    ],


    'bands' => [
        'Strong Match' => 'Strong Match',
        'Good Match' => 'Good Match',
        'Partial Match' => 'Partial Match',
        'Weak Match' => 'Weak Match',
    ],

    'band_definitions' => [
        'Strong Match' => 'Majority of must-have requirements are verified with quantified evidence (metrics, scale, outcomes).',
        'Good Match' => 'Must-have requirements are covered, but several are only claimed without measurable proof.',
        'Partial Match' => 'Noticeable gaps in must-have requirements, or evidence is predominantly weak (claims without metrics).',
        'Weak Match' => 'Critical must-have requirements are missing from the CV.',
    ],

    'status' => [
        'verified' => 'Verified',
        'claimed' => 'Claimed',
        'missing' => 'Missing',
    ],

    'requirements' => [
        'title' => 'Requirement Checklist',
        'count' => ':count requirements',
        'overall_score' => 'Overall Score',
        'must_have' => 'Must-have',
        'nice_to_have' => 'Nice-to-have',
        'evidence_quality' => 'Evidence Quality',
        'how_calculated' => 'How scores are calculated (objective formula)',
        'formula_overall' => 'overall = 0.6 × must_have_coverage + 0.2 × nice_to_have_coverage + 0.2 × evidence_quality',
        'formula_status' => 'status points: verified = 1.0 · claimed = 0.4 · missing = 0.0',
        'formula_quality' => 'evidence_quality = verified / (verified + claimed)',
        'band_scale' => [
            'strong' => '85–100 Strong:',
            'strong_desc' => 'majority of must-haves verified with quantified evidence.',
            'good' => '70–84 Good:',
            'good_desc' => 'must-haves covered, some remain as claims.',
            'partial' => '50–69 Partial:',
            'partial_desc' => 'gaps in must-haves or weak evidence.',
            'weak' => '0–49 Weak:',
            'weak_desc' => 'critical must-haves missing from CV.',
        ],
        'formula_note' => 'Scores are computed deterministically from verified evidence against CV text — AI never assigns numeric scores.',
    ],

    'keywords' => [
        'title' => 'Keyword Audit',
        'meta' => ':found found · :missing missing',
        'found' => 'Found (:count)',
        'weak' => 'Weak Evidence (:count)',
        'missing' => 'Missing (:count)',
        'empty_none' => 'None',
        'all_covered' => 'Keyword coverage complete.',
    ],

    'improvements' => [
        'title' => 'Focus Areas',
        'meta' => ':count items',
        'lowest_scoring' => 'Lowest scoring areas',
        'action_steps' => 'Optimization steps',
        'rewrite_priorities' => 'Rewrite priorities',
    ],

    'strengths_gaps' => [
        'title' => 'Strengths & Gaps',
        'meta' => ':strengths strengths · :gaps gaps',
        'professional_strengths' => 'Professional strengths',
        'critical_gaps' => 'Critical gaps',
        'missing_metrics' => 'Missing metrics',
    ],

    'experience' => [
        'title' => 'Experience Review',
        'meta' => ':count sections',
        'strength_tag' => 'Strength',
        'gap_tag' => 'Gap',
        'rewrite_hint_tag' => 'Rewrite suggestion',
    ],

    'domains' => [
        'title' => 'Domain Breakdown',
        'meta' => ':count domains',
        'target_badge' => 'target',
    ],

    'empty_state' => [
        'title' => 'No analysis results yet',
        'hint' => 'Select a target role in the left panel, paste a job description if available, then run analysis to see scores, requirement checklist, and improvement steps.',
        'run_analysis' => 'Run AI Analysis',
    ],
    'studio' => [
        'eyebrow' => 'Review & Apply Studio',
        'title' => 'Turn analysis into action',
        'subtitle' => 'Review each suggestion, edit it inline, then apply it to your CV. Every change is reversible.',
        'edit_jd' => 'Edit JD / New Match',
        'collapse_jd' => 'Hide input',
        'applied_counter' => ':applied of :total applied',
        'tabs' => [
            'queue' => 'Action Queue',
            'evidence' => 'Matched Evidence',
            'gaps' => 'Skill & Requirement Gaps',
            'rubric' => 'Rubric Breakdown',
        ],
        'exec' => [
            'readiness' => 'HR Readiness',
            'ats' => 'ATS Compatible',
            'coverage' => 'Match Coverage',
            'applied' => 'Applied to CV',
        ],
        'queue' => [
            'title' => 'Action Queue',
            'hint' => 'Highest impact first. Edit the suggested text before applying.',
            'empty' => 'No actionable suggestions for this analysis. Try running a new match with a job description.',
            'before' => 'Current',
            'after' => 'Suggested',
            'rationale' => 'Why this helps',
            'target' => 'Target',
            'impact' => [
                'high' => 'High impact',
                'medium' => 'Medium impact',
                'low' => 'Low impact',
            ],
            'action' => [
                'update_summary' => 'Update Summary',
                'rewrite_experience' => 'Rewrite Experience',
                'add_skill' => 'Add Skill',
                'tailor_project' => 'Tailor Project',
                'advisory' => 'Advisory',
            ],
            'apply' => 'Apply to CV',
            'applied' => 'Applied',
            'dismissed' => 'Dismissed',
            'undo' => 'Undo',
            'dismiss' => 'Dismiss',
            'select' => 'Select for bulk apply',
            'select_all' => 'Select all',
            'skill_note' => 'Confirm you have hands-on experience before adding this skill.',
            'bulk_bar' => ':count selected',
            'bulk_apply' => 'Apply :count selected',
            'view_preview' => 'View Updated CV Preview',
            'inline_label' => 'Edit suggested text',
        ],
        'evidence' => [
            'title' => 'Matched Evidence',
            'hint' => 'Job requirements backed by real experience or projects in your CV.',
            'empty' => 'No requirements were matched with evidence in this analysis.',
            'verified' => 'Verified',
            'claimed' => 'Claimed',
        ],
        'gaps' => [
            'title' => 'Skill & Requirement Gaps',
            'hint' => 'Honest guidance — never add a skill or metric you cannot back up with real experience.',
            'empty' => 'No notable gaps detected.',
            'missing_kw' => 'Missing keywords',
            'weak_kw' => 'Weak evidence',
        ],
        'rubric' => [
            'title' => 'Rubric Breakdown',
            'hint' => 'Secondary detail: objective scores and evidence tiers.',
        ],
    ],

    'notifications' => [
        'applied' => [
            'title' => 'Suggestion applied',
            'body' => 'Updated :target. Your CV preview is now up to date.',
        ],
        'skill_added' => [
            'title' => 'Skill added',
            'body' => ':skill was added to your skills.',
        ],
        'undone' => [
            'title' => 'Change undone',
            'body' => 'The previous value was restored exactly.',
        ],
        'dismissed' => [
            'title' => 'Suggestion dismissed',
            'body' => 'The suggestion was hidden. You can still re-apply it later.',
        ],
        'bulk_applied' => [
            'title' => ':count suggestions applied',
            'body' => 'All selected changes were applied to your CV.',
        ],
        'bulk_none' => [
            'title' => 'Nothing to apply',
            'body' => 'No selectable suggestions were available.',
        ],
        'not_found' => [
            'title' => 'Suggestion not found',
            'body' => 'This suggestion is no longer available. Reload the analysis.',
        ],
        'already_applied' => [
            'title' => 'Already applied',
            'body' => 'This suggestion was already applied to your CV.',
        ],
        'advisory_only' => [
            'title' => 'Advisory item',
            'body' => 'This item is guidance only and cannot be applied automatically.',
        ],
        'empty_content' => [
            'title' => 'Empty content',
            'body' => 'Enter text before applying this suggestion.',
        ],
        'invalid_target' => [
            'title' => 'Invalid target',
            'body' => 'The CV record for this suggestion no longer exists.',
        ],
        'duplicate_skill' => [
            'title' => 'Skill already exists',
            'body' => ':skill is already in your skills list.',
        ],
        'not_applied' => [
            'title' => 'Nothing to undo',
            'body' => 'This suggestion has not been applied.',
        ],
        'target_gone' => [
            'title' => 'Record not found',
            'body' => 'The CV record was deleted, so it cannot be restored.',
        ],
        'invalid_snapshot' => [
            'title' => 'Cannot undo',
            'body' => 'The previous value could not be restored safely.',
        ],
    ],
];
