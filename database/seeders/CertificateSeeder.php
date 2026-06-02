<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            ['name' => 'Certified Kubernetes Administrator', 'issuer' => 'Cloud Native Computing Foundation', 'credential_id' => 'CKA-123456-1234', 'credential_url' => 'https://www.cncf.io/certification/cka/', 'issue_date' => '2023-03-20', 'expiry_date' => '2026-03-20', 'is_active' => true],
            ['name' => 'AWS Certified DevOps Engineer - Professional', 'issuer' => 'Amazon Web Services', 'credential_id' => 'AWS-DEVOPS-PRO-123456', 'credential_url' => 'https://aws.amazon.com/verification', 'issue_date' => '2023-06-15', 'expiry_date' => '2026-06-15', 'is_active' => true],
            ['name' => 'HashiCorp Certified: Terraform Associate', 'issuer' => 'HashiCorp', 'credential_id' => 'TFA-123456', 'credential_url' => 'https://certification.hashicorp.com/', 'issue_date' => '2022-11-10', 'expiry_date' => null, 'is_active' => true],
            ['name' => 'Google Professional Cloud DevOps Engineer', 'issuer' => 'Google Cloud', 'credential_id' => 'GCP-PCDE-456789', 'credential_url' => 'https://cloud.google.com/certification', 'issue_date' => '2023-09-01', 'expiry_date' => '2026-09-01', 'is_active' => true],
            ['name' => 'Microsoft Certified: DevOps Engineer Expert', 'issuer' => 'Microsoft', 'credential_id' => 'AZ-400-987654', 'credential_url' => 'https://learn.microsoft.com/credentials/', 'issue_date' => '2023-08-05', 'expiry_date' => '2026-08-05', 'is_active' => true],
            ['name' => 'Certified Cloud Security Professional', 'issuer' => 'ISC2', 'credential_id' => 'CCSP-SEC-123456', 'credential_url' => 'https://www.isc2.org/certifications/ccsp', 'issue_date' => '2024-01-15', 'expiry_date' => '2027-01-15', 'is_active' => true],
        ];

        foreach ($certificates as $cert) {
            Certificate::updateOrCreate(['credential_id' => $cert['credential_id']], $cert);
        }
    }
}
