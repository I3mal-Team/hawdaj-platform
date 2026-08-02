import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { TranslateService } from '@ngx-translate/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpErrorResponse
} from '@angular/common/http';
import { Observable, EMPTY } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';

import { PublicService } from 'src/app/modules/shared/services/public.service';
import { GeoLocationService } from 'src/app/modules/shared/services/geo-location';
import { GEO_LOCATION_CONFIG } from 'src/app/modules/shared/configs/geo-location';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

@Injectable()
export class HttpInterceptorService implements HttpInterceptor {
  private readonly isBrowser: boolean;

  constructor(
    @Inject(PLATFORM_ID) platformId: Object,
    private translateService: TranslateService,
    private publicService: PublicService,
    private geoLocationService: GeoLocationService
  ) {
    this.isBrowser = isPlatformBrowser(platformId);
  }

  intercept(
    request: HttpRequest<unknown>,
    next: HttpHandler
  ): Observable<HttpEvent<unknown>> {

    // Avoid recursion for IP-based location services
    if (GEO_LOCATION_CONFIG.IP_LOCATION_SERVICES.some(url => request.url.includes(url))) {
      return next.handle(request);
    }

    // SSR or offline → base headers only
    if (!this.isBrowser || !navigator.onLine) {
      return next.handle(request.clone({
        setHeaders: this.getBaseHeaders()
      }));
    }

    /**
     * 1️⃣ FAST PATH: sync cached location (NO blocking)
     */
    const headers = this.getBaseHeaders();
    const cachedLocation = this.geoLocationService.getCachedLocationSync();

    if (cachedLocation) {
      this.attachLocationHeaders(headers, cachedLocation);
    } else {
      /**
       * 2️⃣ BACKGROUND refresh (fire & forget)
       */
      this.geoLocationService.refreshLocation()
        .pipe(catchError(() => EMPTY))
        .subscribe();
    }

    return next.handle(request.clone({ setHeaders: headers }));
  }

  // ---------------- PRIVATE ----------------

  private attachLocationHeaders(headers: Record<string, string>, location: any): void {
    headers[GEO_LOCATION_CONFIG.HEADERS.LATITUDE] = String(location.latitude);
    headers[GEO_LOCATION_CONFIG.HEADERS.LONGITUDE] = String(location.longitude);

    if (location.accuracy != null) {
      headers[GEO_LOCATION_CONFIG.HEADERS.ACCURACY] = String(location.accuracy);
    }

    headers[GEO_LOCATION_CONFIG.HEADERS.SOURCE] = location.source;
  }

  private getBaseHeaders(): Record<string, string> {
    const headers: Record<string, string> = {};

    // Language
    const lang = this.getCurrentLanguage();
    if (lang) {
      headers['locale'] = lang;
    }

    // Auth token
    const token = this.getAuthToken();
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    // Static API token
    headers['accept-tokenapi'] =
      'k*RQ=z*2K4@WnA6d2h_&z39?bE9kDszkwh4XTePpU_vA';

    return headers;
  }

  private getCurrentLanguage(): string {
    if (!this.isBrowser) return '';

    const current = this.publicService.getCurrentLanguage();
    if (current) return current;

    const browserLang = this.translateService.getBrowserLang() || 'en';
    localStorage.setItem(keys.language, browserLang);
    return browserLang;
  }

  private getAuthToken(): string | null {
    if (!this.isBrowser) return null;

    try {
      const data = JSON.parse(localStorage.getItem(keys.userLoginData) || '{}');
      return data?.token ?? null;
    } catch {
      return null;
    }
  }
}
