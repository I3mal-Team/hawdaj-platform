import { Component, Input, Output, EventEmitter, ChangeDetectionStrategy, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { IEnhancedPlace } from 'src/app/domains/trip/dtos';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-trip-place-card',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './trip-place-card.component.html',
  styleUrls: ['./trip-place-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripPlaceCardComponent {
  @Input({ required: true }) place!: IEnhancedPlace;
  @Input() showDelete: boolean = false;
  @Input() currentLanguage: string = 'ar';
  @Output() cardClick = new EventEmitter<IEnhancedPlace>();
  @Output() deleteClick = new EventEmitter<IEnhancedPlace>();

  protected onCardClick(): void {
    this.cardClick.emit(this.place);
  }

  protected onDeleteClick(event: Event): void {
    event.stopPropagation();
    this.deleteClick.emit(this.place);
  }

  protected getPlaceName(): string {
    if (!this.place?.translations || this.place.translations.length === 0) {
      return this.place?.title || '';
    }
    const translation = this.place.translations.find(t => t.locale === this.currentLanguage);
    return translation?.title || this.place?.title || '';
  }

  protected getPlaceAddress(): string {
    return this.place?.address || 'titles.saudiArabia';
  }
}

