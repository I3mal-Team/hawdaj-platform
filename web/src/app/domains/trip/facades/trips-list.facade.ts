import { Injectable, signal } from '@angular/core';
import { TripsService } from '../services';
import { IEnhancedTripItem, ITripDetailsResponseDto, ITripItem, ITripsResponseDto, IPrepareTripRequestDto, IPrepareTripResponseDto, ISaveTripRequestDto, ISaveTripResponseDto, IReprepareTripRequestDto, IReprepareTripResponseDto } from '../dtos';
import { savedTripDetailsWithTokenData } from '../data';

@Injectable({ providedIn: 'root' })
export class TripsFacade {
  private readonly tripsService = new TripsService();

  // ------------------ Trips List Signals ------------------
  readonly tripsList = signal<ITripItem[]>([]);
  readonly isLoadingTripsList = signal(false);
  readonly isDeletingTripFromList = signal(false);
  readonly tripsListErrorMessage = signal<string | null>(null);
  readonly tripsListStatusMessage = signal<string | null>(null);
  readonly currentPage = signal(1);
  readonly perPage = signal(10);
  readonly total = signal(0);
  readonly search = signal<string | null>(null);

  // ------------------ Trip Details Signals ------------------
  readonly tripData = signal<IEnhancedTripItem | null>(null);
  readonly isLoadingTripDetails = signal(false);
  readonly isDeletingTripDetails = signal(false);
  readonly tripDetailsErrorMessage = signal<string | null>(null);
  readonly tripDetailsStatusMessage = signal<string | null>(null);

  // ------------------ Prepare Trip Signals ------------------
  readonly preparedTripData = signal<IEnhancedTripItem | null>(null);
  readonly isPreparingTrip = signal(false);
  readonly prepareTripErrorMessage = signal<string | null>(null);
  readonly prepareTripStatusMessage = signal<string | null>(null);

  // ------------------ Save Trip Signals ------------------
  readonly savedTripData = signal<any | null>(null);
  readonly isSavingTrip = signal(false);
  readonly saveTripErrorMessage = signal<string | null>(null);
  readonly saveTripStatusMessage = signal<string | null>(null);

  // ------------------ Re-prepare Trip Signals ------------------
  readonly repreparedTripData = signal<IEnhancedTripItem | null>(null);
  readonly isRepreparingTrip = signal(false);
  readonly reprepareTripErrorMessage = signal<string | null>(null);
  readonly reprepareTripStatusMessage = signal<string | null>(null);

  // ------------------ Trips List Methods ------------------
  loadTrips(page = 1, perPage = 10, append = false, search?: string, vehicleId?: number | null) {
    this.isLoadingTripsList.set(true);
    this.tripsListErrorMessage.set(null);
    this.tripsListStatusMessage.set(null);

    this.tripsService.getAllTrips(page, perPage, search, vehicleId).subscribe({
      next: (res: ITripsResponseDto) => {
        if (res.code === 200) {
          const trips = res.data.trips.map(trip => ({
            ...trip,
            startTime: trip.start_date,
            endTime: trip.end_date,
            // Map for backward compatibility
            days: trip.total_days,
            date: trip.start_date,
            item_per_day: trip.places_per_day,
            region1Object: trip.start_region,
            region2Object: trip.end_region,
            // Add default image if not present
            image: trip.image || 'assets/images-v2/pages/my-trips/default-trip.png'
          }));

          if (append) {
            this.tripsList.update(old => [...old, ...trips]);
          } else {
            this.tripsList.set(trips);
          }

          this.currentPage.set(res.data.pagination.current_page);
          this.total.set(res.data.pagination.total);
          this.tripsListStatusMessage.set('Trips loaded successfully');
        } else {
          this.tripsListErrorMessage.set(res.message || 'Error loading trips');
        }
        this.isLoadingTripsList.set(false);
      },
      error: (err) => {
        this.tripsListErrorMessage.set(err?.message || 'Error loading trips');
        this.isLoadingTripsList.set(false);
      }
    });
  }

  // ------------------ Delete Trip Methods ------------------
  deleteTrip(token?: string, onSuccess?: () => void) {
    if (!token) return;
    this.isDeletingTripFromList.set(true);
    this.tripsListErrorMessage.set(null);
    this.tripsListStatusMessage.set(null);

    this.tripsService.deleteTrip(token).subscribe({
      next: (res: any) => {
        if (res?.code === 200) {
          this.tripsListStatusMessage.set('Trip deleted successfully');
          onSuccess?.();
        } else {
          this.tripsListErrorMessage.set(res?.message || 'Error deleting trip');
        }
        this.isDeletingTripFromList.set(false);
      },
      error: (err) => {
        this.tripsListErrorMessage.set(err?.message || 'Error deleting trip');
        this.isDeletingTripFromList.set(false);
      }
    });
  }

