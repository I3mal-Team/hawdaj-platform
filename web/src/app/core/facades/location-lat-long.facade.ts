import { Injectable, signal, computed, inject } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { PLATFORM_ID } from '@angular/core';
import { Observable, of, switchMap, tap, catchError } from 'rxjs';

import { AlertsService } from 'src/app/services/alerts.service';
import { LocationLatLongService, IUpdateLocationRequestDto } from '../services';
import { GeoLocationService } from 'src/app/modules/shared/services/geo-location';

@Injectable({
  providedIn: 'root',
})
export class LocationLatLongFacade {
  // ------------------ Injected Services ------------------
  private readonly locationService = inject(LocationLatLongService);
  private readonly geoLocationService = inject(GeoLocationService);
  private readonly alertsService = inject(AlertsService);
  private readonly platformId = inject(PLATFORM_ID);

  // ------------------ Signals ------------------
  readonly isUpdating = signal(false);
  readonly updateErrorMessage = signal<string | null>(null);
  readonly updateSuccessMessage = signal<string | null>(null);
  readonly lastUpdatedLocation = signal<IUpdateLocationRequestDto | null>(null);

  readonly hasError = computed(() => !!this.updateErrorMessage());

  // ------------------ Public API ------------------
  /**
   * Update user location using GeoLocationService
   * ✔ SSR-safe
   * ✔ Cached-first
   * ✔ Async fallback
   */
  updateLocation(): Observable<null | unknown> {
    this.startUpdateState();

    return this.getLatLng$().pipe(
      switchMap(coords => {
        if (!coords) {
          throw new Error('Location unavailable');
        }

        const payload: IUpdateLocationRequestDto = {
          lat: coords.lat,
          lng: coords.lng,
        };

        return this.locationService.updateLocation(payload).pipe(
          tap(() => this.onUpdateSuccess(payload))
        );
      }),
      catchError(err => this.onUpdateError(err))
    );
  }

  // ------------------ Internal Helpers ------------------

  /**
   * Resolve latitude & longitude
   * - SSR → null
   * - Browser → cached sync
   * - Fallback → async fetch
   */
  private getLatLng$(): Observable<{ lat: number; lng: number } | null> {
    if (!isPlatformBrowser(this.platformId)) {
      return of(null);
    }

    // FAST PATH (cached / TransferState)
    const cached = this.geoLocationService.getCachedLocationSync();
    if (cached) {
      return of({
        lat: cached.latitude,
        lng: cached.longitude,
      });
    }

    // ASYNC FALLBACK
    return this.geoLocationService.getCurrentLocation().pipe(
      switchMap(loc =>
        loc
          ? of({ lat: loc.latitude, lng: loc.longitude })
          : of(null)
      ),
      catchError(() => of(null))
    );
  }

  // ------------------ State Handlers ------------------

  private startUpdateState(): void {
    this.isUpdating.set(true);
    this.updateErrorMessage.set(null);
    this.updateSuccessMessage.set(null);
  }

  private onUpdateSuccess(payload: IUpdateLocationRequestDto): void {
    this.isUpdating.set(false);
    this.lastUpdatedLocation.set(payload);
    this.updateSuccessMessage.set('Location updated successfully');
    // this.alertsService.openToast('success', 'Location updated successfully');
  }

  private onUpdateError(err: any): Observable<null> {
    const msg =
      err?.error?.message ||
      err?.message ||
      'Error updating location';

    this.isUpdating.set(false);
    this.updateErrorMessage.set(msg);
    this.alertsService.openToast('error', msg);

    return of(null);
  }
}
