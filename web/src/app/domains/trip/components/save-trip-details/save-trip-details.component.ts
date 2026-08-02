/* ---------- Angular Core ---------- */
import {
  ChangeDetectionStrategy,
  ChangeDetectorRef,
  Component,
  inject,
  PLATFORM_ID,
  signal,
  OnInit,
  OnDestroy,
  effect
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';

/* ---------- Third-party Modules ---------- */
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { ToastModule } from 'primeng/toast';

/* ---------- Services & Facade ---------- */
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { AuthService } from 'src/app/services/auth.service';
import { TripsFacade } from '../../facades';
import { TripRoutesEnum } from '../../constants';
import { TripPdfService } from '../../services/trip-pdf.service';

/* ---------- Shared Components ---------- */
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { Banner2Component } from 'src/app/Common/layout/banner-2/banner-2.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { TripHeaderComponent, ITripHeaderConfig } from 'src/app/shared/components/trip-header';
import { TripDayDetailComponent, ITripDayData } from 'src/app/shared/components/trip-day-detail';

/* ---------- Directives & Constants ---------- */
import { environment } from 'src/environments/environment';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

/* ---------- Modals ---------- */
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { SaveTripModalComponent } from 'src/app/components/my-trips/components/save-trip-modal/save-trip-modal.component';
import { ComingSoonModalComponent } from 'src/app/modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { TripEmailModalComponent } from 'src/app/components/my-trips/components/trip-email-modal/trip-email-modal.component';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { NoSaveTripDataComponent } from 'src/app/components/my-trips/components/save-trip-v2/no-save-trip-data/no-save-trip-data.component';

/* ---------- Types & Interfaces ---------- */
import { IMapLocation } from '../../dtos';
import { ILoggedUserData } from 'src/app/components/applications/dots/applications';

@Component({
  selector: 'app-save-trip-details',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ToastModule,
    SkeletonComponent,
    Banner2Component,
    NewFooterComponent,
    TripHeaderComponent,
    TripDayDetailComponent
  ],
  templateUrl: './save-trip-details.component.html',
  styleUrls: ['./save-trip-details.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class SaveTripDetailsComponent implements OnInit, OnDestroy {
  /* ---------- Private/Protected Properties (Injected) ---------- */
  private readonly platformId: Object = inject(PLATFORM_ID);
  protected readonly tripsFacade = inject(TripsFacade);
  private readonly publicService = inject(PublicService);
  private readonly alertsService = inject(AlertsService);
  private readonly authService = inject(AuthService);
  private readonly router = inject(Router);
  private readonly activatedRoute = inject(ActivatedRoute);
  private readonly cdr = inject(ChangeDetectorRef);
  private readonly dialogService = inject(DialogService);
  private readonly tripPdfService = inject(TripPdfService);

  /* ---------- Private/Protected Properties (Class-defined) ---------- */
  protected tripToken: string = this.activatedRoute.snapshot.params['token'];
  protected fullUrl: string = '';
  protected currentLoginInformation: ILoggedUserData | null = null;
  protected notShowPage: boolean = false;
  protected commingSoonImage: string = 'assets/images-v2/pages/place-details/hawdaj-bot.png';
  private regenerateErrorCheckInterval: any;

  /* ---------- Signals (Public for Template access) ---------- */
  readonly preparedTripData = this.tripsFacade.preparedTripData;
  readonly isLoading = this.tripsFacade.isLoadingTripDetails;
  readonly errorMessage = this.tripsFacade.tripDetailsErrorMessage;
  readonly statusMessage = this.tripsFacade.tripDetailsStatusMessage;
  readonly tripPlaces = signal<any[]>([]);
  readonly mapLocations = signal<IMapLocation[]>([]);
  readonly currentLanguage = signal<string>('ar');
  readonly tripHeaderConfig = signal<ITripHeaderConfig>({
    title: '',
    dateRange: '',
    showDownloadPdf: true,
    showMapButton: true,
    isRtl: true,
    showSaveButton: false
  });
  readonly tripDaysData = signal<ITripDayData[]>([]);
  readonly isRegenerating = signal<boolean>(false);

  constructor() {
    this.setupDataEffects();
    this.setupLoggingEffects();
    this.setupNoDataDialogEffect();
  }

  ngOnInit(): void {
    this.initializeBrowserSpecificData();
    this.loadPreparedTripDetails();
  }

  ngOnDestroy(): void {
    // Clean up error check interval
    if (this.regenerateErrorCheckInterval) {
      clearInterval(this.regenerateErrorCheckInterval);
    }
    // Reset header and footer visibility
    this.publicService.hideHeaderFooter.next(false);
  }

  /* ---------- Private Methods (Effects) ---------- */

  /** Sets up effects to update tripPlaces and mapLocations when tripData changes. */
  private setupDataEffects(): void {
    effect(() => {
      const trip = this.preparedTripData();
      if (trip && 'enhanced_data' in trip) {
        const enhancedData = (trip as any).enhanced_data || [];

        // Transform enhanced_data to ITripDayData format
        const transformedDays: ITripDayData[] = enhancedData.map((day: any) => {
          const morningPlaces = day.morning?.places || [];
          const eveningPlaces = day.evening?.places || [];
          const totalPlaces = morningPlaces.length + eveningPlaces.length;

          // Extract city information from day or places
          const derivedCity =
            day.city ||
            morningPlaces[0]?.city ||
            eveningPlaces[0]?.city ||
            day.region ||
            {};
          const cityName = derivedCity?.name || '';
          const cityDescription = derivedCity?.description || '';
          const defaultRegionName =
            trip.start_region?.name ||
            this.publicService.translateTextFromJson('trip.tripProgram') ||
            '';
          const defaultRegionDescription =
            this.publicService.translateTextFromJson('trip.happyTrip') ||
            '';

          return {
            dayNumber: day.day_number,
            dayTitle: `${this.publicService.translateTextFromJson('saveTrip.day')} ${day.day_number}`,
            date: day.date,
            placesCount: totalPlaces,
            regionName: cityName || defaultRegionName,
            regionDescription: cityDescription || defaultRegionDescription,
            cityName,
            cityDescription,
            city: derivedCity,
            morning: morningPlaces.length > 0 ? {
              type: 'morning' as const,
              title: this.publicService.translateTextFromJson('saveTrip.morning'),
              description: day.morning?.description || '',
              places: morningPlaces.map((p: any) => this.transformPlaceToCard(p))
            } : undefined,
            evening: eveningPlaces.length > 0 ? {
              type: 'evening' as const,
              title: this.publicService.translateTextFromJson('saveTrip.evening'),
              description: day.evening?.description || '',
              places: eveningPlaces.map((p: any) => this.transformPlaceToCard(p))
            } : undefined
          };
        });

        this.tripDaysData.set(transformedDays);

        // Flatten the places from morning and evening of all days
        const places = enhancedData.flatMap((day: any) => [
          ...(day.morning?.places || []),
          ...(day.evening?.places || [])
        ]);
        this.tripPlaces.set(places);

        // Build map locations with all required data
        const locations = places.map((p: any) => ({
          lat: p.lat,
          lng: p.long,
          name: this.getPlaceNameByLocale(p, this.currentLanguage()),
          title: this.getPlaceNameByLocale(p, this.currentLanguage()),
          image: `${p.image}`,
          address_name: p.address || '',
          review: p.review || 0,
          type: p.type || 'place',
          rate: p.rate || 0,
          slug: p.slug || ''
        }));
        this.mapLocations.set(locations);
        // Update trip header config
        this.updateTripHeaderConfig();
      } else {
        this.tripPlaces.set([]);
        this.mapLocations.set([]);
        this.tripDaysData.set([]);
      }
    }, { allowSignalWrites: true });
  }

  /** Transform place from API to IPlaceCard format */
  private transformPlaceToCard(place: any): any {
    return {
      id: String(place.id),
      slug: place.slug || '',
      title: this.getPlaceNameByLocale(place, this.currentLanguage()),
      description: place.description || '',
      imageUrl: `${place.image}`,
      type: place.type || 'place',
      location: place.address || '',
      rating: place.rate || 0,
      reviewsCount: place.review || 0
    };
  }

  /** Updates trip header configuration with trip data */
  private updateTripHeaderConfig(): void {
    const trip = this.preparedTripData();
    if (!trip) return;

    const startDate = trip.start_date ? new Date(trip.start_date).toLocaleDateString(this.currentLanguage()) : '';
    const endDate = trip.end_date ? new Date(trip.end_date).toLocaleDateString(this.currentLanguage()) : '';

    this.tripHeaderConfig.set({
      title: this.publicService.translateTextFromJson('saveTrip.tripProgram'),
      dateRange: `${startDate} - ${endDate}`,
      showDownloadPdf: true,
      showMapButton: true,
      showSaveButton: true,
      isRtl: this.currentLanguage() === 'ar'
    });
  }

  /** Sets up an effect to log all trip details signals for debugging. */
  private setupLoggingEffects(): void {
    effect(() => {
      console.log('SaveTripDetailsComponent | Prepared Trip Signals:');
      console.log('SaveTripDetailsComponent | preparedTripData:', this.preparedTripData());
      console.log('SaveTripDetailsComponent | isLoadingTripDetails:', this.isLoading());
      console.log('SaveTripDetailsComponent | tripDetailsErrorMessage:', this.errorMessage());
      console.log('SaveTripDetailsComponent | tripDetailsStatusMessage:', this.statusMessage());
    }, { allowSignalWrites: true });
  }

  /** Sets up an effect to show NoSaveTripDataComponent dialog when no data is available. */
  private setupNoDataDialogEffect(): void {
    effect(() => {
      const trip = this.preparedTripData();
      const loading = this.isLoading();

      // Only check when loading is complete and we're in browser
      if (!loading && isPlatformBrowser(this.platformId)) {
        if (!trip || (trip && !('enhanced_data' in trip))) {
          if (this.notShowPage === false) {
            this.notShowPage = true;
            // Hide header and footer
            this.publicService.hideHeaderFooter.next(true);
            const ref = this.dialogService.open(NoSaveTripDataComponent, {
              width: '35%',
              closable: false,
              data: {
                token: this.tripToken
              }
            });
          }
        } else {
          this.notShowPage = false;
          // Show header and footer
          this.publicService.hideHeaderFooter.next(false);
        }
      }
    }, { allowSignalWrites: true });
  }

  /* ---------- Private Methods (Initialization) ---------- */

  /** Initializes data that must be run only on the browser platform. */
  private initializeBrowserSpecificData(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage.set(this.publicService.getCurrentLanguage());
      this.currentLoginInformation = this.retrieveUserLoginData();
      this.fullUrl = window.location.href;
    }
  }

  /** Retrieves user login data from local storage. */
  private retrieveUserLoginData(): ILoggedUserData | null {
    if (isPlatformBrowser(this.platformId)) {
      const userLoginData = window?.localStorage?.getItem(keys?.userLoginData);
      if (userLoginData) {
        try {
          return JSON.parse(userLoginData)?.user as ILoggedUserData;
        } catch (e) {
          console.error("Error parsing user login data from localStorage", e);
          return null;
        }
      }
    }
    return null;
  }

  /** Calls the facade to load the prepared trip details using the token. */
  private loadPreparedTripDetails(): void {
    if (!this.tripToken) return;
    this.tripsFacade.loadPreparedTripByToken(this.tripToken);
  }

  /** Download PDF */
  protected downloadPDF(): void {
    if (!isPlatformBrowser(this.platformId)) return;

    const header = this.tripHeaderConfig();
    const days = this.tripDaysData();

    if (!days || days.length === 0) {
      this.alertsService.openToast('info', this.publicService.translateTextFromJson('general.noDataFound'));
      return;
    }

    this.tripPdfService.openTripPlanPdf(header, days, this.currentLanguage());
  }

  /** Show trip on map */
  protected showTripOnMap(): void {
    if (!isPlatformBrowser(this.platformId)) return;

    const locations = this.mapLocations();
    if (!locations || locations.length === 0) {
      this.alertsService.openToast('info', this.publicService.translateTextFromJson('general.noDataFound'));
      return;
    }

    const ref = this.dialogService.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: locations,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });

    ref.onClose.subscribe((res: any) => {
      this.publicService.toggleBodyScroll(true);
    });
  }

  /** Open save trip modal */
  protected openSaveTripModal(): void {
    if (!this.authService.isLoggedIn()) {
      this.alertsService.openToast('info', this.publicService.translateTextFromJson('general.pleaseLogin'));
      return;
    }

    const trip = this.preparedTripData();
    if (!trip) return;

    // Convert tripDaysData to the format expected by SaveTripModalComponent
    const formattedTripPlaces = this.convertTripDaysToPlacesFormat();

    const ref = this.dialogService.open(SaveTripModalComponent, {
      width: '35%',
      styleClass: 'auth-dialog confirm-delete-trip',
      data: {
        tripData: trip,
        tripPlaces: formattedTripPlaces,
        token: this.tripToken
      }
    });

    ref.onClose.subscribe((res: any) => {
      if (res?.isSave === true) {
        this.publicService.toggleBodyScroll(true);
        if (isPlatformBrowser(this.platformId)) {
          window.localStorage.removeItem(keys.saveTripData);
          window.localStorage.removeItem(keys.prepareStepData);
        }
        // Navigate to trips list
        this.router.navigate(['/trip/list']);
      }
    });
  }

  /** Convert tripDaysData to the format expected by SaveTripModalComponent */
  private convertTripDaysToPlacesFormat(): any[] {
    const result: any[] = [];

    this.tripDaysData().forEach((day, index) => {
      const allPlaces: any[] = [];

      // Add morning places
      if (day.morning?.places) {
        day.morning.places.forEach(place => {
          const imagePath = place.imageUrl?.replace(``, '') || place.imageUrl;

          allPlaces.push({
            id: Number(place.id),
            slug: place.slug,
            title: place.title,
            type: place.type,
            image: imagePath,
            address: place.location,
            rate: place.rating || 0,
            review: place.reviewsCount || 0
          });
        });
      }

      // Add evening places
      if (day.evening?.places) {
        day.evening.places.forEach(place => {
          const imagePath = place.imageUrl?.replace(``, '') || place.imageUrl;

          allPlaces.push({
            id: Number(place.id),
            slug: place.slug,
            title: place.title,
            type: place.type,
            image: imagePath,
            address: place.location,
            rate: place.rating || 0,
            review: place.reviewsCount || 0
          });
        });
      }

      if (allPlaces.length > 0) {
        result.push({
          placesItems: allPlaces,
          indexNumber: day.dayNumber,
          date: day.date
        });
      }
    });

    return result;
  }

  /** Regenerate trip */
  protected regenerateTrip(): void {
    if (!isPlatformBrowser(this.platformId)) return;

    // Get prepare_token from current trip token
    if (!this.tripToken) {
      this.alertsService.openToast('error', this.publicService.translateTextFromJson('general.invalidTripData'));
      return;
    }

    // Set loading state to true
    this.isRegenerating.set(true);

    // Clear any existing error check interval
    if (this.regenerateErrorCheckInterval) {
      clearInterval(this.regenerateErrorCheckInterval);
    }

    // Check for errors periodically
    this.regenerateErrorCheckInterval = setInterval(() => {
      const errorMsg = this.tripsFacade.reprepareTripErrorMessage();
      if (errorMsg) {
        this.handleRegenerateError(errorMsg);
        clearInterval(this.regenerateErrorCheckInterval);
      }
    }, 100);

    // Call reprepareTrip through facade
    this.tripsFacade.reprepareTrip(
      { prepare_token: this.tripToken },
      (data) => {
        // Clear error check interval
        if (this.regenerateErrorCheckInterval) {
          clearInterval(this.regenerateErrorCheckInterval);
        }

        if (data?.token) {
          this.tripToken = data.token;

          this.router.navigate([`/${TripRoutesEnum.TRIP1}/${TripRoutesEnum.SAVE_TRIP_DETAILS}`, data.token], { replaceUrl: true });

          this.isRegenerating.set(false);

          this.cdr.markForCheck();
        } else {
          this.handleRegenerateError(this.publicService.translateTextFromJson('general.errorOccurred'));
        }
      },
      (error) => {
        // Clear error check interval
        if (this.regenerateErrorCheckInterval) {
          clearInterval(this.regenerateErrorCheckInterval);
        }
        this.handleRegenerateError(error);
      }
    );
  }

  /** Handle regenerate error */
  private handleRegenerateError(errorMessage: string): void {
    this.isRegenerating.set(false);
    this.alertsService.openToast('error', errorMessage);
    this.cdr.markForCheck();
  }

  /** Add trip to calendar */
  protected addToCalendar(): void {
    const ref = this.dialogService.open(ComingSoonModalComponent, {
      width: '35%',
      styleClass: '',
      header: '',
      dismissableMask: true,
      data: {
        image: this.commingSoonImage,
      }
    });

    ref.onClose.subscribe((res: any) => {
      this.publicService.toggleBodyScroll(true);
    });
  }

  /** Email trip to someone */
  protected emailTripTo(): void {
    const trip = this.preparedTripData();
    if (!trip) return;

    const ref = this.dialogService.open(TripEmailModalComponent, {
      width: '45%',
      styleClass: 'auth-dialog confirm-delete-trip',
      data: {
        tripData: trip,
        tripPlaces: this.tripDaysData()
      }
    });

    ref.onClose.subscribe((res: any) => {
      if (res?.isSave === true) {
        this.publicService.toggleBodyScroll(true);
      }
    });
  }

  /** Get place name by locale */
  private getPlaceNameByLocale(place: any, locale: string): string {
    if (!place?.translations || place.translations.length === 0) {
      return place?.title || '';
    }
    const translation = place.translations.find((t: any) => t.locale === locale);
    return translation?.title || place?.title || '';
  }

  /** Track by function for days list */
  protected trackByDayIndex(index: number, day: ITripDayData): number {
    return day.dayNumber;
  }

  /** Handle place click - Navigate to place details */
  protected onPlaceClick(place: any): void {
    if (place?.slug) {
      const link = this.getLinkByType(place?.type, place?.slug);
      this.router.navigate([link]);
    }
  }

  /** Get link by place type */
  private getLinkByType(type: string, slug: string): string {
    switch (type) {
      case 'trip':
        return '/trips/' + slug;
      case 'event':
      case 'events':
        return '/events/event-details/' + slug;
      case 'story':
      case 'stories':
        return '/stories/' + slug;
      case 'store':
      case 'stores':
        return '/stores/details/' + slug;
      case 'restaurant':
      case 'restaurants':
        return '/restaurants/' + slug;
      case 'place':
      case 'places':
        return '/places/details/' + slug;
      default:
        return '/places/details/' + slug;
    }
  }

  /** Handle delete place from trip */
  protected onDeletePlace(place: any, dayNumber: number, periodType: 'morning' | 'evening'): void {
    const ref = this.dialogService.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
      data: {
        title: this.publicService.translateTextFromJson('trip.areYouSureToDeletePlace'),
        description: this.publicService.translateTextFromJson('trip.beSureToDeletePlace')
      }
    });

    ref.onClose.subscribe((res: any) => {
      if (res?.isConfirmed) {
        this.deletePlaceFromTrip(place, dayNumber, periodType);
      }
    });
  }

  /** Delete place from trip data */
  private deletePlaceFromTrip(place: any, dayNumber: number, periodType: 'morning' | 'evening'): void {
    // Update tripDaysData signal
    const updatedDays = this.tripDaysData().map(day => {
      if (day.dayNumber === dayNumber) {
        const period = periodType === 'morning' ? day.morning : day.evening;
        if (period) {
          const updatedPlaces = period.places.filter(p => p.id !== place.id);

          // If no places left, return day without this period
          if (updatedPlaces.length === 0) {
            if (periodType === 'morning') {
              return { ...day, morning: undefined };
            } else {
              return { ...day, evening: undefined };
            }
          }

          // Update period with filtered places
          if (periodType === 'morning') {
            return {
              ...day,
              morning: { ...period, places: updatedPlaces },
              placesCount: (day.evening?.places?.length || 0) + updatedPlaces.length
            };
          } else {
            return {
              ...day,
              evening: { ...period, places: updatedPlaces },
              placesCount: (day.morning?.places?.length || 0) + updatedPlaces.length
            };
          }
        }
      }
      return day;
    });

    this.tripDaysData.set(updatedDays);
    this.alertsService.openToast('success', this.publicService.translateTextFromJson('general.deletePlace'));
    this.cdr.markForCheck();
  }
}

