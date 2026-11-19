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
    'exec' => [
        'readiness' => 'Kesiapan HR',
        'ats' => 'Kompatibel ATS',
        'coverage' => 'Cakupan Kecocokan',
        'density' => 'Kepadatan A4',
        'words' => ':count kata',
        'priority_title' => 'Perbaikan Prioritas',
        'priority_hint' => 'Perbaikan konkret paling berdampak terlebih dahulu.',
        'before' => 'Sebelum',
        'after' => 'Sesudah',
        'deep_dive' => 'Rincian Lengkap',
        'deep_dive_hint' => 'Rubrik, requirement, keyword, dan tier bukti.',
        'no_priority' => 'Tidak ada perbaikan kritis — CV Anda sudah baik.',
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
    'studio' => [
        'eyebrow' => 'Review & Apply Studio',
        'title' => 'Ubah analisis jadi tindakan',
        'subtitle' => 'Tinjau tiap saran, sunting langsung, lalu terapkan ke CV Anda. Setiap perubahan bisa dibatalkan.',
        'edit_jd' => 'Ubah JD / Match Baru',
        'collapse_jd' => 'Sembunyikan input',
        'applied_counter' => ':applied dari :total diterapkan',
        'tabs' => [
            'queue' => 'Antrian Tindakan',
            'evidence' => 'Bukti Tercocokan',
            'gaps' => 'Celah Skill & Requirement',
            'rubric' => 'Rincian Rubrik',
        ],
        'exec' => [
            'readiness' => 'Kesiapan HR',
            'ats' => 'Kompatibel ATS',
            'coverage' => 'Cakupan Kecocokan',
            'applied' => 'Diterapkan ke CV',
        ],
        'queue' => [
            'title' => 'Antrian Tindakan',
            'hint' => 'Dampak tertinggi lebih dulu. Sunting teks saran sebelum menerapkan.',
            'empty' => 'Tidak ada saran yang bisa diterapkan pada analisis ini. Coba jalankan match baru dengan deskripsi lowongan.',
            'before' => 'Saat Ini',
            'after' => 'Saran',
            'rationale' => 'Mengapa ini membantu',
            'target' => 'Target',
            'impact' => [
                'high' => 'Dampak tinggi',
                'medium' => 'Dampak sedang',
                'low' => 'Dampak rendah',
            ],
            'action' => [
                'update_summary' => 'Perbarui Ringkasan',
                'rewrite_experience' => 'Tulis Ulang Pengalaman',
                'add_skill' => 'Tambah Skill',
                'tailor_project' => 'Sesuaikan Proyek',
                'advisory' => 'Saran',
            ],
            'apply' => 'Terapkan ke CV',
            'applied' => 'Diterapkan',
            'dismissed' => 'Diabaikan',
            'undo' => 'Batalkan',
            'dismiss' => 'Abaikan',
            'select' => 'Pilih untuk terapkan massal',
            'select_all' => 'Pilih semua',
            'skill_note' => 'Pastikan Anda benar-benar punya pengalaman langsung sebelum menambahkan skill ini.',
            'bulk_bar' => ':count dipilih',
            'bulk_apply' => 'Terapkan :count terpilih',
            'view_preview' => 'Lihat Preview CV Terbaru',
            'inline_label' => 'Sunting teks saran',
        ],
        'evidence' => [
            'title' => 'Bukti Tercocokan',
            'hint' => 'Requirement lowongan yang didukung pengalaman atau proyek nyata di CV Anda.',
            'empty' => 'Tidak ada requirement yang cocok dengan bukti pada analisis ini.',
            'verified' => 'Terverifikasi',
            'claimed' => 'Terdaku',
        ],
        'gaps' => [
            'title' => 'Celah Skill & Requirement',
            'hint' => 'Saran jujur — jangan pernah menambahkan skill atau metrik yang tidak bisa Anda buktikan.',
            'empty' => 'Tidak ada celah signifikan terdeteksi.',
            'missing_kw' => 'Kata kunci hilang',
            'weak_kw' => 'Bukti lemah',
        ],
        'rubric' => [
            'title' => 'Rincian Rubrik',
            'hint' => 'Detail sekunder: skor objektif dan tier bukti.',
        ],
    ],

    'notifications' => [
        'applied' => [
            'title' => 'Saran diterapkan',
            'body' => ':target diperbarui. Preview CV Anda sudah mutakhir.',
        ],
        'skill_added' => [
            'title' => 'Skill ditambahkan',
            'body' => ':skill ditambahkan ke daftar skill Anda.',
        ],
        'undone' => [
            'title' => 'Perubahan dibatalkan',
            'body' => 'Nilai sebelumnya dipulihkan persis.',
        ],
        'dismissed' => [
            'title' => 'Saran diabaikan',
            'body' => 'Saran disembunyikan. Anda masih bisa menerapkannya kemudian.',
        ],
        'bulk_applied' => [
            'title' => ':count saran diterapkan',
            'body' => 'Semua perubahan terpilih diterapkan ke CV Anda.',
        ],
        'bulk_none' => [
            'title' => 'Tidak ada yang diterapkan',
            'body' => 'Tidak ada saran yang bisa dipilih.',
        ],
        'not_found' => [
            'title' => 'Saran tidak ditemukan',
            'body' => 'Saran ini sudah tidak tersedia. Muat ulang analisis.',
        ],
        'already_applied' => [
            'title' => 'Sudah diterapkan',
            'body' => 'Saran ini sudah diterapkan ke CV Anda.',
        ],
        'advisory_only' => [
            'title' => 'Item saran',
            'body' => 'Item ini hanya panduan dan tidak bisa diterapkan otomatis.',
        ],
        'empty_content' => [
            'title' => 'Konten kosong',
            'body' => 'Masukkan teks sebelum menerapkan saran ini.',
        ],
        'invalid_target' => [
            'title' => 'Target tidak valid',
            'body' => 'Data CV untuk saran ini sudah tidak ada.',
        ],
        'duplicate_skill' => [
            'title' => 'Skill sudah ada',
            'body' => ':skill sudah ada di daftar skill Anda.',
        ],
        'not_applied' => [
            'title' => 'Tidak ada yang dibatalkan',
            'body' => 'Saran ini belum diterapkan.',
        ],
        'target_gone' => [
            'title' => 'Data tidak ditemukan',
            'body' => 'Data CV sudah dihapus, sehingga tidak bisa dipulihkan.',
        ],
        'invalid_snapshot' => [
            'title' => 'Tidak bisa membatalkan',
            'body' => 'Nilai sebelumnya tidak bisa dipulihkan dengan aman.',
        ],
    ],
];
