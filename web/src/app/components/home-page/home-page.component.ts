import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';


import { AnimatedImageSliderV4Component } from './../../modules/shared/components/animated-image-slider-v4/animated-image-slider-v4component';
import { FooterComponent } from './../../modules/shared/components/footer/footer.component';
// Modules
import { CommonModule, NgOptimizedImage, isPlatformBrowser, isPlatformServer } from '@angular/common';

// Components
import { AnimatedImageSliderV3Component } from './../../modules/shared/components/animated-image-slider-v3/animated-image-slider-v3.component';
import { QuickGlobalSearchComponent } from './components/quick-global-search/quick-global-search.component';
import { AnimatedImageSliderV2Component } from 'src/app/modules/shared/components/animated-image-slider-v2/animated-image-slider-v2.component';
import { AnimatedImageSliderComponent } from './../../modules/shared/components/animated-image-slider/animated-image-slider.component';
import { TestimonialComponent } from './components/testimonial/testimonial.component';
import { HomeEventsComponent } from './components/home-events/home-events.component';
import { SubscribeComponent } from './components/subscribe/subscribe.component';
import { SkeletonComponent } from './../../modules/shared/components/skeleton/skeleton.component';
import { EarthComponent } from './../../modules/shared/components/earth/earth.component';

// Services
import { LocalizationLanguageService } from './../../modules/shared/services/localization-language.service';
import { MetadataService } from './../../modules/shared/services/metadata.service';
import { ChangeDetectorRef, Component, ElementRef, Inject, PLATFORM_ID, Renderer2, ViewChild } from '@angular/core';
import { PublicService } from './../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { HomeService } from '../../services/home.service';
import { AuthService } from '../../services/auth.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { IHome, IHomeApiResponse } from './../../interfaces/home';
import { environment } from './../../../environments/environment';
import { Subscription, catchError, finalize, tap } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';
import { Router } from '@angular/router';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { DestinationSliderComponent } from './components/destination-slider/destination-slider.component';
import { HighlightsStatisticsComponent } from './components/highlights-statistics/highlights-statistics.component';
import { HomeRestaurantsComponent } from './components/home-restaurants/home-restaurants.component';
import { HomeTripComponent } from './components/home-trip/home-trip.component';
import { PopularAppsComponent } from './components/popular-apps/popular-apps.component';
import { MapComponent } from './components/map/map.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ToastModule } from 'primeng/toast';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { ResponsiveIfDirectiveNotStandalone } from 'src/app/shared/directives/appResponiveIf.direcitve';
import { DialogService } from 'primeng/dynamicdialog';
import { defaultTags } from 'src/app/services/meta-tags-pages';
import { CreateTripComponent } from '../create-trip/create-trip.component';
import { AppsHomeSectionComponent } from './components/apps-home-section/apps-home-section.component';
import { EventsHomeSectionComponent } from './components/events-home-section/events-home-section.component';
import { JoinUsHomeSectionComponent } from './components/join-us-home-section/join-us-home-section.component';
import { PrepareTripStepperWizardComponent } from '../trips/prepare-trip-stepper-wizard/prepare-trip-stepper-wizard.component';
import { TourGuideHomeComponent } from './components/tour-guide-home/tour-guide-home.component';
import { HomePageHeroSectionComponent } from "./components/home-page-hero-section/home-page-hero-section.component";
import { AppAnimatedImageSliderV5Component } from "./components/app-animated-image-slider-v5/app-animated-image-slider-v5.component";
import { HomePageApplicationsV2Component } from "./components/home-page-applications-v2/home-page-applications-v2.component";
import { EventsHomeSectionV2Component } from "./components/events-home-section-v2/events-home-section-v2.component";
import { HomeTripV2Component } from "./components/home-trip-v2/home-trip-v2.component";
import { HomeRestaurantsV2Component } from "./components/home-restaurants-v2/home-restaurants-v2.component";
import { TestimonialV2Component } from './components/testimonial-v2/testimonial-v2.component';
import { HomePageApplicationsMobileV2Component } from './components/home-page-applications-mobile-v2/home-page-applications-mobile-v2.component';
import { StoresV2Component } from './components/stores-v2/stores-v2.component';
import { NewFooterComponent } from "../../modules/shared/components/new-footer/new-footer.component";
import { SubscribeV2Component } from './components/subscribe-v2/subscribe-v2.component';
import { HighlightsStatisticsV2Component } from "./components/highlights-statistics-v2/highlights-statistics-v2.component";
import { EarthV2Component } from 'src/app/modules/shared/components/earct-v2/earth-v2.component';
import { QuickGlobalSearchV2Component } from "./components/quick-global-search-v2/quick-global-search-v2.component";
import { SwalefSectionComponent } from "./components/swalef-section/swalef-section.component";
import { PrepearTripStepperComponent } from 'src/app/domains';
import { HomeDownloadAppsSectionComponent } from './components/home-download-apps-section/home-download-apps-section.component';
@Component({
  selector: 'app-home-page',
  standalone: true,
  imports: [
    TranslateModule,
    CommonModule,
    NgOptimizedImage,
    ToastModule,
    // Components
    EventsHomeSectionComponent,
    JoinUsHomeSectionComponent,
    AppsHomeSectionComponent,
    TourGuideHomeComponent,
    AnimatedImageSliderV2Component,
    AnimatedImageSliderV4Component,
    AnimatedImageSliderV3Component,
    HighlightsStatisticsComponent,
    AnimatedImageSliderComponent,
    QuickGlobalSearchComponent,
    DestinationSliderComponent,
    HomeRestaurantsComponent,
    OverlayLoadingComponent,
    PopularAppsComponent,
    TestimonialComponent,
    HomeEventsComponent,
    SubscribeComponent,
    ScrollTopComponent,
    HomeTripComponent,
    SkeletonComponent,
    FooterComponent,
    HeaderComponent,
    EarthComponent,
    MapComponent,
    AppAnimatedImageSliderV5Component,
    HomeDownloadAppsSectionComponent,
    HomeTripV2Component,
    TestimonialV2Component,
    HomePageHeroSectionComponent,
    AppAnimatedImageSliderV5Component,
    HomePageApplicationsV2Component,
    HomePageApplicationsMobileV2Component,
    EventsHomeSectionV2Component,
    HomeTripV2Component,
    HomeRestaurantsV2Component,
    EarthV2Component,
    StoresV2Component,
    NewFooterComponent,
    SubscribeV2Component,
    HighlightsStatisticsV2Component,
    // Directives
    LazyLoadSectionDirective,
    LazyLoadDirective,
    ResponsiveIfDirectiveNotStandalone,
    NewFooterComponent,
    HighlightsStatisticsV2Component,
    QuickGlobalSearchV2Component,
    SwalefSectionComponent
  ],
  templateUrl: './home-page.component.html',
  styleUrls: ['./home-page.component.scss']
})
export class HomePageComponent {
  private subscriptions: Subscription[] = [];
  currentLanguage: string = '';

