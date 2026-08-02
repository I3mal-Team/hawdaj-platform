// import { ResponsiveIfStandaloneDirective } from 'src/app/shared/directives/appResponiveIf.direcitve';
// Modules
import { ChangeDetectorRef, Component, ElementRef, inject, Inject, PLATFORM_ID, QueryList, ViewChild, ViewChildren } from '@angular/core';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { CommonModule, isPlatformBrowser, isPlatformServer, Location } from '@angular/common';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { GoogleMapsModule, MapInfoWindow, MapMarker } from '@angular/google-maps';
import { PlacesService } from 'src/app/services/places.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { catchError } from 'rxjs/internal/operators/catchError';
import { finalize } from 'rxjs/internal/operators/finalize';
import { environment } from 'src/environments/environment';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { InputTextModule } from 'primeng/inputtext';
import { CarouselModule } from 'primeng/carousel';
import { tap } from 'rxjs/internal/operators/tap';
import { GalleriaModule } from 'primeng/galleria';
import { SidebarModule } from 'primeng/sidebar';
import { TabViewModule } from 'primeng/tabview';
import { RatingModule } from 'primeng/rating';
import { MessageService } from 'primeng/api';

// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { galleriaOptions, recommendedPlacesOptions } from '../../store/places-configrations';
import { darkModeTheme } from 'src/app/components/home-page/components/map/map-options';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
// Components
import { ComingSoonModalComponent } from 'src/app/modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { ReviewsComponent } from 'src/app/modules/shared/components/reviews/reviews.component';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ToastModule } from 'primeng/toast';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { AuthService } from 'src/app/services/auth.service';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ResponsiveIfStandaloneDirective } from 'src/app/shared/directives/appResponiveIf.direcitve';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { RateSiteComponent } from 'src/app/components/home-page/components/rate-site/rate-site.component';
import { Place } from './interfaces/place-details';
import { DestinationSliderComponent } from 'src/app/components/home-page/components/destination-slider/destination-slider.component';
import { AnimatedImageSliderV3Component } from 'src/app/modules/shared/components/animated-image-slider-v3/animated-image-slider-v3.component';
import { Tabs2Component } from "../../../../Common/layout/tabs2/tabs2.component";
import { AllTabsTypes } from 'src/app/Common/enums/details-tabs.enum';
import { BreadCrumbComponent } from 'src/app/Common/component/bread-crumb/bread-crumb.component';
import { RecommendedPlacesSliderComponent } from "../../../../Common/component/recommended-places-slider/recommended-places-slider.component";
import { ShareSocialComponent } from "../../../../Common/component/share-social/share-social.component";
import { moduleTypeRating } from 'src/app/Common/enums/module-type-rating.enum';
import { RateItemComponent } from 'src/app/Common/component/rate-place/rate-item.component';
import { MediaViewerComponent } from "../../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-place-details-v2',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    GoogleMapsModule,
    InputTextModule,
    TranslateModule,
    GalleriaModule,
    CarouselModule,
    TabViewModule,
    RouterModule,
    SidebarModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    ListCardComponent,
    RateItemComponent,
    RateSiteComponent,
    Tabs2Component,
    BreadCrumbComponent,
    RecommendedPlacesSliderComponent,
    ShareSocialComponent,
    MediaViewerComponent
  ],
  templateUrl: './place-details-v2.component.html',
  styleUrls: ['./place-details-v2.component.scss']
})
export class PlaceDetailsV2Component {
  private subscriptions: Subscription[] = [];
  @ViewChildren(Tabs2Component) tabsComponents!: QueryList<Tabs2Component>;

