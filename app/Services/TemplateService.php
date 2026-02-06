<?php

namespace App\Services;

class TemplateService
{
    public function getAvailableTemplates(): array
    {
        return config('cv-templates', []);
    }

    public function getTemplatePreview(string $template): string
    {
        $templates = $this->getAvailableTemplates();

        if (! isset($templates[$template])) {
            return '';
        }

        return $templates[$template]['preview'] ?? '';
    }

    public function validateTemplate(string $template): bool
    {
        $templates = $this->getAvailableTemplates();

        return isset($templates[$template]);
    }

    public function applyTemplate(string $template, array $data): string
    {
        if (! $this->validateTemplate($template)) {
            throw new \InvalidArgumentException("Template '{$template}' does not exist.");
        }

        return view($template, $data)->render();
    }

    public function getTemplateName(string $template): string
    {
        $templates = $this->getAvailableTemplates();

        if (! isset($templates[$template])) {
            return '';
        }

        return $templates[$template]['name'] ?? '';
    }

    public function getTemplateDescription(string $template): string
    {
        $templates = $this->getAvailableTemplates();

        if (! isset($templates[$template])) {
            return '';
        }

        return $templates[$template]['description'] ?? '';
    }
}
