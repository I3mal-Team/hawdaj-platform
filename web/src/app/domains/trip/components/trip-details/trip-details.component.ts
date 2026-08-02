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
import { TripsFacade } from '../../facades';

/* ---------- Shared Components ---------- */
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { Banner2Component } from 'src/app/Common/layout/banner-2/banner-2.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ShareSocialComponent } from 'src/app/Common/component/share-social/share-social.component';
import { TripHeaderComponent, ITripHeaderConfig } from 'src/app/shared/components/trip-header';
import { TripDayDetailComponent, ITripDayData } from 'src/app/shared/components/trip-day-detail';

/* ---------- Directives & Constants ---------- */
import { environment } from 'src/environments/environment';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';

/* ---------- Modals ---------- */
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';

/* ---------- Types & Interfaces ---------- */
import { IMapLocation } from '../../dtos';
import { ILoggedUserData } from 'src/app/components/applications/dots/applications';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { PrepearTripStepperComponent } from '../prepear-trip-stepper';
import { TripPdfService } from '../../services/trip-pdf.service';

@Component({
  selector: 'app-trip-details',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ToastModule,
    SkeletonComponent,
    Banner2Component,
    NewFooterComponent,
    ShareSocialComponent,
    TripHeaderComponent,
    TripDayDetailComponent
  ],
  templateUrl: './trip-details.component.html',
  styleUrls: ['./trip-details.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripDetailsComponent implements OnInit, OnDestroy {
  /* ---------- Private/Protected Properties (Injected) ---------- */
  private readonly platformId: Object = inject(PLATFORM_ID);
  protected readonly tripsFacade = inject(TripsFacade);
  private readonly publicService = inject(PublicService);
  private readonly alertsService = inject(AlertsService);
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

  /* ---------- Signals (Public for Template access) ---------- */
  readonly tripData = this.tripsFacade.tripData;
  readonly isLoading = this.tripsFacade.isLoadingTripDetails;
  readonly isDeleting = this.tripsFacade.isDeletingTripDetails;
  readonly errorMessage = this.tripsFacade.tripDetailsErrorMessage;
  readonly statusMessage = this.tripsFacade.tripDetailsStatusMessage;
  readonly tripPlaces = signal<any[]>([]); // Using 'any' as the exact place type is very large
  readonly mapLocations = signal<IMapLocation[]>([]);
  readonly currentLanguage = signal<string>('ar');
  readonly tripHeaderConfig = signal<ITripHeaderConfig>({
    title: 'برنامج رحلة',
    dateRange: '25/04/2024 - 25/05/2024',
    showDownloadPdf: true,
    showMapButton: true,
    isRtl: true
  });
  readonly tripDaysData = signal<ITripDayData[]>([]);

  constructor() {
    this.setupDataEffects();
  }

  ngOnInit(): void {
    this.initializeBrowserSpecificData();
    this.loadTripDetails();
  }

  ngOnDestroy(): void {
    // No explicit cleanup needed; signals/effects auto-cleanup
  }

  /* ---------- Private Methods (Effects) ---------- */

  /** Sets up effects to update tripPlaces and mapLocations when tripData changes. */
  private setupDataEffects(): void {
    effect(() => {
      const trip = this.tripData();
      if (trip) {
        console.log(trip);
        // Support both old 'enhanced_data' and new 'days' format
        const daysData = (trip as any).days || (trip as any).enhanced_data || [];
        // Transform to ITripDayData format
        const transformedDays: ITripDayData[] = daysData.map((day: any) => {
          const morningPlaces = day.morning?.places || [];
          const eveningPlaces = day.evening?.places || [];
          const totalPlaces = morningPlaces.length + eveningPlaces.length;
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
        const places = daysData.flatMap((day: any) => [
          ...(day.morning?.places || []),
          ...(day.evening?.places || [])
        ]);
        this.tripPlaces.set(places);

        // Build map locations with all required data
        const locations = places.map((p: any) => ({
          lat: p.lat,
          lng: p.long,
          name: p.name || p.description?.substring(0, 50) || '',
          title: p.name || p.description?.substring(0, 50) || '',
          image: p.image ? `${p.image}` : '',
          address_name: p.city || '',
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
    // Handle city and region (can be string or object)
    const cityName = typeof place.city === 'string' ? place.city : (place.city?.name || '');
    const regionName = typeof place.region === 'string' ? place.region : (place.region?.name || '');

    return {
      id: String(place.id),
      slug: place.slug || '',
      title: place.name || place.title || place.description?.substring(0, 100) || 'مكان',
      description: place.description || '',
      imageUrl: place.image || 'not-found/no-img.svg',
      type: place.type || 'place',
      location: `${cityName}, ${regionName}`.trim().replace(/^,|,$/g, '').replace(/^, |, $/g, ''),
      latitude: place.lat ?? place.latitude ?? null,
      longitude: place.long ?? place.lng ?? place.longitude ?? null,
      rating: place.rate || 0,
      reviewsCount: place.review || 0
    };
  }

  /** Updates trip header configuration with trip data */
  private updateTripHeaderConfig(): void {
    const trip = this.tripData();
    if (!trip) return;

    const startDate = trip.start_date ? new Date(trip.start_date).toLocaleDateString(this.currentLanguage()) : '';
    const endDate = trip.end_date ? new Date(trip.end_date).toLocaleDateString(this.currentLanguage()) : '';

    this.tripHeaderConfig.set({
      title: this.publicService.translateTextFromJson('saveTrip.tripProgram'),
      dateRange: `${startDate} - ${endDate}`,
      showDownloadPdf: true,
      showMapButton: true,
      isRtl: this.currentLanguage() === 'ar'
    });
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
          // Parse the stored data and access the 'user' property which holds ILoggedUserData
          return JSON.parse(userLoginData)?.user as ILoggedUserData;
        } catch (e) {
          console.error("Error parsing user login data from localStorage", e);
          return null;
        }
      }
    }
    return null;
  }

  /** Calls the facade to load the trip details using the token. */
  private loadTripDetails(): void {
    if (!this.tripToken) return;
    this.tripsFacade.loadSavedTripByToken(this.tripToken);
  }


  /** ---------- Create Trip Action ---------- */
  protected startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
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

  /** Edit trip */
  protected editTrip(): void {
    this.router.navigate(['/trips/edit', this.tripToken]);
  }

  /** Delete trip */
  protected deleteTrip(): void {
    const ref = this.dialogService.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
    });

    ref.onClose?.subscribe((res: any) => {
      if (res?.isConfirmed && this.tripToken) {
        this.tripsFacade.deleteTrip(this.tripToken, () => {
          this.alertsService.openToast('success', this.publicService.translateTextFromJson('general.deleteTrip'));
          // Navigate to trips list after successful deletion
          this.router.navigate(['/trip/list']);
        });
      }
    });
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
}
