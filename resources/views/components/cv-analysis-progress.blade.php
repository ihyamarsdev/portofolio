@php
    $steps = [
        ['title' => 'Menyiapkan profil CV', 'desc' => 'Memuat profil, pengalaman kerja, dan skill.'],
        ['title' => 'Mengekstrak requirement', 'desc' => 'Membaca deskripsi lowongan menjadi daftar syarat.'],
        ['title' => 'Mencocokkan bukti', 'desc' => 'Memverifikasi bukti pada CV dengan metode STAR.'],
        ['title' => 'Menyusun skor', 'desc' => 'Menghitung skor objektif dan menyusun rekomendasi.'],
    ];
@endphp

<div class="am-progress"
     x-data="{
         analyzing: $wire.entangle('isAnalyzing'),
         hasResult: !! $wire.analysisResult,
         step: 0,
         timer: null,
         total: {{ count($steps) }},
         init() {
             this.hasResult = !! $wire.analysisResult;
             this.step = this.hasResult ? this.total : 0;
             this.$watch('analyzing', (value) => {
                 if (value) {
                     this.step = 0;
                     this.start();
                 } else {
                     this.stop();
                     this.hasResult = !! $wire.analysisResult;
                     this.step = this.hasResult ? this.total : 0;
                 }
             });
         },
         start() {
             this.stop();
             this.timer = setInterval(() => {
                 if (this.step < this.total - 1) {
                     this.step++;
                 }
             }, 1600);
         },
         stop() {
             if (this.timer) { clearInterval(this.timer); this.timer = null; }
         },
         isDone(index) {
             if (this.analyzing) { return index < this.step; }
             return this.hasResult;
         },
         isActive(index) {
             return this.analyzing && index === this.step;
         }
     }">
    <div class="am-card">
        <div class="am-card-head">
            <span class="am-ico"><x-heroicon-o-sparkles class="w-4.5 h-4.5" /></span>
            <div>
                <h3 class="am-title">Proses Analisis</h3>
                <p class="am-hint" x-show="analyzing">Sedang berjalan — estimasi 30–90 detik.</p>
                <p class="am-hint" x-show="! analyzing && hasResult">Analisis terakhir selesai.</p>
                <p class="am-hint" x-show="! analyzing && ! hasResult">Siap dijalankan.</p>
            </div>
        </div>

        <div class="am-progress-bar" x-show="analyzing">
            <div class="am-progress-bar-fill" :style="'width: ' + Math.round(((step + 1) / total) * 100) + '%'"></div>
        </div>

        <ol class="am-steps">
            @foreach($steps as $index => $item)
                <li class="am-step" :class="{ 'is-active': isActive({{ $index }}), 'is-done': isDone({{ $index }}) }">
                    <span class="am-step-mark" aria-hidden="true">
                        <template x-if="isDone({{ $index }})">
                            <x-heroicon-s-check class="w-3.5 h-3.5" />
                        </template>
                        <template x-if="! isDone({{ $index }})">
                            <span class="am-step-num">{{ $index + 1 }}</span>
                        </template>
                    </span>
                    <span class="am-step-text">
                        <span class="am-step-title">{{ $item['title'] }}</span>
                        <span class="am-step-desc">{{ $item['desc'] }}</span>
                    </span>
                </li>
            @endforeach
        </ol>
    </div>
</div>
