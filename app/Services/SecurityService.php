<?php

namespace App\Services;

class SecurityService
{
    /**
     * Clean and sanitize user HTML input to prevent XSS attacks while retaining safe rich text formatting.
     */
    public static function cleanHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // 1. Remove dangerous script tags and content
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // 2. Remove iframe, object, embed, applet, link, meta, style tags
        $html = preg_replace('/<(iframe|object|embed|applet|link|meta|style)\b[^>]*>(.*?)<\/\1>/is', '', $html);
        $html = preg_replace('/<(iframe|object|embed|applet|link|meta|style)\b[^>]*\/?>/is', '', $html);

        // 3. Remove dangerous inline event attributes (e.g. onerror=, onload=, onclick=)
        $html = preg_replace('/on[a-z]+\s*=\s*(["\'])(.*?)\1/i', '', $html);
        $html = preg_replace('/on[a-z]+\s*=\s*[^"\'\s>]+/i', '', $html);

        // 4. Remove javascript: URIs
        $html = preg_replace('/href\s*=\s*(["\'])\s*javascript:[^\1]*?\1/i', 'href="#"', $html);
        $html = preg_replace('/src\s*=\s*(["\'])\s*javascript:[^\1]*?\1/i', '', $html);

        return trim($html);
    }
}
