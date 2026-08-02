import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { SecurityContext } from '@angular/core';

export function sanitizeUrl(url: string, sanitizer: DomSanitizer): string {
    let safeUrl: SafeResourceUrl;

    if (!url.startsWith('http') && !url.startsWith('https')) {
        safeUrl = sanitizer.bypassSecurityTrustResourceUrl('about:blank');
    } else {
        safeUrl = sanitizer.bypassSecurityTrustResourceUrl(url);
    }

    return sanitizer.sanitize(SecurityContext.RESOURCE_URL, safeUrl) || 'about:blank';
}