  // ------------------ Saved Trip Details Methods ------------------
  loadSavedTripByToken(token: string) {
    if (!token) return;
    this.isLoadingTripDetails.set(true);
    this.tripDetailsErrorMessage.set(null);
    this.tripDetailsStatusMessage.set(null);

    this.tripsService.getTripByToken(token).subscribe({
      next: (res: ITripDetailsResponseDto) => {
        if (res?.code === 200) {
          this.tripData.set(res.data);
          this.tripDetailsStatusMessage.set('Trip loaded successfully');
        } else {
          this.tripDetailsErrorMessage.set(res?.message || 'Error loading trip');
        }
        this.isLoadingTripDetails.set(false);
      },
      error: (err) => {
        this.tripDetailsErrorMessage.set(err?.message || 'Error loading trip');
        this.tripData.set({
          ...savedTripDetailsWithTokenData.data,
          total_days: Number(savedTripDetailsWithTokenData.data.total_days)
        } as IEnhancedTripItem); // For testing purposes
        this.isLoadingTripDetails.set(false);
      }
    });
  }

  // ------------------ Prepared Trip Details Methods ------------------
  loadPreparedTripByToken(token: string) {
    if (!token) return;
    this.isLoadingTripDetails.set(true);
    this.tripDetailsErrorMessage.set(null);
    this.tripDetailsStatusMessage.set(null);

    this.tripsService.getPreparedTripByToken(token).subscribe({
      next: (res: IPrepareTripResponseDto) => {
        if (res?.code === 200) {
          this.preparedTripData.set(res.data);
          this.tripDetailsStatusMessage.set('Prepared trip loaded successfully');
        } else {
          this.tripDetailsErrorMessage.set(res?.message || 'Error loading prepared trip');
        }
        this.isLoadingTripDetails.set(false);
      },
      error: (err) => {
        this.tripDetailsErrorMessage.set(err?.message || 'Error loading prepared trip');
        this.isLoadingTripDetails.set(false);
      }
    });
  }
  // ------------------ Prepare Trip Methods ------------------
  prepareTrip(request: IPrepareTripRequestDto, onSuccess?: (data: IEnhancedTripItem) => void) {
    this.isPreparingTrip.set(true);
    this.prepareTripErrorMessage.set(null);
    this.prepareTripStatusMessage.set(null);

    this.tripsService.prepareTrip(request).subscribe({
      next: (res: IPrepareTripResponseDto) => {
        if (res.code === 200) {
          this.preparedTripData.set(res.data);
          this.prepareTripStatusMessage.set(res.message || 'Trip prepared successfully');
          onSuccess?.(res.data);
        } else {
          this.prepareTripErrorMessage.set(res.message || 'Error preparing trip');
        }
        this.isPreparingTrip.set(false);
      },
      error: (err) => {
        const backendMessage = err;
        console.log('err', err);
        this.prepareTripErrorMessage.set(backendMessage || err?.message || 'Error preparing trip');
        this.isPreparingTrip.set(false);
      }
    });
  }

  // ------------------ Save Trip Methods ------------------
  saveTrip(request: ISaveTripRequestDto, onSuccess?: (data: any) => void) {
    this.isSavingTrip.set(true);
    this.saveTripErrorMessage.set(null);
    this.saveTripStatusMessage.set(null);

    this.tripsService.saveTrip(request).subscribe({
      next: (res: ISaveTripResponseDto) => {
        if (res?.code === 201 || res?.code === 200) {
          this.savedTripData.set(res.data);
          this.saveTripStatusMessage.set(res?.message || 'Trip saved successfully');

          if (onSuccess) {
            onSuccess(res.data);
          }
        } else {
          this.saveTripErrorMessage.set(res?.message || 'Error saving trip');
        }
        this.isSavingTrip.set(false);
      },
      error: (err) => {
        this.saveTripErrorMessage.set(err || 'Error saving trip');
        this.isSavingTrip.set(false);
      }
    });
  }

  // ------------------ Re-prepare Trip Methods ------------------
  reprepareTrip(request: IReprepareTripRequestDto, onSuccess?: (data: IEnhancedTripItem) => void, onError?: (error: string) => void) {
    this.isRepreparingTrip.set(true);
    this.reprepareTripErrorMessage.set(null);
    this.reprepareTripStatusMessage.set(null);

    this.tripsService.reprepareTrip(request).subscribe({
      next: (res: IReprepareTripResponseDto) => {
        if (res?.code === 200) {
          this.repreparedTripData.set(res.data);
          this.preparedTripData.set(res.data); // Also update preparedTripData
          this.reprepareTripStatusMessage.set(res?.message || 'Trip reprepared successfully');

          if (onSuccess) {
            onSuccess(res.data);
          }
        } else {
          const errorMessage = res?.message || 'Error repreparing trip';
          this.reprepareTripErrorMessage.set(errorMessage);
          if (onError) {
            onError(errorMessage);
          }
        }
        this.isRepreparingTrip.set(false);
      },
      error: (err) => {
        const errorMessage = err?.error?.message || err?.message || 'Error repreparing trip';
        this.reprepareTripErrorMessage.set(errorMessage);
        this.isRepreparingTrip.set(false);
        if (onError) {
          onError(errorMessage);
        }
      }
    });
  }

  // ------------------ Utilities ------------------
  private addDaysToDate(date: string, days: string | number): string {
    const inputDate = new Date(date);
    inputDate.setDate(inputDate.getDate() + Number(days));
    return inputDate.toISOString().split('T')[0];
  }
}