  @ViewChild('joinSection') joinSection!: ElementRef<HTMLElement>;

  homeData: IHome;
  isLoadingHomeData: boolean = false;
  showMap: boolean = false;

  isLoggedIn: boolean = false;
  isDownlaodAppsInView = false;
  statisticsSectionInView = false;
  globalSearchSectionInView = false;
  destinationsSliderSectionInView = false;
  joinUsSectionInView = false;
  popularSectionInView = false;
  tourGuideSectionInView = false;
  eventSectionInView = false;
  homeTripSectionInView = false;
  homeStoresSectionInView = false;
  homeZadSectionInView = false;
  homeServicesSectionInView = false;
  homeVisitorsSectionInView = false;
  homeSubscribeSectionInView = false;
  homeShowFooter = false;
  homeElezbaSectionInView = false;
  homeZadStoreParenSectionInView = false;
  destinationsParent = false;

  defaultTags = defaultTags;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private homeService: HomeService,
    private cdr: ChangeDetectorRef,
    private dialogService: DialogService,
    private renderer: Renderer2,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    // this.prepareNewTrip();
    // this.startTrip();
    this.publicService?.showMap?.next(false);
    this.currentLanguage = this.publicService.getCurrentLanguage();
    this.isLoggedIn = this.authService.isLoggedIn();
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('homePage');

    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('homePage');

    }
    this.getHomeData();
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | الرئيسية`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | الرئيسية` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/home` },
      { property: 'og:title', content: `هودج | الرئيسية` },
    ]);
  }

  prepareNewTrip(): void {
    const ref: any = this.dialogService.open(PrepareTripStepperWizardComponent, {
      dismissableMask: false,
      resizable: true,
      maximizable: true,
      header: "Trip Program Wizard",
      baseZIndex: 10001,
      style: {
        width: '95vw', // 95% of the viewport width
        height: '95vh', // 95% of the viewport height
        maxWidth: '1600px', // Max width set to 1600px
        maxHeight: '800px', // Max height set to 800px
        minWidth: '300px', // Optional: minimum width to maintain usability on very small screens
        minHeight: '300px', // Optional: minimum height to maintain usability on very small screens
        overflow: 'hidden' // Optional: to prevent overflow of the main dialog area
      },
      contentStyle: {
        overflow: 'auto' // Ensure content can scroll if it overflows
      }
    });
  }

  onScroll(): void {
    if (isPlatformBrowser(this.platformId)) {
      const sections = document.querySelectorAll('.home-section');
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting && !entry.target.hasAttribute('data-intersected')) {
            entry.target.classList.remove('d-none');
            entry.target.classList.add('d-block');
            this.handleIntersection(entry.target.id);
            entry.target.setAttribute('data-intersected', 'true');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });

      // sections.forEach(section => {
      //   section.classList.add('d-none'); // Initially hide all sections
      //   observer.observe(section);
      // });
    }
  }
  private handleIntersection(targetId: string): void {
    switch (targetId) {
      case 'zad':

        break;
      case 'elezba':

        break;
      case 'services':

        break;
    }
  }

  scrollToJoinSection(): void {
    this.destinationsParent = true;
    setTimeout(() => {
      if (isPlatformBrowser(this.platformId) && this.joinSection) {
        this.joinSection.nativeElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }, 10);
  }

  // Start Home Data Functions
  getHomeData(): void {
    this.isLoadingHomeData = true;
    let homeDataSubscription: Subscription = this.homeService?.getHomeData()
      .pipe(
        tap((res: IHomeApiResponse) => this.processHomeDataResponse(res)),
        catchError(err => this.handleError(err)),
        finalize(() => this.finalizeHomeDataLoading())
      ).subscribe();
    this.subscriptions.push(homeDataSubscription);
  }
  private processHomeDataResponse(response: any): void {
    if (response) {
      response.data?.places?.items?.map((element: any) => {
        this.setAddressDetails(element);
      });
      response.data?.topVisitedStores?.items?.map((element: any) => {
        this.setAddressDetails(element);
      });
      response.data?.topEvents?.items?.map((element: any) => {
        this.setAddressDetails(element);
      });
      response.data?.zads?.items?.map((element: any) => {
        this.setAddressDetails(element);
      });
      this.homeData = response?.data;
      this.cdr.detectChanges();
    } else {
      this.handleError(response.error);
      return;
    }
  }
  private finalizeHomeDataLoading(): void {
    this.isLoadingHomeData = false;
  }

  private getAddressName(regionName: string, cityName: string): string {
    if (regionName && cityName) {
      return `${regionName}, ${cityName}`;
    } else if (regionName) {
      return regionName;
    } else if (cityName) {
      return cityName;
    }
    return '';
  }
  private setAddressDetails(element: any): void {
    if (element?.lat && element?.long && element?.address_type === 'map') {
      element['address'] = this.publicService.createGoogleMapsLink(element?.lat, element?.long);
    }
    element['address_name'] = this.getAddressName(element?.region?.name, element?.city?.name);
  }
  // End Home Data Functions

  explore(): void {
    this.publicService?.showMap?.next(true);
    let showMapSubscription: Subscription = this.publicService?.showMap?.subscribe(res => {
      this.showMap = res;
    });
    this.subscriptions.push(showMapSubscription);
  }

  joinUs(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (localStorage.getItem(keys?.userLoginData)) {
        this.router.navigate(['/Profile']);
      } else {
        this.authService.promptJoinUsDialog();
      }
    }
  }

  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || this.publicService.translateTextFromJson('general.errorOccur'));
  }
  private setErrorMessage(message: string): void {
    this.alertsService.openToast('error', 'error', message);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
