/* ---------- Angular Core ---------- */
import {
  Component,
  Input,
  Output,
  EventEmitter,
  ChangeDetectionStrategy,
  signal,
  computed
} from '@angular/core';
import { CommonModule } from '@angular/common';

/* ---------- Third-party Modules ---------- */
import { TranslateModule } from '@ngx-translate/core';

/* ---------- Interfaces ---------- */
import { ITripHeaderConfig } from './trip-header.interface';

/* ---------- SVG Icons ---------- */
import { DownloadPdfIconComponent } from '../svg-icons/download-pdf-icon/download-pdf-icon.component';
import { MapPinHeartIconComponent } from '../svg-icons/map-pin-heart-icon/map-pin-heart-icon.component';

/**
 * Trip Header Component
 * @description Displays trip title, date range, and action buttons (PDF download, Map)
 * @example
 * <app-trip-header
 *   [config]="tripHeaderConfig"
 *   (downloadPdf)="onDownloadPdf()"
 *   (mapClick)="onMapClick()">
 * </app-trip-header>
 */
@Component({
  selector: 'app-trip-header',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    DownloadPdfIconComponent,
    MapPinHeartIconComponent
  ],
  templateUrl: './trip-header.component.html',
  styleUrls: ['./trip-header.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripHeaderComponent {
  /** ---------- Signals ---------- */
  readonly config = signal<ITripHeaderConfig>({
    title: '',
    dateRange: '',
    showDownloadPdf: true,
    showMapButton: true,
    customClass: '',
    isRtl: true
  });

  /** ---------- Computed Signals ---------- */
  readonly headerClasses = computed(() => {
    const baseClass = 'trip-header';
    const rtlClass = this.config().isRtl ? `${baseClass}--rtl` : '';
    const customClass = this.config().customClass || '';
    return [baseClass, rtlClass, customClass].filter(Boolean).join(' ');
  });

  /** ---------- Inputs ---------- */
  @Input({ required: true }) set headerConfig(value: ITripHeaderConfig) {
    this.config.set({ ...this.config(), ...value });
  }

  /** ---------- Outputs ---------- */
  @Output() readonly downloadPdf = new EventEmitter<void>();
  @Output() readonly mapClick = new EventEmitter<void>();
  @Output() readonly saveTrip = new EventEmitter<void>();

  /** ---------- Methods ---------- */

  /**
   * Handle download PDF button click
   * @description Emits downloadPdf event with proper accessibility
   */
  protected onDownloadPdf(): void {
    this.downloadPdf.emit();
  }

  /**
   * Handle map button click
   * @description Emits mapClick event with proper accessibility
   */
  protected onMapClick(): void {
    this.mapClick.emit();
  }

  /**
   * Handle save trip button click
   * @description Emits saveTrip event with proper accessibility
   */
  protected onSaveTrip(): void {
    this.saveTrip.emit();
  }
}