  private localizationLanguageService = inject(LocalizationLanguageService);
  private metadataService = inject(MetadataService);
  private activatedRoute = inject(ActivatedRoute);
  private messageService = inject(MessageService);
  private dialogService = inject(DialogService);
  private placesService = inject(PlacesService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private cdr = inject(ChangeDetectorRef);
  private authService = inject(AuthService);
  private location = inject(Location);
  private fb = inject(FormBuilder);
  private router = inject(Router);

  isUserLoggedin: boolean = false;
  currentLanguage: any;
  currentLoginInformation: any;
  moduleTypeRating: string;

  isLoadingSavePlace: boolean = false;
  isLoadingfavouritePlace: boolean = false;
  isLoadingFavourite: boolean = false;

  @ViewChild('locationTab') locationTab!: ElementRef;
  activeIndex: any = 0;
  // Start Map Configs
  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;

  center: google.maps.LatLngLiteral = { lat: 24.774265, lng: 46.738586 }; // Coordinates of Riyadh, Saudi Arabia
  zoom: any = 5;
  darkMode: any = darkModeTheme;
  markerPositions: any[] = [];
  responsiveOptions: any;

  locations: any;
  tabs: any;
  currentIndex: number;

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    this.center = {
      lat: this.placeDetails?.lat,
      lng: this.placeDetails?.long,
    };
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    if (isPlatformBrowser(this.platformId)) {
      window.open(this.placeDetails.address, '_blank');
    }
  }
  closeAllInfoWindows(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.infoWindow?.close();
      this.center = {
        lat: this.placeDetails?.lat,
        lng: this.placeDetails?.long,
      };
    }
  }
  // End Map Configs

  placeDetails: any;
  placeLocations: any = [];
  isLoadingPlaceDetails: boolean = false;
  placeId: any;
  id: any;

  showGalleria: boolean = false;
  galleriaOptions: any[] = galleriaOptions;

  recommendedPlacesOptions = recommendedPlacesOptions;
  relatedPlaces: any;
  isLoadingRelatedPlaces: boolean = false;
  seeAllRelatedPlaces: boolean = false;
  paginatedRelatedPlaces: any[] = [];
  currentRelatedPage: number = 1;
  pageRelatedSize: number = 12;

  isLoadingTestimonialsData: boolean = false;
  homeShowFooter = false;
  placeDetailelocationTab = false;
  form = this.fb.group({
    status: [null, [Validators.required]],
    name: ['', {
      validators: [],
      updateOn: 'blur',
    },],
    email: ['', {
      validators: [Validators.pattern(patterns.email)],
      updateOn: 'blur',
    },
    ],
    massage: ['', {
      validators: [
        Validators.required,
        Validators.minLength(10),
        Validators.pattern('[a-zA-Z\u0600-\u06FF\u0400-\u04FF\u4E00-\u9FFF ]+')
      ],
      updateOn: 'blur',
    },
    ],
  });
  get formControls(): any {
    return this.form?.controls;
  }
  isLoadingBtn: boolean = false;

  chat: any = [];
  isLoadingChat: boolean = false;
  selectedFile: any = '';
  chatForm = this.fb.group({
    message: ['', { validators: [Validators.required], updateOn: 'change' }],
  });
  fullUrl: any = null;
  displayChat: boolean = false;

  //rating
  ratingsPerPage = 3;
  currentRatingPage = 0;
  isLoadingReviews: boolean = false;


  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
  ) {
    this.localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.isUserLoggedin = this.authService.isLoggedIn();
    this.moduleTypeRating = moduleTypeRating?.PLACE;

    this.tabs = [
      {
        type: AllTabsTypes.Description,
        title: 'places.description',
        icon: 'assets/images-v2/pages/place-details/tabs/description.svg'
      },
      {
        type: AllTabsTypes.Features,
        title: 'places.features',
        icon: 'assets/images-v2/pages/place-details/tabs/feature.svg'
      },
      {
        type: AllTabsTypes.Location,
        title: 'places.location',
        icon: `assets/images-v2/pages/place-details/tabs/location.svg`
      },
      {
        type: AllTabsTypes.AI,
        title: 'places.ai',
        icon: `assets/images-v2/pages/place-details/tabs/ai.svg`
      }
    ];

    if (isPlatformBrowser(this.platformId)) {
      if (
        JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')
          ?.user
      ) {
        this.currentLoginInformation = JSON.parse(
          window?.localStorage?.getItem(keys?.userLoginData) || '{}'
        )?.user;
      }
    }
    !this.currentLoginInformation ? this.publicService?.addValidators(this.form, ['email', 'name']) : '';

    if (isPlatformBrowser(this.platformId)) {
      this.responsiveOptions = [
        {
          breakpoint: '767px',
          numVisible: 1,
          numScroll: 1
        }
      ];
    }

    this.initPageData();
  }
  private initPageData(): void {
    this.setupBrowserSpecificTasks();
    this.activatedRoute.params.subscribe((params) => {
      this.placeId = params['id'];
      if (this.placeId) {
        this.getPLaceDataById(this.placeId);
        this.getRelatedPlaces(this.placeId);
        // this.fullUrl = environment.publicUrl + '/places/details/' + this.placeId;
        this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
      }
    });
  }
  private setupBrowserSpecificTasks(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }
  getPLaceDataById(id: number, preventLoading?: boolean, reviewLoading?: boolean): void {
    preventLoading ? '' : (this.isLoadingPlaceDetails = true);
    reviewLoading ? this.isLoadingReviews = true : '';
    this.placesService?.getPLaceById(id)?.subscribe(
      (res: Place) => {
        if (res?.code == 200) {
          this.placeDetails = res?.data;
          this.id = res?.data?.id;
          this.cdr.detectChanges();
          if (isPlatformBrowser(this.platformId)) {
            this.updateMetaTags();
            this.cdr.detectChanges();
          }
          if (isPlatformServer(this.platformId)) {
            this.updateMetaTags();
            this.cdr.detectChanges();
          }
          this.placeDetails['rate'] = this.placeDetails?.rate ? Math.ceil(this.placeDetails?.rate) : 0;
          if (
            this.placeDetails?.lat !== null &&
            this.placeDetails?.long !== null
          ) {
            if (this.placeDetails?.address_type == 'map') {
              this.placeDetails['address'] =
                this.publicService.createGoogleMapsLink(
                  this.placeDetails?.lat,
                  this.placeDetails?.long
                );
            }
            this.center = {
              lat: this.placeDetails?.lat,
              lng: this.placeDetails?.long,
            };
          }
          if (
            this.placeDetails?.region?.name &&
            this.placeDetails?.city?.name
          ) {
            this.placeDetails['address_name'] =
              this.placeDetails?.region?.name +
              ', ' +
              this.placeDetails?.city?.name;
          } else if (this.placeDetails?.region?.name) {
            this.placeDetails['address_name'] = this.placeDetails?.region?.name;
          } else if (this.placeDetails?.city?.name) {
            this.placeDetails['address_name'] = this.placeDetails?.city?.name;
          }
          this.markerPositions = [
            {
              lat: this.placeDetails?.lat,
              lng: this.placeDetails?.long,
              place_icon: 'assets/images/icons/location2.svg',
              icon: {
                url: this.placeDetails?.place_icon
                  ? this.placeDetails?.place_icon
                  : 'assets/images/icons/location2.svg',
                size: this.placeDetails?.place_icon
                  ? new google.maps.Size(30, 30)
                  : new google.maps.Size(50, 50),
              },
              content: {
                id: this.placeDetails?.id,
                title: this.placeDetails.title,
                location_name: this.placeDetails.address_name,
                address: this.placeDetails.address,
                rate: this.placeDetails.rate ? this.placeDetails.rate : 0,
                reviews: this.placeDetails?.review,
                icon: this.placeDetails.place_icon,
                thumbil_image: this.placeDetails.image
                  ? this.placeDetails?.image
                  : 'assets/images/icons/location2.svg',
              },
            },
          ];
          this.locations = [this.placeDetails?.region?.name, this.placeDetails?.city?.name]
          this.cdr.detectChanges();
          this.isLoadingPlaceDetails = false;
        } else {
          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
        }
        this.isLoadingPlaceDetails = false;
        this.isLoadingReviews = false;
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingPlaceDetails = false;
      }
    );
  }
  private updateMetaTags(): void {
    const lang = this.localizationLanguageService.getCurrentLanguage();
    const pageUrl = `${environment.publicUrl}/${lang}/places/details/${this.placeDetails.slug}`;
    this.metadataService.applyDetailPageShareMeta({
      title: this.placeDetails?.title,
      description: this.placeDetails?.description,
      pageUrl,
      imageUrl: this.placeDetails?.image,
    });
  }
  savePlace(): void {
    this.isLoadingSavePlace = true;
    this.publicService?.isSaved(this.placeDetails.type, this.placeDetails?.id)?.subscribe(
      (res: any) => {
        if (res.code == 200) {
          this.isLoadingSavePlace = false;
          const messageKey = this.placeDetails.is_saved ? 'general.removeSaved' : 'general.addSaved';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
          this.placeDetails.is_saved = true;
          this.getPLaceDataById(this.placeId, true);
        } else {
          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
          this.isLoadingSavePlace = false;
        }
      },
      (err: any) => {
        this.isLoadingSavePlace = false;
        err ? this.alertsService?.openToast('error', err) : '';
      }
    );
  }
  recallNewPlaceDetails(slug: any): void {
    this.router.navigate(['/places/details/', slug]);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(0, 0);
    }
    this.getPLaceDataById(slug);
    this.getRelatedPlaces(slug);
  }
  getRelatedPlaces(id?: any, preventLoading?: boolean, isLoadingFavourite?: boolean): void {
    preventLoading ? '' : (this.isLoadingRelatedPlaces = true);
    isLoadingFavourite ? '' : (this.isLoadingFavourite = true)
    this.placesService?.getRelatedPlaces(id)?.subscribe(
      (res: Place) => {
        if (res.code == 200) {
          this.relatedPlaces = res?.data?.items;

          if (this.relatedPlaces?.length > 0) {
            this.relatedPlaces?.forEach((element: any) => {
              if (
                element?.lat &&
                element?.long &&
                element?.address_type == 'map'
              ) {
                element['address'] = this.publicService.createGoogleMapsLink(
                  element?.lat,
                  element?.long
                );
              }
              if (element?.region?.name && element?.city?.name) {
                element['address_name'] =
                  element?.region?.name + ', ' + element?.city?.name;
              } else if (element?.region?.name) {
                element['address_name'] = element?.region?.name;
              } else if (element?.city?.name) {
                element['address_name'] = element?.city?.name;
              }
            });
          }
          this.isLoadingRelatedPlaces = false;
          this.isLoadingFavourite = false;
        } else {
          this.isLoadingRelatedPlaces = false;
          this.isLoadingFavourite = false;

          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err) : '';
        this.isLoadingRelatedPlaces = false;
        this.isLoadingFavourite = false;
      }
    );
  }

  allRelatedplaces() {
    this.router.navigate(['/places']);
  }

  sendFeedbackFromPlaces(feedbackData): void {
    this.messageService.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (feedbackData) {
        this.isLoadingBtn = true;
        this.placesService.sendFeedbackFromPlaces(feedbackData).subscribe(
          (res: any) => {
            if (res?.code == 200) {
              this.isLoadingBtn = false;
              this.getPLaceDataById(this.placeId, true, true);
              this.alertsService?.openToast(
                'success',
                this.publicService?.translateTextFromJson('general.successRate')
              );
              if (isPlatformBrowser(this.platformId)) {
                window.scrollTo({ top: 200, behavior: 'smooth' });
              }
            } else {
              this.isLoadingBtn = false;
              res?.message
                ? this.alertsService?.openToast('error', res?.message)
                : '';
            }
          },
          (err: any) => {
            err ? this.alertsService?.openToast('error', err) : '';
            this.isLoadingBtn = false;
          }
        );
      } else {
        this.publicService.validateAllFormFields(this.form);
      }
    }
  }

  getChatGpt(): void {
    this.isLoadingChat = true;
    this.placesService?.getChatGpt()?.subscribe(
      (res: any) => {
        if (res) {
          this.chat = res?.data;
          this.isLoadingChat = false;
        } else {
          this.isLoadingChat = false;
          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
        }
      },
      (err: any) => {
        err ? this.alertsService?.openToast('error', err?.message) : '';
        this.isLoadingChat = false;
      }
    );
    // this.chat = [
    //   { text: 'How likely are you to recommend our company to your friends and family ?', type: 'one' },
    //   { text: 'Hey there, we’re just writing to let you know that you’ve been subscribed to a repository on GitHub.', type: 'two' },
    //   { text: 'Ok, Understood!', type: 'one' },
    //   { text: 'You’ll receive notifications for all issues, pull requests!', type: 'two' },
    //   { text: 'You can unwatch this repository immediately by clicking here: Keenthemes.com', type: 'one' },
    //   { text: 'Most purchased Business courses during this sale!', type: 'two' },
    //   { text: 'Company BBQ to celebrate the last quater achievements and goals. Food and drinks provided', type: 'one' },
    // ]
  }

  showPlaceMap(): void {
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: this.placeLocations,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
  }

  onLocationTabClick(): void {
    setTimeout(() => {
      this.tabsComponents.forEach((tabs) => {
        tabs.locationTabClick(2);
        this.smoothScroll();
      });
    }, 200);
  }
  private smoothScroll() {
    if (isPlatformBrowser(this.platformId)) {
      const topValue = window.innerWidth < 990 ? 720 : 135;
      window.scrollTo({ top: topValue, behavior: 'smooth' });
    }
  }
  back(): void {
    this.location?.back();
  }
  showMap(): void {
    let data: any = [];
    this.markerPositions?.forEach((el: any) => {
      data?.push({
        lat: el?.lat,
        lng: el?.lng,
        place_icon: el?.place_icon,
        name: el?.content?.title,
        image: el?.content?.thumbil_image,
        address_name: el?.content?.location_name,
        address: el?.content?.address,
        id: el?.id,
      });
    });
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: data,
      dismissableMask: true,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  openComingSoon(): void {
    const ref = this?.dialogService?.open(ComingSoonModalComponent, {
      width: '35%',
      styleClass: '',
      header: '',
      dismissableMask: true,
    });
  }
  tabIndex: number = 0;

  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.isLoadingfavouritePlace = true;
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getPLaceDataById(this.placeId, true);
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => {
        this.isLoadingfavouritePlace = false;
      })
    ).subscribe();
    this.subscriptions.push(addToFavoriteSubscription);
  }
  addToFavoriteRelated(item: any): void {
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.isLoadingFavourite = true;
          this.getRelatedPlaces(this.placeId, true, false);
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => {
        this.isLoadingFavourite = false;
      })
    ).subscribe();
    this.subscriptions.push(addToFavoriteSubscription);
  }
  /* --- End Add To Favorite Functions --- */

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug])
    }
  }

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
  }
  private setErrorMessage(message: string): void {
    // Implementation for displaying the error message, e.g., using a sweetalert
    this.alertsService?.openToast('error', message);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
