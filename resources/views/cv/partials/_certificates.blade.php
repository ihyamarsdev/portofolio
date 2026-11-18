{{-- Certificates Partial — compact one-line credential ledger.
     Format: Name — Issuer | Year (ID)
     Variables: $certificates (collection), $heading
--}}
@if(($certificates ?? collect())->count() > 0)
    <div class="section">
        <h2 class="section-title">{{ $heading ?? 'Certifications' }}</h2>
        <ul class="certificate-ledger">
            @foreach($certificates as $certificate)
                <li class="certificate-item">
                    <strong>{{ $certificate->name }}</strong>@if($certificate->issuer) — {{ $certificate->issuer }}@endif
                    @if($certificate->issue_date || $certificate->credential_id)
                        <span class="entry-context">
                            | @if($certificate->issue_date){{ $certificate->issue_date->format('Y') }}@endif
                            @if($certificate->issue_date && $certificate->credential_id) · @endif
                            @if($certificate->credential_id)ID {{ $certificate->credential_id }}@endif
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
