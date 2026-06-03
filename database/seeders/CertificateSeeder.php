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
            ['name' => 'Docker Certified Associate', 'issuer' => 'Docker', 'credential_id' => 'DCA-456789', 'credential_url' => 'https://www.docker.com/certification/', 'issue_date' => '2023-09-01', 'expiry_date' => '2026-09-01', 'is_active' => true],
            ['name' => 'GitHub Actions Certification', 'issuer' => 'GitHub', 'credential_id' => 'GHA-987654', 'credential_url' => 'https://resources.github.com/learn/certifications/', 'issue_date' => '2023-08-05', 'expiry_date' => '2026-08-05', 'is_active' => true],
            ['name' => 'Linux Foundation Certified System Administrator', 'issuer' => 'Linux Foundation', 'credential_id' => 'LFCS-123456', 'credential_url' => 'https://training.linuxfoundation.org/certification/linux-foundation-certified-sysadmin-lfcs/', 'issue_date' => '2024-01-15', 'expiry_date' => '2027-01-15', 'is_active' => true],
        ];

        foreach ($certificates as $cert) {
            Certificate::updateOrCreate(['credential_id' => $cert['credential_id']], $cert);
        }
    }
}
