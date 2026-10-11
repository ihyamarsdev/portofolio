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
];
