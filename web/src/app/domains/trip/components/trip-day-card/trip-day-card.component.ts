/* ---------- Angular Core ---------- */
import {
  ChangeDetectionStrategy,
  Component,
  Input,
  OnInit,
  inject,
  PLATFORM_ID,
  signal
} from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Router, RouterModule } from '@angular/router';

/* ---------- Third-party Modules ---------- */
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';

/* ---------- Services & Facade ---------- */
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';

/* ---------- Shared Components ---------- */
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';

/* ---------- Types & Interfaces ---------- */
import { IEnhancedDay, IEnhancedPlace } from '../../dtos';

/* ---------- Pipes ---------- */
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-trip-day-card',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TranslateModule,
    NgOptimizedImage,
    StripHtmlPipe
  ],
  templateUrl: './trip-day-card.component.html',
  styleUrls: ['./trip-day-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripDayCardComponent implements OnInit {
  /* ---------- Private/Protected Properties (Injected) ---------- */
  private readonly router = inject(Router);
  private readonly dialogService = inject(DialogService);
  protected readonly publicService = inject(PublicService);
  private readonly alertsService = inject(AlertsService);
  protected readonly translateService = inject(TranslateService);
  private readonly platformId = inject(PLATFORM_ID);

  /* ---------- Input Properties (Protected/Readonly for template) ---------- */
  @Input({ required: true }) dayData!: IEnhancedDay;
  @Input({ required: true }) dayIndex!: number;
  @Input() tripToken?: string;

  /* ---------- Signals (Protected for template) ---------- */
  protected readonly currentLanguage = signal<string>('ar');
  private readonly isBrowser = signal<boolean>(false);
  protected readonly expandedPeriod = signal<string | null>(null);

  ngOnInit(): void {
    this.initializeComponent();
  }

  /* ---------- Protected Methods (Template Access) ---------- */

  /**
   * Get localized place title
   */
  protected getLocalizedTitle(place: IEnhancedPlace): string {
    if (!this.isBrowser()) return place.title;

    const currentLang = this.currentLanguage();
    const translation = place.translations?.find(t => t.locale === currentLang);
    return translation?.title || place.title;
  }

  /**
   * Get localized place description
   */
  protected getLocalizedDescription(place: IEnhancedPlace): string {
    if (!this.isBrowser()) return place.description;

    const currentLang = this.currentLanguage();
    const translation = place.translations?.find(t => t.locale === currentLang);
    return translation?.description || place.description;
  }

  /**
   * Get formatted date for display
   */
  protected getFormattedDate(): string {
    if (!this.dayData?.date || !this.isBrowser()) return '';

    try {
      const date = new Date(this.dayData.date);
      // Use toLocaleDateString as a safer alternative to Intl.DateTimeFormat with this pattern
      return date.toLocaleDateString(this.currentLanguage(), {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    } catch {
      return this.dayData.date;
    }
  }

  /**
   * Toggle period expansion
   */
  protected togglePeriod(period: string): void {
    this.expandedPeriod.set(this.expandedPeriod() === period ? null : period);
  }

  /**
   * Navigate to place details
   */
  protected navigateToPlace(place: IEnhancedPlace, event: Event): void {
    event.preventDefault();
    event.stopPropagation();

    if (place.slug) {
      this.router.navigate(['/places/details', place.slug], {
        state: { returnUrl: this.router.url }
      });
    } else {
      this.alertsService.openToast('info', this.translateService.instant('general.invalidPlaceData'));
    }
  }

  /**
   * Show place on map
   */
  protected showPlaceOnMap(place: IEnhancedPlace, event: Event): void {
    event.preventDefault();
    event.stopPropagation();

    const mapLocation = {
      lat: place.lat,
      lng: place.long,
      name: this.getLocalizedTitle(place),
      image: `${place.image}`,
      address_name: this.getAddressName(place),
      review: place.review,
      type: place.type,
      rate: place.rate || 0,
      slug: place.slug
    };

    const ref = this.dialogService.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: [mapLocation],
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });

    ref.onClose.subscribe(() => {
      if (this.isBrowser()) {
        this.publicService.toggleBodyScroll(true);
      }
    });
  }

  /**
   * Handle image loading errors
   */
  protected onImageError(event: Event, place: IEnhancedPlace): void {
    const imgElement = event.target as HTMLImageElement;
    imgElement.src = 'assets/images-v2/pages/no-result/place-no-result.png';
    imgElement.alt = this.getLocalizedTitle(place);
  }

  /**
   * TrackBy function for ngFor performance
   */
  protected trackByPlaceId(index: number, place: IEnhancedPlace): number {
    return place.id;
  }

  /**
   * Get period translation key
   */
  protected getPeriodTranslationKey(period: string): string {
    return period === 'morning' ? 'trip.morning' : 'trip.evening';
  }

  /**
   * Check if period has places
   */
  protected hasPlaces(period: 'morning' | 'evening' | string): boolean {
    // Access dayData input property, which is protected
    return !!this.dayData[period]?.places?.length;
  }

  /* ---------- Private Methods (Internal Logic) ---------- */

  /**
   * Initializes browser-specific settings.
   */
  private initializeComponent(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isBrowser.set(true);
      this.currentLanguage.set(this.publicService.getCurrentLanguage());
    }
  }

  /**
   * Get formatted address name
   */
  private getAddressName(place: IEnhancedPlace): string {
    const parts = [];
    // Handle region (can be string or object)
    if (place.region) {
      if (typeof place.region === 'string') {
        parts.push(place.region);
      } else if (place.region.name) {
        parts.push(place.region.name);
      }
    }
    // Handle city (can be string or object)
    if (place.city) {
      if (typeof place.city === 'string') {
        parts.push(place.city);
      } else if (place.city.name) {
        parts.push(place.city.name);
      }
    }
    return parts.join(', ');
  }
}
