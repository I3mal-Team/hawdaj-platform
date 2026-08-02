/* ---------- Angular Core ---------- */
import {
  Component,
  Input,
  Output,
  EventEmitter,
  ChangeDetectionStrategy,
  signal,
  inject
} from '@angular/core';
import { CommonModule } from '@angular/common';

/* ---------- Third-party Modules ---------- */
import { TranslateModule, TranslateService } from '@ngx-translate/core';

/* ---------- Interfaces ---------- */
import { ITimePeriodData } from '../trip-day-detail/trip-day-detail.interface';

/* ---------- Constants ---------- */
import { environment } from 'src/environments/environment';
import { SvgIconComponent } from '../svg-icon';

/* ---------- Pipes ---------- */
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';
import { PublicService } from 'src/app/modules/shared/services/public.service';

/**
 * Time Period Card Component
 * @description Displays morning or evening activities with places
 */
@Component({
  selector: 'app-time-period-card',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    SvgIconComponent,
    StripHtmlPipe
  ],
  templateUrl: './time-period-card.component.html',
  styleUrls: ['./time-period-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TimePeriodCardComponent {
  /** ---------- Injected Services ---------- */
  private readonly translateService = inject(TranslateService);
  private readonly publicService = inject(PublicService);

  /** ---------- Signals ---------- */
  readonly periodData = signal<ITimePeriodData | null>(null);
  readonly currentLanguage = signal<string>('ar');
  readonly isFullWidthLayout = signal<boolean>(false);

  /** ---------- Inputs ---------- */
  @Input({ required: true }) set data(value: ITimePeriodData) {
    this.periodData.set(value);
  }

  @Input() set language(value: string) {
    this.currentLanguage.set(value);
  }

  @Input() showDelete: boolean = false;

  @Input() set isFullWidth(value: boolean) {
    this.isFullWidthLayout.set(value);
  }

  /** ---------- Outputs ---------- */
  @Output() readonly placeClick = new EventEmitter<any>();
  @Output() readonly deletePlace = new EventEmitter<any>();

  /** Track by function for performance */
  protected trackByPlaceId(index: number, place: any): string {
    return place.id;
  }

  /** Handle place click */
  protected onPlaceClick(place: any): void {
    this.placeClick.emit(place);
  }

  /** Handle delete place */
  protected onDeletePlace(place: any, event: Event): void {
    event.stopPropagation();
    this.deletePlace.emit(place);
  }

  /** Get place name by locale */
  protected getPlaceName(place: any): string {
    return place?.title || '';
  }

  /** Get place address */
  protected getPlaceAddress(place: any): string {
    return place?.location || 'titles.saudiArabia';
  }

  /** Get place type translated to current language */
  protected getPlaceType(place: any): string {
    const type = place?.type || 'place';
    const key = `placeTypes.${type.toLowerCase()}`;

    // Use TranslateService to get translation from JSON files
    const translation = this.translateService.instant(key);

    // If translation not found, fallback to default
    return translation !== key ? translation : this.translateService.instant('placeTypes.place');
  }

  /** Get Google Maps link for a place */
  protected getPlaceMapLink(place: any): string | null {
    if (!place) {
      return null;
    }

    if (place.location) {
      return `https://www.google.com/maps/search/?api=1&query=${place.location}`;
    }

    const latitude = place.latitude ?? place.lat ?? place.latitude;
    const longitude = place.longitude ?? place.long ?? place.lng ?? place.longitude;

    if (
      (latitude || latitude === 0) &&
      (longitude || longitude === 0)
    ) {
      return this.publicService.createGoogleMapsLink(latitude, longitude);
    }

    return null;
  }
}


