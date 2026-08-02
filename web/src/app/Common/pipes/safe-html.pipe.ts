import { Pipe, PipeTransform, SecurityContext, inject } from '@angular/core';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';

/**
 * `<div [innerHTML]="item?.description | safeHtml"></div>` — renders stored
 * rich-text descriptions as markup instead of printing the raw tags.
 *
 * The value is run through Angular's HTML sanitizer first, so scripts, inline
 * event handlers and javascript: URLs are removed before we trust it.
 */
@Pipe({
  name: 'safeHtml',
  standalone: true,
})
export class SafeHtmlPipe implements PipeTransform {
  private readonly sanitizer = inject(DomSanitizer);

  transform(value: string | null | undefined): SafeHtml {
    if (value == null || value === '') return '';

    const sanitized = this.sanitizer.sanitize(SecurityContext.HTML, String(value)) ?? '';
    return this.sanitizer.bypassSecurityTrustHtml(sanitized);
  }
}
