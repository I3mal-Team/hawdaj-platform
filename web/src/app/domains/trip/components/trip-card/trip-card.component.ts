import {
  ChangeDetectionStrategy,
  Component,
  ElementRef,
  EventEmitter,
  inject,
  Input,
  Output,
  PLATFORM_ID,
  Renderer2,
  signal,
  OnInit,
  OnDestroy
} from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { Router } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { SvgIconComponent } from 'src/app/shared/components/svg-icon';
import { AlertsService } from 'src/app/services/alerts.service';
import { AuthService } from 'src/app/services/auth.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { environment } from 'src/environments/environment';
import { ITripItem } from '../../dtos';
import { TripRoutesEnum } from '../../constants';

@Component({
  selector: 'app-trip-card',
  standalone: true,
  imports: [
    CommonModule,
    NgOptimizedImage,
    TranslateModule,
    LazyLoadImageDirective,
    SvgIconComponent
  ],
  templateUrl: './trip-card.component.html',
  styleUrls: ['./trip-card.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class TripCardComponent implements OnInit, OnDestroy {
  @Input() item!: ITripItem;
  @Output() deleteClicked = new EventEmitter<void>();
  @Output() exploreClicked = new EventEmitter<void>();

  protected readonly collapse = signal<boolean>(false);
  protected readonly currentLanguage = signal<string>('ar');

  private readonly authService = inject(AuthService);
  private readonly alertsService = inject(AlertsService);
  private readonly publicService = inject(PublicService);
  private readonly router = inject(Router);
  private readonly renderer = inject(Renderer2);
  private readonly elementRef = inject(ElementRef);
  private readonly platformId = inject(PLATFORM_ID);

  private documentClickListener?: () => void;

  ngOnInit(): void {
    if (!this.item) return; // safety check
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage.set(this.publicService.getCurrentLanguage());
    }
    this.ensureDates();
  }

  ngOnDestroy(): void {
    if (this.documentClickListener) this.documentClickListener();
  }

  private ensureDates(): void {
    if (!this.item) return;
    if (!this.item.start_date) this.item.start_date = this.item.date;
    if (!this.item.end_date) {
      const start = new Date(this.item.start_date);
      const days = parseInt(this.item.days, 10) || 0;
      start.setDate(start.getDate() + days - 1);
      this.item.end_date = start.toISOString().split('T')[0];
    }
  }

  protected toggleDropdown(event: MouseEvent): void {
    event.stopPropagation();
    this.collapse.update(prev => !prev);
    this.addClickOutsideListener();
  }

  protected closeDropdown(): void {
    this.collapse.set(false);
  }

  private addClickOutsideListener(): void {
    if (!isPlatformBrowser(this.platformId) || this.documentClickListener) return;

    this.documentClickListener = this.renderer.listen('document', 'click', (event: MouseEvent) => {
      if (!this.elementRef.nativeElement.contains(event.target) && this.collapse()) {
        this.collapse.set(false);
      }
    });
  }

  protected onDeleteClick(): void {
    this.deleteClicked.emit();
    this.collapse.set(false);
  }

  protected exploreTrip(): void {
    if (this.item?.token) {
      this.router.navigate([`${TripRoutesEnum.TRIP1}`, this.item.token]);
      this.exploreClicked.emit();
      this.collapse.set(false);
    } else {
      this.alertsService.openToast(
        'info',
        this.publicService.translateTextFromJson('general.invalidTripData')
      );
    }
  }

  protected onImageError(event: Event): void {
    (event.target as HTMLImageElement).src = 'assets/images-v2/pages/no-result/place-no-result.png';
  }
}
