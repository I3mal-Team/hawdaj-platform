import { Component, ElementRef, EventEmitter, HostListener, Inject, inject, Input, Output, PLATFORM_ID, Renderer2 } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { ListCardType } from './types-models/listType';
import { TranslateModule } from '@ngx-translate/core';
import { environment } from 'src/environments/environment';
import { Router, RouterModule } from '@angular/router';
import { item } from './interface/list-card';
import { AuthService } from 'src/app/services/auth.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { StripHtmlPipe } from '../../pipes/strip-html.pipe';

@Component({
  selector: 'app-list-card',
  standalone: true,
  imports: [CommonModule, TranslateModule, RouterModule, LazyLoadImageDirective, NgOptimizedImage, SkeletonComponent, StripHtmlPipe],
  templateUrl: './list-card.component.html',
  styleUrls: ['./list-card.component.scss']
})
export class ListCardComponent {
  isLogin: boolean;
  private authService = inject(AuthService);
  collapse: any;
  currentLanguage: string;

  @Input() type: ListCardType;
  @Input() item: item;
  @Input() isLoadingFavourite: boolean;
  @Input() status?: 'pending' | 'accepted' | 'rejected';
  @Input() showFavourite: boolean = true;
  @Output() favouriteClicked = new EventEmitter<void>();
  @Output() deleteClicked = new EventEmitter<void>();
  @Output() exploreClicked = new EventEmitter<void>();

  constructor(
    private alertsService: AlertsService,
    public publicService: PublicService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private router: Router,
    private renderer: Renderer2,
    private elementRef: ElementRef
  ) { }
  ngOnInit() {
    this.isLogin = this.authService?.isLoggedIn()
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  onFavouriteClick(item?: any) {
    this.favouriteClicked.emit(item);
  }
  onDeleteClick() {
    this.deleteClicked.emit();
  }
  private documentClickListener!: () => void;
  addClickOutsideListener(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.documentClickListener = this.renderer.listen('document', 'click', (event: MouseEvent) => {
        const clickedInside = this.elementRef.nativeElement.contains(event.target);
        if (!clickedInside && this.collapse) {
          this.collapse = false;
        }
      });
    }
  }
  onImageError(type: ListCardType | undefined, event: Event): void {
    const target = event.target as HTMLImageElement;

    switch (type) {
      case 'trip':
        target.src = 'assets/images-v2/pages/no-result/place-no-result.png';
        break;
      case 'events':
        target.src = 'assets/images-v2/pages/default-cards-imgs/events.png';
        break;
      case 'stories':
        target.src = 'assets/images-v2/pages/no-result/no-result.png';
        break;
      case 'stores':
        target.src = 'assets/images-v2/pages/default-cards-imgs/stores.png';
        break;
      case 'restaurant':
        target.src = 'assets/images-v2/pages/default-cards-imgs/zad.png';
        break;
      case 'places':
        target.src = 'assets/images-v2/pages/default-cards-imgs/places.png';
        break;
      default:
        target.src = 'assets/images-v2/pages/no-result/no-result.png';
    }
  }

  getLinkByType(type: ListCardType): string {
    switch (type) {
      case 'trip':
        return '/trips/' + this.item?.slug;
      case 'events':
        return '/events/event-details/' + this.item?.slug;
      case 'stories':
        return '/stories/' + this.item?.slug;
      case 'stores':
        return '/stores/' + this.item?.slug;
      case 'restaurant':
        return '/restaurants/' + this.item?.slug;
      case 'places':
        return '/places/details/' + this.item?.slug;
      default:
        return '/';
    }
  }
  closeDropdown() {
    this.collapse = false;
  }
  checkEventStatus(dateFrom: string, dateTo: string): string {
    const today = new Date();
    const eventStartDate = new Date(dateFrom);
    const eventEndDate = new Date(dateTo);

    if (today < eventStartDate) {
      return 'soon'; // Coming Soon
    } else if (today >= eventStartDate && today <= eventEndDate) {
      return 'open'; // Open
    } else {
      return 'closed'; // Closed
    }
  }

  exploreTrip(item?: any): void {
    if (item?.token) {
      this.router?.navigate(['/trips/trip-details/' + item?.token]);
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('general.invalidTripData'));
    }
  }

  getStatusInfo(): { text: string; class: string; style: string } | null {
    if (!this.status) {
      return null;
    }

    switch (this.status) {
      case 'pending':
        return {
          text: 'properties.status.pending',
          class: 'status-badge status-badge--pending',
          style: 'background: #FFFAEB; color: #DC6803;'
        };
      case 'rejected':
        return {
          text: 'properties.status.rejected',
          class: 'status-badge status-badge--rejected',
          style: 'background: #FEF3F2;'
        };
      case 'accepted':
        return {
          text: 'properties.status.accepted',
          class: 'status-badge status-badge--accepted',
          style: 'background: #F3E3FF;'
        };
      default:
        return null;
    }
  }
}
