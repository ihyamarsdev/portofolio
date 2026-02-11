# ATS Keyword Database

Keyword database statis untuk optimasi CV berbasis ATS (Applicant Tracking System) dengan fokus pada DevOps dan Cloud Engineering.

## 📁 Struktur File

```
storage/app/keywords/
├── index.json       # Index dan metadata database
├── devops.json      # Keywords DevOps (200+ keywords)
└── cloud.json       # Keywords Cloud Engineering (300+ keywords)
```

## 📊 Kategori Keywords

### DevOps Keywords (`devops.json`)
- **Core Concepts**: CI/CD, IaC, SRE, Microservices, Automation, DevSecOps, Agile
- **Containerization & Orchestration**: Docker, Kubernetes, Helm, OpenShift
- **Configuration Management**: Terraform, Ansible, Chef, Puppet, CloudFormation
- **CI/CD Tools**: Jenkins, GitLab CI/CD, GitHub Actions, ArgoCD, Azure DevOps
- **Monitoring & Logging**: Prometheus, Grafana, ELK Stack, Datadog, Splunk
- **Scripting Languages**: Python, Bash, Go, Ruby, PowerShell, Node.js
- **Version Control**: Git, GitHub, GitLab, GitFlow
- **Security**: Vulnerability Scanning, Secrets Management, HashiCorp Vault, IAM
- **Networking**: VPC, Load Balancing, DNS, Service Mesh, Istio
- **Databases**: MySQL, PostgreSQL, MongoDB, Redis, DynamoDB
- **Soft Skills**: Communication, Collaboration, Problem Solving, Leadership

### Cloud Engineering Keywords (`cloud.json`)
- **Core Concepts**: Cloud Computing, Cloud Architecture, IaC, Multi-Cloud, Serverless
- **AWS Services**: EC2, S3, Lambda, RDS, CloudFormation, EKS, CloudWatch (40+ services)
- **Azure Services**: VMs, Blob Storage, Functions, AKS, Cosmos DB, Azure Monitor (30+ services)
- **GCP Services**: Compute Engine, Cloud Storage, BigQuery, GKE, Cloud Functions (25+ services)
- **Containerization**: Kubernetes, Docker, Helm, Container Registry
- **Infrastructure as Code**: Terraform, CloudFormation, ARM Templates, Bicep
- **CI/CD**: Jenkins, GitLab CI/CD, GitHub Actions, Azure DevOps, Cloud Build
- **Monitoring**: CloudWatch, Azure Monitor, Cloud Operations, Prometheus, Grafana
- **Networking**: VPC, Load Balancing, CDN, Firewall, VPN, Direct Connect
- **Security**: IAM, KMS, Encryption, WAF, Compliance (GDPR, HIPAA, SOC 2)
- **Databases**: RDS, DynamoDB, Azure SQL, Cosmos DB, Cloud SQL, BigQuery
- **Soft Skills**: Problem Solving, Cost Optimization, Incident Response

## 🎯 Sumber Keywords

Keywords dikumpulkan dari riset industri terbaru (2024-2026):
- Forbes Tech Career Guides
- Indeed & LinkedIn Job Postings
- AWS, Azure, GCP Documentation
- DevOps.com Best Practices
- TealHQ Resume Optimization
- ProresAI ATS Analysis

## 💻 Cara Menggunakan

### 1. Load Keywords dalam PHP

```php
// Load DevOps keywords
$devopsKeywords = json_decode(
    file_get_contents(storage_path('app/keywords/devops.json')), 
    true
);

// Load Cloud keywords
$cloudKeywords = json_decode(
    file_get_contents(storage_path('app/keywords/cloud.json')), 
    true
);

// Access specific category
$cicdTools = $devopsKeywords['keywords']['ci_cd_tools'];
$awsServices = $cloudKeywords['keywords']['aws_services'];
```

### 2. Integrasi dengan CV Analysis

```php
// Get all keywords as flat array
function getAllKeywords($category = 'devops') {
    $file = storage_path("app/keywords/{$category}.json");
    $data = json_decode(file_get_contents($file), true);
    
    $allKeywords = [];
    foreach ($data['keywords'] as $subcategory => $keywords) {
        $allKeywords = array_merge($allKeywords, $keywords);
    }
    
    return array_unique($allKeywords);
}

// Compare CV with keywords
$cvContent = "..."; // Your CV content
$keywords = getAllKeywords('devops');

$found = [];
$missing = [];

foreach ($keywords as $keyword) {
    if (stripos($cvContent, $keyword) !== false) {
        $found[] = $keyword;
    } else {
        $missing[] = $keyword;
    }
}
```

### 3. Combine DevOps + Cloud Keywords

```php
$allKeywords = array_merge(
    getAllKeywords('devops'),
    getAllKeywords('cloud')
);

$uniqueKeywords = array_unique($allKeywords);
```

## 🔄 Update Keywords

Keywords di-update secara berkala berdasarkan:
1. Trend industri terbaru
2. Job posting analysis
3. Feedback dari CV analysis
4. Update teknologi dan tools baru

**Last Updated**: 2026-02-11  
**Version**: 1.0.0

## 📈 Statistik

- **Total DevOps Keywords**: ~200
- **Total Cloud Keywords**: ~300
- **Total Combined**: ~500 unique keywords
- **Categories**: 20+ subcategories
- **Cloud Providers**: AWS, Azure, GCP

## 🎓 Sertifikasi yang Disebutkan

### AWS
- AWS Certified Solutions Architect

### Azure
- Microsoft Certified: Azure Solutions Architect Expert

### GCP
- Google Associate Cloud Engineer
- Professional Cloud Architect

## 📝 Catatan

- Keywords ini **case-insensitive** untuk matching
- Beberapa keywords memiliki alias (contoh: "K8s" = "Kubernetes")
- Soft skills sama pentingnya dengan technical skills untuk ATS
- Sesuaikan keywords dengan job description yang spesifik

## 🚀 Best Practices

1. **Jangan keyword stuffing** - Gunakan secara natural dalam konteks
2. **Prioritas 20-30 keywords** utama yang sesuai dengan target role
3. **Include both technical + soft skills**
4. **Variasi format** - Gunakan singkatan dan full name (CI/CD, Continuous Integration)
5. **Context matters** - Tunjukkan experience dengan keywords, bukan hanya list

## 📧 Maintenance

File JSON ini bersifat static dan dapat diedit manual sesuai kebutuhan. Untuk menambah keyword baru:

1. Edit file `devops.json` atau `cloud.json`
2. Tambahkan keyword ke subcategory yang sesuai
3. Update `keyword_count` di `index.json`
4. Update `last_updated` di `index.json`

---

**Created**: 2026-02-11  
**Source**: Internet Research + Industry Best Practices  
**Maintained by**: Portfolio Project
