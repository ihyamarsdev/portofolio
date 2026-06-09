@props([
    'targetRole' => 'Devops Engineer',
    'jobDescription' => ''
])

<div class="glass-card p-6 space-y-4 font-mono text-xs text-slate-300 border border-cyan-500/30 shadow-lg shadow-cyan-500/5 mb-6"
     x-data="{
         isAnalyzing: $wire.entangle('isAnalyzing'),
         logs: [],
         allLogs: [
             { type: 'info', text: 'Initializing AI Engine connection...' },
             { type: 'info', text: 'Retrieving primary candidate profile...' },
             { type: 'info', text: 'Fetching and compiling work experiences...' },
             { type: 'info', text: 'Fetching and compiling skills & technologies...' },
             { type: 'info', text: 'Target role selected: {{ $targetRole }}' },
             @if(!empty($jobDescription))
             { type: 'info', text: 'Analyzing custom job description requirements ({{ strlen($jobDescription) }} bytes)...' },
             @endif
             { type: 'info', text: 'Analyzing resume text matching against reference concepts...' },
             { type: 'warning', text: 'Sending prompt payloads to AI model gateway ({{ config('cv.provider', 'cv') }}/{{ config('cv.model', 'cv') }})...' },
             { type: 'warning', text: 'Waiting for structured response format...' }
         ],
         currentIndex: 0,
         timer: null,
         scrollConsole() {
             this.$nextTick(() => {
                 let consoleDiv = this.$refs.console;
                 if (consoleDiv) {
                     consoleDiv.scrollTop = consoleDiv.scrollHeight;
                 }
             });
         },
         runLogs() {
             this.logs = [];
             this.currentIndex = 0;
             if (this.timer) clearInterval(this.timer);
             this.timer = setInterval(() => {
                 if (this.currentIndex < this.allLogs.length) {
                     let log = this.allLogs[this.currentIndex];
                     let time = new Date().toLocaleTimeString();
                     this.logs.push(`[${time}] [${log.type.toUpperCase()}] ${log.text}`);
                     this.currentIndex++;
                     this.scrollConsole();
                 } else {
                     clearInterval(this.timer);
                 }
             }, 450);
         },
         init() {
             let initTime = new Date().toLocaleTimeString();
             this.logs.push(`[${initTime}] [INFO] Console initialized.`);
             this.logs.push(`[${initTime}] [INFO] Select a target role and click 'Run AI Analysis Match' to start...`);

             this.$watch('isAnalyzing', value => {
                 if (value) {
                     this.runLogs();
                 } else {
                     clearInterval(this.timer);
                     setTimeout(() => {
                         let result = this.$wire.analysisResult;
                         let time = new Date().toLocaleTimeString();
                         if (result) {
                             this.logs.push(`[${time}] [INFO] Response received from AI model gateway.`);
                             this.logs.push(`[${time}] [INFO] Parsed structured JSON schema successfully.`);
                             this.logs.push(`[${time}] [INFO] Scores computed - ATS: ${result.ats_score}% | Format: ${result.format_score}% | Job Match: ${result.job_match_score ?? 0}%.`);
                             this.logs.push(`[${time}] [INFO] Saving analysis record to Database.`);
                             this.logs.push(`[${time}] [SUCCESS] Analysis completed successfully!`);
                         } else {
                             if (this.logs.length > 2) {
                                 this.logs.push(`[${time}] [ERROR] AI analysis failed or returned an empty response.`);
                             }
                         }
                         this.scrollConsole();
                     }, 150);
                 }
             });
         }
     }">
    <div class="flex items-center justify-between border-b border-[#2d2d3a] pb-3 mb-2">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
            <span class="text-slate-400 font-bold ml-2">AI Analysis Live Console</span>
        </div>
        <span class="text-cyan-400 animate-pulse text-[10px] tracking-wider uppercase font-bold" x-show="isAnalyzing">Processing...</span>
        <span class="text-emerald-400 text-[10px] tracking-wider uppercase font-bold" x-show="!isAnalyzing">Ready</span>
    </div>
    <div x-ref="console" class="bg-[#0c0c14] border border-[#2d2d3a] rounded-lg p-4 h-64 overflow-y-auto space-y-2 scrollbar-thin select-none">
        <template x-for="log in logs">
            <div class="leading-relaxed whitespace-pre-wrap font-mono"
                 :class="{
                     'text-cyan-400': log.includes('[INFO]'),
                     'text-amber-400 font-semibold': log.includes('[WARNING]'),
                     'text-emerald-400 font-bold': log.includes('[SUCCESS]'),
                     'text-rose-400 font-bold': log.includes('[ERROR]')
                 }"
                 x-text="log">
            </div>
        </template>
        <div class="inline-flex items-center gap-1 text-cyan-400 font-bold animate-pulse" x-show="isAnalyzing">
            <span>&gt;_</span>
        </div>
    </div>
</div>
