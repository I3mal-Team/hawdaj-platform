import { DOCUMENT } from '@angular/common';
import { Inject, Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class CanonicalUrlService {
  private canonicalUrl: string = '';

  constructor(@Inject(DOCUMENT) private document: Document) { }

  updateCanonicalLink(newCanonicalUrl: string) {
    const canonicalLink: any = this.document?.querySelector('link[rel="canonical"]');
    // console.log('canonical link', canonicalLink);
    if (canonicalLink) {
      // console.log('Canonical tag updated', canonicalLink);
      canonicalLink?.setAttribute('href', newCanonicalUrl);
    }
  }
  setCanonicalUrl(url: string): void {
    this.canonicalUrl = url;
  }

  getCanonicalUrl(): string {
    return this.canonicalUrl;
  }
}
