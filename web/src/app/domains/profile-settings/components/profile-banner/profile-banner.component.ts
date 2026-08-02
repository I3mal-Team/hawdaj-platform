/* ---------- Angular Core ---------- */
import {
  ChangeDetectionStrategy,
  Component,
  inject,
  PLATFORM_ID,
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';

/* ---------- Shared Components ---------- */
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';

/* ---------- Shared Directives ---------- */
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';

/* ---------- Third-Party Modules ---------- */
import { ToastModule } from 'primeng/toast';

/* ---------- Environment ---------- */
import { environment } from 'src/environments/environment';

/* ---------- Services ---------- */
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { ProfileBannerData } from '../../interfaces';
import { STATIC_PROFILE } from '../../constants';

@Component({
  selector: 'app-profile-banner',
  standalone: true,
  imports: [
    /* Angular */
    CommonModule
  ],
  templateUrl: './profile-banner.component.html',
  styleUrls: ['./profile-banner.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProfileBannerComponent {
  /* ---------------- Signals ---------------- */

  /* ---------------- Constants ---------------- */
  protected readonly profile: ProfileBannerData = STATIC_PROFILE;

  /* ---------------- Injected ---------------- */
  private readonly publicService = inject(PublicService);
  private readonly platformId = inject(PLATFORM_ID);
  protected readonly isBrowser = isPlatformBrowser(this.platformId);

  constructor() {
  }

  ngOnInit(): void { }

  ngAfterViewInit(): void {
    // Only check content height in browser
    if (isPlatformBrowser(this.platformId)) {
      // You can add any logic here that needs to run after the view has initialized
    }
  }

}
