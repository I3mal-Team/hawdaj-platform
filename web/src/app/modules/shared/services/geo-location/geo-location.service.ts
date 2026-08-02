import { Injectable, Inject, PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import {
  TransferState
} from '@angular/platform-browser';
import { Observable, from, throwError } from 'rxjs';
import { catchError, map, shareReplay, timeout, tap } from 'rxjs/operators';
import { GEO_LOCATION_CONFIG } from '../../configs/geo-location';
import { getTransferState, removeTransferState } from '../../utils';


export interface IGeoDeviceLocation {
  latitude: number;
  longitude: number;
  accuracy?: number;
  timestamp: number;
  source: 'geolocation' | 'ip' | 'cached' | 'transfer';
}

@Injectable({ providedIn: 'root' })
export class GeoLocationService {
  private locationRequest$?: Observable<IGeoDeviceLocation>;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private transferState: TransferState
  ) { }

  // ---------- PUBLIC ----------

  getCachedLocationSync(): IGeoDeviceLocation | null {
    if (!this.isBrowser()) return null;

    // 1️⃣ TransferState (SSR → Browser hydration)
    const state = getTransferState<IGeoDeviceLocation | null>(
      this.transferState,
      GEO_LOCATION_CONFIG.STORAGE_KEYS.DEVICE_LOCATION,
      null
    );

    if (state) {
      removeTransferState(
        this.transferState,
        GEO_LOCATION_CONFIG.STORAGE_KEYS.DEVICE_LOCATION
      );
      return { ...state, source: 'transfer' };
    }

    // 2️⃣ LocalStorage fallback
    try {
      const raw = localStorage.getItem(GEO_LOCATION_CONFIG.STORAGE_KEYS.DEVICE_LOCATION);
      const time = localStorage.getItem(GEO_LOCATION_CONFIG.STORAGE_KEYS.LOCATION_TIMESTAMP);

      if (!raw || !time) return null;
      if (Date.now() - Number(time) > GEO_LOCATION_CONFIG.CACHE_DURATION) return null;

      return { ...JSON.parse(raw), source: 'cached' };
    } catch {
      return null;
    }
  }

  refreshLocation(): Observable<IGeoDeviceLocation> {
    this.clearCache();
    return this.getCurrentLocation();
  }

  getCurrentLocation(): Observable<IGeoDeviceLocation> {
    if (!this.isBrowser()) {
      return throwError(() => new Error('SSR: location unavailable'));
    }

    if (!this.locationRequest$) {
      this.locationRequest$ = this.fetchLocationWithFallback().pipe(
        timeout(GEO_LOCATION_CONFIG.REQUEST_TIMEOUT),
        tap(location => this.persist(location)),
        shareReplay(1),
        catchError(err => {
          this.locationRequest$ = undefined;
          return throwError(() => err);
        })
      );
    }

    return this.locationRequest$;
  }

  clearCache(): void {
    if (!this.isBrowser()) return;

    localStorage.removeItem(GEO_LOCATION_CONFIG.STORAGE_KEYS.DEVICE_LOCATION);
    localStorage.removeItem(GEO_LOCATION_CONFIG.STORAGE_KEYS.LOCATION_TIMESTAMP);
    this.locationRequest$ = undefined;
  }

  // ---------- INTERNAL ----------

  private fetchLocationWithFallback(): Observable<IGeoDeviceLocation> {
    return from(this.tryGeolocation()).pipe(
      catchError(() => this.tryCachedLocation()),
      catchError(() => this.tryIpLocation()),
      map(location => ({
        ...location,
        timestamp: Date.now()
      }))
    );
  }

  private tryGeolocation(): Promise<IGeoDeviceLocation> {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) return reject();

      navigator.geolocation.getCurrentPosition(
        pos => resolve({
          latitude: pos.coords.latitude,
          longitude: pos.coords.longitude,
          accuracy: pos.coords.accuracy,
          timestamp: Date.now(),
          source: 'geolocation'
        }),
        reject,
        {
          enableHighAccuracy: true,
          timeout: GEO_LOCATION_CONFIG.GEOLOCATION_TIMEOUT,
          maximumAge: GEO_LOCATION_CONFIG.CACHE_DURATION
        }
      );
    });
  }

  private tryCachedLocation(): Observable<IGeoDeviceLocation> {
    const cached = this.getCachedLocationSync();
    return cached
      ? from([cached])
      : throwError(() => new Error('No cached location'));
  }

  private tryIpLocation(): Observable<IGeoDeviceLocation> {
    return from(fetch(GEO_LOCATION_CONFIG.IP_LOCATION_SERVICES[0]).then(r => r.json())).pipe(
      map(data => ({
        latitude: data.latitude,
        longitude: data.longitude,
        timestamp: Date.now(),
        source: 'ip'
      }))
    );
  }

  private persist(location: IGeoDeviceLocation): void {
    if (!this.isBrowser()) return;

    localStorage.setItem(
      GEO_LOCATION_CONFIG.STORAGE_KEYS.DEVICE_LOCATION,
      JSON.stringify(location)
    );
    localStorage.setItem(
      GEO_LOCATION_CONFIG.STORAGE_KEYS.LOCATION_TIMESTAMP,
      Date.now().toString()
    );
  }

  private isBrowser(): boolean {
    return isPlatformBrowser(this.platformId);
  }
}
