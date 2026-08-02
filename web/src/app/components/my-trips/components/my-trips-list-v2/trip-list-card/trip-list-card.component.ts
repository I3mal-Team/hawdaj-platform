import { Component, ElementRef, EventEmitter, inject, Inject, Input, Output, PLATFORM_ID, Renderer2 } from '@angular/core';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { item } from 'src/app/Common/component/list-card/interface/list-card';
import { AuthService } from 'src/app/services/auth.service';
import { environment } from 'src/environments/environment';
import { Router } from '@angular/router';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  selector: 'app-trip-list-card',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, TranslateModule, NgOptimizedImage],
  templateUrl: './trip-list-card.component.html',
  styleUrls: ['./trip-list-card.component.scss']
})
export class TripListCardComponent {
  isLogin: boolean;
  collapse: any;
  currentLanguage: string;

  private authService = inject(AuthService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private platformId = inject(PLATFORM_ID);
  private router = inject(Router);
  private renderer = inject(Renderer2);
  private elementRef = inject(ElementRef);

  @Input() item: any;
  @Output() deleteClicked = new EventEmitter<void>();
  @Output() exploreClicked = new EventEmitter<void>();
  @Output('onExplore') exploreClickedAlias = this.exploreClicked;

  ngOnInit() {
    this.isLogin = this.authService?.isLoggedIn()
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }

  onDeleteClick() {
    this.deleteClicked.emit();
  }

  onExploreClick() {
    this.exploreClicked.emit();
    this.exploreClickedAlias.emit();
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
  closeDropdown() {
    this.collapse = false;
  }

  onImageError(event: Event) {
    (event.target as HTMLImageElement).src = 'assets/images-v2/pages/no-result/place-no-result.png';
  }
}
