<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Getting Started with Kubernetes: A Complete Guide',
                'slug' => 'getting-started-kubernetes',
                'excerpt' => 'Learn the fundamentals of Kubernetes and how to deploy your first application. This comprehensive guide covers pods, services, deployments, and more.',
                'content' => '# Getting Started with Kubernetes

Kubernetes has become the de-facto standard for container orchestration. In this guide, we\'ll explore the core concepts and deploy your first application.

## What is Kubernetes?

Kubernetes is an open-source container orchestration platform that automates deployment, scaling, and management of containerized applications.

## Core Concepts

### Pods
The smallest deployable unit in Kubernetes. A pod contains one or more containers.

### Services
Provides stable networking for pods, enabling load balancing and service discovery.

### Deployments
Manages pod replicas and updates, ensuring desired state is maintained.

## Deploying Your First App

\`\`\`yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-deployment
spec:
  replicas: 3
  selector:
    matchLabels:
      app: nginx
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
      - name: nginx
        image: nginx:1.21
        ports:
        - containerPort: 80
\`\`\`

## Conclusion

This is just the beginning. Kubernetes offers much more including ingress, configmaps, secrets, and more.',
                'category' => 'Kubernetes',
                'status' => 'published',
                'published_at' => '2024-01-15 10:00:00',
            ],
            [
                'title' => 'Building CI/CD Pipelines with GitHub Actions',
                'slug' => 'cicd-github-actions',
                'excerpt' => 'Learn how to automate your development workflow with GitHub Actions. Build, test, and deploy your applications automatically.',
                'content' => '# CI/CD with GitHub Actions

GitHub Actions makes it easy to automate all your software workflows.

## Workflow Basics

A workflow is a configurable automated process that runs one or more jobs.

## Example Workflow

\`\`\`yaml
name: CI/CD Pipeline

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: \'18\'
    
    - name: Install dependencies
      run: npm ci
    
    - name: Run tests
      run: npm test
    
    - name: Build
      run: npm run build
\`\`\`

## Best Practices

- Use caching for dependencies
- Run tests in parallel
- Deploy to staging first
- Use secrets for sensitive data

## Conclusion

GitHub Actions provides a powerful platform for automating your development workflows.',
                'category' => 'CI/CD',
                'status' => 'published',
                'published_at' => '2024-01-10 08:00:00',
            ],
            [
                'title' => 'Infrastructure as Code with Terraform',
                'slug' => 'terraform-infrastructure-code',
                'excerpt' => 'Discover how to manage your infrastructure as code using Terraform. Create reproducible, version-controlled infrastructure deployments.',
                'content' => '# Infrastructure as Code with Terraform

Terraform enables you to safely and predictably create, change, and improve infrastructure.

## Why Terraform?

- **Declarative**: Describe the desired state
- **Multi-cloud**: Works with any provider
- **Idempotent**: Safe to apply multiple times
- **State management**: Tracks resources

## Basic Example

\`\`\`hcl
resource "aws_instance" "web" {
  ami           = "ami-0c55b159cbfafe1f0"
  instance_type = "t2.micro"
  
  tags = {
    Name = "WebServer"
  }
}
\`\`\`

## Modules

Use modules to organize and reuse configuration:

\`\`\`hcl
module "vpc" {
  source = "terraform-aws-modules/vpc/aws"
  
  cidr = "10.0.0.0/16"
}
\`\`\`

## State Management

Store state remotely for team collaboration:

\`\`\`hcl
terraform {
  backend "s3" {
    bucket = "my-terraform-state"
    key    = "prod/terraform.tfstate"
    region = "us-east-1"
  }
}
\`\`\`

## Best Practices

- Use modules for reusability
- Version pin your providers
- Use variables for flexibility
- Implement proper state locking',
                'category' => 'Infrastructure as Code',
                'status' => 'published',
                'published_at' => '2024-01-05 14:00:00',
            ],
            [
                'title' => 'Monitoring Best Practices with Prometheus',
                'slug' => 'prometheus-monitoring-guide',
                'excerpt' => 'Learn how to set up comprehensive monitoring with Prometheus and Grafana. Track metrics, set up alerts, and gain visibility into your systems.',
                'content' => '# Monitoring with Prometheus

Prometheus is an open-source monitoring and alerting toolkit.

## Key Concepts

### Metrics
Numeric time-series data representing your system\'s behavior.

### Targets
Systems that Prometheus scrapes for metrics.

### PromQL
Query language for retrieving and processing metrics.

## Setting Up Prometheus

\`\`\`yaml
global:
  scrape_interval: 15s

scrape_configs:
  - job_name: \'node_exporter\'
    static_configs:
      - targets: [\'localhost:9100\']
\`\`\`

## Important Metrics to Track

- **RED Method**: Rate, Errors, Duration
- **USE Method**: Utilization, Saturation, Errors
- **Four Golden Signals**: Latency, Traffic, Errors, Saturation

## Alerting Rules

\`\`\`yaml
groups:
  - name: api_alerts
    rules:
      - alert: HighErrorRate
        expr: rate(errors[5m]) > 0.05
        for: 5m
        annotations:
          summary: "High error rate detected"
\`\`\`

## Best Practices

- Use labels effectively
- Set appropriate retention periods
- Create meaningful dashboards
- Test your alerting rules',
                'category' => 'Monitoring',
                'status' => 'published',
                'published_at' => '2023-12-28 10:00:00',
            ],
            [
                'title' => 'Docker Best Practices for Production',
                'slug' => 'docker-best-practices',
                'excerpt' => 'Master Docker containerization with these production-ready best practices. Build efficient, secure, and scalable Docker images.',
                'content' => '# Docker Best Practices

Building production-ready Docker images requires attention to several key areas.

## Image Optimization

### Use Multi-stage Builds

\`\`\`dockerfile
# Build stage
FROM node:18 AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Production stage
FROM node:18-alpine
WORKDIR /app
COPY --from=builder /app/dist ./dist
CMD ["node", "dist/index.js"]
\`\`\`

### Use Minimal Base Images

Prefer Alpine or distroless images over full OS images.

## Security Practices

### Run as Non-Root

\`\`\`dockerfile
FROM node:18-alpine
RUN addgroup -g 1001 -S nodejs
RUN adduser -S nodejs -u 1001
USER nodejs
\`\`\`

### Scan for Vulnerabilities

\`\`\`bash
docker scan myimage:latest
\`\`\`

## Best Practices Checklist

- [ ] Use multi-stage builds
- [ ] Run as non-root user
- [ ] Scan for vulnerabilities
- [ ] Use .dockerignore
- [ ] Pin dependency versions
- [ ] Implement health checks
- [ ] Use specific version tags
- [ ] Minimize layer count

## Conclusion

Following these practices will result in smaller, more secure, and more maintainable Docker images.',
                'category' => 'Docker',
                'status' => 'published',
                'published_at' => '2023-12-20 09:00:00',
            ],
        ];

        foreach ($posts as $postData) {
            $categoryName = $postData['category'];
            unset($postData['category']);

            $category = Category::where('name', $categoryName)->first();

            Post::updateOrCreate(
                ['slug' => $postData['slug']],
                array_merge($postData, ['category_id' => $category?->id])
            );
        }
    }
}
