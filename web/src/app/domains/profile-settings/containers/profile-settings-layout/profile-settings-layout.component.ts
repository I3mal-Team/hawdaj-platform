import {
  ChangeDetectionStrategy,
  Component,
  ElementRef,
  Signal,
  ViewChild,
  signal,
  inject,
  PLATFORM_ID,
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';

/* ---------- Shared Components ---------- */
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ProfileBannerComponent, ProfileFeaturedTabsComponent } from '../../components';

/* ---------- Shared Directives ---------- */
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';

/* ---------- Third-Party Modules ---------- */
import { ToastModule } from 'primeng/toast';

/* ---------- Environment ---------- */
import { environment } from 'src/environments/environment';

/* ---------- Services & Interfaces ---------- */
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { ITab } from '../../interfaces';
import { ProfileSettingsRoutesEnum, profileTabsItems } from '../../constants';

@Component({
  selector: 'app-profile-settings-layout',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    HeaderComponent,
    NewFooterComponent,
    ScrollTopComponent,
    OverlayLoadingComponent,
    ProfileBannerComponent,
    ProfileFeaturedTabsComponent,
    LazyLoadDirective,
    ToastModule,
  ],
  templateUrl: './profile-settings-layout.component.html',
  styleUrls: ['./profile-settings-layout.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProfileSettingsLayoutComponent {
  /* ---------------- Signals ---------------- */
  readonly homeShowFooter = signal(false);
  readonly currentLanguage: Signal<string>;

  /* ---------------- Tabs ---------------- */
  readonly profileTabs: ITab[] = profileTabsItems;
  readonly currentTabIdSignal = signal<string | number | null>(null);
  readonly isLoadingSignal = signal(false);

  /* ---------------- Injected ---------------- */
  private readonly publicService = inject(PublicService);
  private readonly platformId = inject(PLATFORM_ID);
  private readonly router = inject(Router);

  /* ---------------- View References ---------------- */
  @ViewChild('outletContainer', { static: true }) outletContainerRef?: ElementRef<HTMLDivElement>;

  constructor() {
    this.currentLanguage = signal(this.publicService.getCurrentLanguage?.() ?? 'ar');
  }

  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.checkContentHeight();
      this.syncTabWithRoute();
    }
  }

  /* ---------------- Template Actions ---------------- */
  protected onSectionInView(): void {
    this.homeShowFooter.set(true);
  }

  protected onTabChange(tab: ITab): void {
    if (!tab) return;

    this.currentTabIdSignal.set(tab.id);

    if (tab.route) {
      this.router.navigate([`${ProfileSettingsRoutesEnum.ROOT}/${tab.route}`]);
    }
  }

  /* ---------------- Helpers ---------------- */
  private checkContentHeight(): void {
    const container = this.outletContainerRef?.nativeElement;
    if (!container || !isPlatformBrowser(this.platformId)) return;

    if (container.offsetHeight < window.innerHeight) {
      this.onSectionInView();
    }
  }

  /** Sync the selected tab with the current route on load */
  private syncTabWithRoute(): void {
    if (!isPlatformBrowser(this.platformId)) return;

    const currentUrl = this.router.url;
    const matchingTab = this.profileTabs.find(tab => currentUrl.includes(tab.route));
    if (matchingTab) {
      this.currentTabIdSignal.set(matchingTab.id);
    } else {
      this.currentTabIdSignal.set(this.profileTabs[0].id);
    }
  }
}
