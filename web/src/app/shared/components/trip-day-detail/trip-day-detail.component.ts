/* ---------- Angular Core ---------- */
import {
  Component,
  Input,
  Output,
  EventEmitter,
  ChangeDetectionStrategy,
  signal,
  computed,
  inject,
  PLATFORM_ID
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';

/* ---------- Third-party Modules ---------- */
import { TranslateModule } from '@ngx-translate/core';
import { TabViewModule } from 'primeng/tabview';

/* ---------- Services ---------- */
import { PublicService } from 'src/app/modules/shared/services/public.service';

/* ---------- Interfaces ---------- */
import { ITripDayData, ITripDayConfig } from './trip-day-detail.interface';

/* ---------- Shared Components ---------- */
import { TimePeriodCardComponent } from '../time-period-card';
import { SvgIconComponent } from '../svg-icon';

/* ---------- Pipes ---------- */
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

/**
 * Trip Day Detail Component
 * @description Displays full day information including region, activities, and places
 * @example
 * <app-trip-day-detail
 *   [dayData]="tripDay"
 *   [config]="dayConfig">
 * </app-trip-day-detail>
 */
@Component({
  selector: 'app-trip-day-detail',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    TabViewModule,
    TimePeriodCardComponent,
    SvgIconComponent,
    StripHtmlPipe
  ],
  templateUrl: './trip-day-detail.component.html',
  styleUrls: ['./trip-day-detail.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripDayDetailComponent {
  private readonly platformId = inject(PLATFORM_ID);
  private readonly publicService = inject(PublicService);

  /** ---------- Signals ---------- */
  readonly dayData = signal<ITripDayData | null>(null);
  readonly config = signal<ITripDayConfig>({
    showPlaces: true,
    showRegionInfo: true,
    isRtl: true,
    customClass: ''
  });
  readonly allowDelete = signal<boolean>(true);

  /** ---------- Computed Signals ---------- */
  readonly componentClasses = computed(() => {
    const baseClass = 'trip-day-detail';
    const rtlClass = this.config().isRtl ? `${baseClass}--rtl` : '';
    const customClass = this.config().customClass || '';
    return [baseClass, rtlClass, customClass].filter(Boolean).join(' ');
  });

  readonly dayMetaInfo = computed(() => {
    const data = this.dayData();
    if (!data) return '';

    // Format the date
    const formattedDate = this.formatDate(data.date);

    // Get places text based on language
    const placesText = this.getCurrentLanguage() === 'ar' ? 'أماكن' : 'places';

    return `${formattedDate} - ${data.placesCount} ${placesText}`;
  });

  /** Check how many periods exist (morning/evening) */
  readonly periodsCount = computed(() => {
    const data = this.dayData();
    if (!data) return 0;
    let count = 0;
    if (data.morning) count++;
    if (data.evening) count++;
    return count;
  });

  /** Check if only one period exists (full width layout) */
  readonly isSinglePeriod = computed(() => this.periodsCount() === 1);

  /** Calculate total places count in the day (morning + evening) */
  readonly totalPlacesCount = computed(() => {
    const data = this.dayData();
    if (!data) return 0;
    let total = 0;
    if (data.morning?.places) total += data.morning.places.length;
    if (data.evening?.places) total += data.evening.places.length;
    return total;
  });

  /** Show delete button only if allowed and total places > 1 */
  readonly canShowDelete = computed(() => this.allowDelete() && this.totalPlacesCount() > 1);

  /** Active tab index for mobile view - writable signal to handle dynamic updates */
  readonly activeTabIndex = signal<number>(0);

  /** ---------- Inputs ---------- */
  @Input({ required: true }) set data(value: ITripDayData) {
    this.dayData.set(value);
    // Reset active tab to 0 whenever data changes to ensure first available tab is shown
    this.activeTabIndex.set(0);
  }
  ngOnInit(): void {
    console.log(this.dayData());
  }
  @Input() set dayConfig(value: ITripDayConfig) {
    this.config.set({ ...this.config(), ...value });
  }

  @Input() set enableDelete(value: boolean) {
    this.allowDelete.set(value);
  }

  /** ---------- Outputs ---------- */
  @Output() readonly placeClick = new EventEmitter<any>();
  @Output() readonly deletePlace = new EventEmitter<{ place: any, dayNumber: number, periodType: 'morning' | 'evening' }>();

  /** Handle place click from child component */
  protected onPlaceClick(place: any): void {
    this.placeClick.emit(place);
  }

  /** Handle delete place from child component */
  protected onDeletePlace(place: any, periodType: 'morning' | 'evening'): void {
    const dayNumber = this.dayData()?.dayNumber;
    if (dayNumber) {
      this.deletePlace.emit({ place, dayNumber, periodType });
    }
  }

  /** Get current language */
  protected getCurrentLanguage(): string {
    if (isPlatformBrowser(this.platformId)) {
      return this.publicService.getCurrentLanguage() || 'ar';
    }
    return 'ar';
  }

  /** Format date to readable format (e.g., "12 مارس 2025" or "12 March 2025") */
  private formatDate(dateString: string): string {
    if (!dateString) return '';

    try {
      const date = new Date(dateString);
      if (isNaN(date.getTime())) return dateString; // Return original if invalid

      const locale = this.getCurrentLanguage();
      const options: Intl.DateTimeFormatOptions = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        calendar: 'gregory'  // Force Gregorian calendar (Miladi) even for Arabic
      };

      // Map language codes to locale strings
      const localeMap: { [key: string]: string } = {
        'ar': 'ar-SA',
        'en': 'en-US',
        'ru': 'ru-RU',
        'zh': 'zh-CN'
      };

      // Use browser's Intl API for proper date formatting
      if (isPlatformBrowser(this.platformId)) {
        const localeString = localeMap[locale] || 'en-US';
        const formatter = new Intl.DateTimeFormat(localeString, options);
        return formatter.format(date);
      }

      return dateString;
    } catch (error) {
      return dateString;
    }
  }
}


