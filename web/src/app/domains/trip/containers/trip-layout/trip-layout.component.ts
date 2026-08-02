/* ---------- Angular Core ---------- */
import {
  AfterViewInit,
  ChangeDetectionStrategy,
  ChangeDetectorRef,
  Component,
  ElementRef,
  Inject,
  OnDestroy,
  OnInit,
  PLATFORM_ID,
  Signal,
  ViewChild,
  signal,
  inject,
} from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';

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

@Component({
  selector: 'app-trip-layout',
  standalone: true,
  imports: [
    /* Angular */
    CommonModule,
    RouterModule,

    /* Shared Components */
    HeaderComponent,
    NewFooterComponent,
    ScrollTopComponent,
    OverlayLoadingComponent,

    /* Shared Directives */
    LazyLoadDirective,

    /* Third-party */
    ToastModule,
  ],
  templateUrl: './trip-layout.component.html',
  styleUrls: ['./trip-layout.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class TripLayoutComponent implements OnInit, AfterViewInit, OnDestroy {
  /* ---------------- Signals ---------------- */
  readonly homeShowFooter = signal(false);
  readonly currentLanguage: Signal<string>;
  readonly hideHeaderFooter = signal(false);

  /* ---------------- Injected ---------------- */
  private readonly router = inject(Router);
  private readonly publicService = inject(PublicService);
  private readonly platformId = inject(PLATFORM_ID);
  private readonly cdr = inject(ChangeDetectorRef);

  /* ---------------- Subscriptions ---------------- */
  private hideHeaderFooterSubscription?: any;

  /* ---------------- View References ---------------- */
  @ViewChild('outletContainer', { static: true }) outletContainerRef?: ElementRef<HTMLDivElement>;

  constructor() {
    this.currentLanguage = signal(this.publicService?.getCurrentLanguage?.() ?? 'ar');
  }

  ngOnInit(): void {
    // Subscribe to hideHeaderFooter changes
    if (isPlatformBrowser(this.platformId)) {
      this.hideHeaderFooterSubscription = this.publicService.hideHeaderFooter.subscribe((hide: boolean) => {
        this.hideHeaderFooter.set(hide);
        this.cdr.markForCheck();
      });
    }
  }

  ngAfterViewInit(): void {
    // Only check content height in browser
    if (isPlatformBrowser(this.platformId)) {
      this.checkContentHeight();
    }
  }

  ngOnDestroy(): void {
    // Unsubscribe from hideHeaderFooter
    if (this.hideHeaderFooterSubscription) {
      this.hideHeaderFooterSubscription.unsubscribe();
    }
  }

  /* ---------------- Template Actions ---------------- */
  protected onSectionInView(): void {
    this.homeShowFooter.set(true);
  }

  /* ---------------- Helpers ---------------- */
  private checkContentHeight(): void {
    const container = this.outletContainerRef?.nativeElement;
    if (!container || !isPlatformBrowser(this.platformId)) return;

    const contentHeight = container.offsetHeight;
    const viewportHeight = window.innerHeight;

    // Automatically show footer if content height < viewport
    if (contentHeight < viewportHeight) {
      this.onSectionInView();
    }
  }
}
