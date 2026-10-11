<?php

return [
    'input' => [
        'title' => 'Target Pekerjaan',
        'hint' => 'Pilih role dan tempel deskripsi lowongan untuk analisis kecocokan.',
        'target_role' => 'Target Role',
        'jobdesc' => 'Job Description',
        'optional' => 'opsional',
        'jobdesc_placeholder' => 'Tempel requirement atau deskripsi lowongan di sini...',
        'jobdesc_hint' => 'Isi untuk mendapat skor Job Match berbasis bukti. Kosongkan jika hanya ingin menilai ATS, format, dan domain.',
        'run_analysis' => 'Jalankan Analisis AI',
        'roles' => [
            'devops' => 'DevOps Engineer',
            'devsecops' => 'DevSecOps Engineer',
            'cloud' => 'Cloud Engineer',
            'sre' => 'Site Reliability Engineer',
        ],
    ],

    'progress' => [
        'title' => 'Proses Analisis',
        'hint_running' => 'Sedang berjalan — estimasi 30–90 detik.',
        'hint_done' => 'Analisis terakhir selesai.',
        'hint_ready' => 'Siap dijalankan.',
        'steps' => [
            'profile' => [
                'title' => 'Menyiapkan profil CV',
                'desc' => 'Memuat profil, pengalaman kerja, dan skill.',
            ],
            'requirements' => [
                'title' => 'Mengekstrak requirement',
                'desc' => 'Membaca deskripsi lowongan menjadi daftar syarat.',
            ],
            'evidence' => [
                'title' => 'Mencocokkan bukti',
                'desc' => 'Memverifikasi bukti pada CV dengan metode STAR.',
            ],
            'scoring' => [
                'title' => 'Menyusun skor',
                'desc' => 'Menghitung skor objektif dan menyusun rekomendasi.',
            ],
        ],
    ],

    'history' => [
        'title' => 'Analisis Terakhir',
        'hint' => 'Klik untuk memuat hasil sebelumnya.',
        'default_title' => 'Analisis CV',
        'load_aria' => 'Muat analisis untuk :role dari :date',
    ],

    'summary' => [
        'ring_job_match' => 'Job Match',
        'ring_role_fitment' => 'Role Fitment',
        'match_for' => 'Kecocokan untuk :role',
        'seniority' => 'Seniority: :level',
        'domain' => 'Domain :name: :score%',
        'job_match' => 'Job Match: :score%',
        'role_taglines' => [
            'devops engineer' => 'Keseimbangan delivery CI/CD, infrastructure-as-code, dan keandalan platform.',
            'devsecops engineer' => 'Keamanan menyatu dalam pipeline: hardening, manajemen secret, dan compliance.',
            'cloud engineer' => 'Kedalaman arsitektur cloud, pemilihan layanan, dan infrastruktur hemat biaya.',
            'site reliability engineer' => 'Rekayasa uptime: SLO, incident response, observability, dan perencanaan kapasitas.',
        ],
    ],

    'metrics' => [
        'title' => 'Metrik Penilaian',
        'hint' => 'Metrik inti untuk role ini ditandai.',
        'core_badge' => 'inti',
        'items' => [
            'role_alignment_score' => [
                'label' => 'Kesesuaian Role',
                'desc' => 'Cakupan skill & tanggung jawab terhadap role target',
            ],
            'security_score' => [
                'label' => 'Keamanan',
                'desc' => 'Hardening, manajemen secret & praktik kerentanan',
            ],
            'reliability_score' => [
                'label' => 'Keandalan',
                'desc' => 'Uptime, incident response & praktik SLO',
            ],
            'cloud_score' => [
                'label' => 'Cloud',
                'desc' => 'Kedalaman layanan & arsitektur cloud',
            ],
            'ats_score' => [
                'label' => 'Skor ATS',
                'desc' => 'Kompatibilitas parsing keyword & section',
            ],
            'format_score' => [
                'label' => 'Format',
                'desc' => 'Kualitas struktur, heading & tata letak',
            ],
            'about_me_score' => [
                'label' => 'Ringkasan',
                'desc' => 'Kekuatan & positioning summary',
            ],
            'impact_score' => [
                'label' => 'Dampak',
                'desc' => 'Outcome terukur & pencapaian',
            ],
            'job_match_score' => [
                'label' => 'Job Match',
                'desc' => 'Kecocokan berbasis bukti vs deskripsi lowongan',
            ],
        ],
    ],

    'bands' => [
        'Strong Match' => 'Kecocokan Kuat',
        'Good Match' => 'Kecocokan Baik',
        'Partial Match' => 'Kecocokan Sebagian',
        'Weak Match' => 'Kecocokan Lemah',
    ],

    'band_definitions' => [
        'Strong Match' => 'Mayoritas syarat wajib terverifikasi dengan bukti terukur (metrik, skala, dampak).',
        'Good Match' => 'Syarat wajib tercakup, namun beberapa masih sebatas klaim tanpa bukti terukur.',
        'Partial Match' => 'Celah terlihat pada syarat wajib, atau bukti sebagian besar lemah (klaim tanpa metrik).',
        'Weak Match' => 'Syarat wajib penting belum ada di CV.',
    ],

    'status' => [
        'verified' => 'Terverifikasi',
        'claimed' => 'Terdaku',
        'missing' => 'Hilang',
    ],

    'requirements' => [
        'title' => 'Checklist Requirement',
        'count' => ':count syarat',
        'overall_score' => 'Skor Keseluruhan',
        'must_have' => 'Must-have',
        'nice_to_have' => 'Nice-to-have',
        'evidence_quality' => 'Kualitas Bukti',
        'how_calculated' => 'Cara skor dihitung (formula objektif)',
        'formula_overall' => 'overall = 0.6 × must_have_coverage + 0.2 × nice_to_have_coverage + 0.2 × evidence_quality',
        'formula_status' => 'poin status: verified = 1.0 · claimed = 0.4 · missing = 0.0',
        'formula_quality' => 'evidence_quality = verified / (verified + claimed)',
        'band_scale' => [
            'strong' => '85–100 Kuat:',
            'strong_desc' => 'mayoritas must-have terverifikasi dengan bukti terukur.',
            'good' => '70–84 Baik:',
            'good_desc' => 'must-have tercakup, sebagian masih sebatas klaim.',
            'partial' => '50–69 Sebagian:',
            'partial_desc' => 'ada celah pada must-have atau bukti lemah.',
            'weak' => '0–49 Lemah:',
            'weak_desc' => 'must-have penting belum ada di CV.',
        ],
        'formula_note' => 'Skor dihitung secara deterministik dari bukti yang diverifikasi terhadap teks CV — AI tidak pernah menetapkan angka.',
    ],

    'keywords' => [
        'title' => 'Audit Kata Kunci',
        'meta' => ':found ditemukan · :missing hilang',
        'found' => 'Ditemukan (:count)',
        'weak' => 'Bukti Lemah (:count)',
        'missing' => 'Hilang (:count)',
        'empty_none' => 'Tidak ada',
        'all_covered' => 'Cakupan kata kunci lengkap.',
    ],

    'improvements' => [
        'title' => 'Fokus Perbaikan',
        'meta' => ':count item',
        'lowest_scoring' => 'Area skor terendah',
        'action_steps' => 'Langkah optimasi',
        'rewrite_priorities' => 'Prioritas rewrite',
    ],

    'strengths_gaps' => [
        'title' => 'Kekuatan & Celah',
        'meta' => ':strengths kekuatan · :gaps celah',
        'professional_strengths' => 'Kekuatan profesional',
        'critical_gaps' => 'Celah kritis',
        'missing_metrics' => 'Metrik yang belum ada',
    ],

    'experience' => [
        'title' => 'Review Pengalaman',
        'meta' => ':count bagian',
        'strength_tag' => 'Kekuatan',
        'gap_tag' => 'Celah',
        'rewrite_hint_tag' => 'Saran rewrite',
    ],

    'domains' => [
        'title' => 'Detail Domain',
        'meta' => ':count domain',
        'target_badge' => 'target',
    ],

    'empty_state' => [
        'title' => 'Belum ada hasil analisis',
        'hint' => 'Pilih target role di panel kiri, tempel deskripsi lowongan bila ada, lalu jalankan analisis untuk melihat skor, checklist requirement, dan langkah perbaikan.',
        'run_analysis' => 'Jalankan Analisis AI',
    ],
];
