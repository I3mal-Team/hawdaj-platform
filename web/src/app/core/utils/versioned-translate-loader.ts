import { HttpClient } from '@angular/common/http';
import { TranslateLoader } from '@ngx-translate/core';
import { Observable } from 'rxjs';
import { TranslationVersionUtil } from './translation-version.util';

/**
 * Versioned Translate HTTP Loader
 * 
 * Extends the standard TranslateHttpLoader to append a version query parameter
 * to all translation file URLs. This prevents browser/CDN caching issues.
 * 
 * The version is generated once at app startup using Date.now() and remains
 * constant throughout the app lifecycle, ensuring consistent cache busting.
 */
export class VersionedTranslateHttpLoader implements TranslateLoader {
  constructor(
    private http: HttpClient,
    private prefix: string = './assets/i18n/',
    private suffix: string = '.json'
  ) {}

  /**
   * Get translations for the specified language
   * Appends version query parameter to bust cache: ?v=<timestamp>
   */
  getTranslation(lang: string): Observable<any> {
    const version = TranslationVersionUtil.getVersion();
    const url = `${this.prefix}${lang}${this.suffix}?v=${version}`;
    return this.http.get(url);
  }
}

/**
 * Factory function for creating VersionedTranslateHttpLoader
 * Used in TranslateModule configuration
 */
export function createVersionedTranslateLoader(http: HttpClient): VersionedTranslateHttpLoader {
  return new VersionedTranslateHttpLoader(http, './assets/i18n/', '.json');
}

