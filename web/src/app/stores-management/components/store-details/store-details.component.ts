// Modules
import { Component, ElementRef, Inject, QueryList, ViewChild, ViewChildren, PLATFORM_ID, inject } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer, Location } from '@angular/common';
import { Subscription } from 'rxjs/internal/Subscription';
import { environment } from 'src/environments/environment';
import { GoogleMapsModule, MapInfoWindow, MapMarker } from '@angular/google-maps';
import { darkModeTheme } from 'src/app/components/home-page/components/map/map-options';
// import { storeGalleriaOptions, storeRecommendedPlacesOptions } from '../../store/storesStaticDataConfigs';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { MessageService } from 'primeng/api';
import { DialogService } from 'primeng/dynamicdialog';
// Services
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { finalize } from 'rxjs/internal/operators/finalize';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { ReviewsComponent } from 'src/app/modules/shared/components/reviews/reviews.component';
import { ComingSoonModalComponent } from 'src/app/modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { tap } from 'rxjs/internal/operators/tap';
import { catchError } from 'rxjs/internal/operators/catchError';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { TranslateModule } from '@ngx-translate/core';
import { CarouselModule } from 'primeng/carousel';
import { GalleriaModule } from 'primeng/galleria';
import { RatingModule } from 'primeng/rating';
import { SidebarModule } from 'primeng/sidebar';
import { TabViewModule } from 'primeng/tabview';
import { ToastModule } from 'primeng/toast';
// Components
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { AuthService } from 'src/app/services/auth.service';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ShareSocialComponent } from 'src/app/Common/component/share-social/share-social.component';
import { stripHtmlAndClamp } from 'src/app/Common/functions/html.util';
import { BreadCrumbComponent } from 'src/app/Common/component/bread-crumb/bread-crumb.component';
import { Tabs2Component } from 'src/app/Common/layout/tabs2/tabs2.component';
import { AllTabsTypes } from 'src/app/Common/enums/details-tabs.enum';
import { RateSiteComponent } from 'src/app/components/home-page/components/rate-site/rate-site.component';
import { StoresService } from '../../services';
import { moduleTypeRating } from 'src/app/Common/enums/module-type-rating.enum';
import { RateItemComponent } from 'src/app/Common/component/rate-place/rate-item.component';
import { MediaViewerComponent } from "../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-store-details',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    GoogleMapsModule,
    TranslateModule,
    GalleriaModule,
    CarouselModule,
    TabViewModule,
    SidebarModule,
    RouterModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    ShareSocialComponent,
    BreadCrumbComponent,
    Tabs2Component,
    RateItemComponent,
    RateSiteComponent,
    // Directives
    LazyLoadSectionDirective,
    MediaViewerComponent
  ],
  templateUrl: './store-details.component.html',
  styleUrls: ['./store-details.component.scss']
})
export class StoreDetailsComponent {
  @ViewChildren(Tabs2Component) tabsComponents!: QueryList<Tabs2Component>;
  private subscriptions: Subscription[] = [];

  private localizationLanguageService = inject(LocalizationLanguageService);
  private metadataService = inject(MetadataService);
  private activatedRoute = inject(ActivatedRoute);
  private messageService = inject(MessageService);
  private dialogService = inject(DialogService);
  private storesService = inject(StoresService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private authService = inject(AuthService);
  private location = inject(Location);
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private platformId = inject(PLATFORM_ID);

  storeDetailelocationTab = false;
  currentLanguage: any;
  currentLoginInformation: any;
  moduleTypeRating: string;

  isLoadingSaveStore: boolean = false;
  isLoadingfavouritePlace: boolean = false;
  @ViewChild('locationTab') locationTab!: ElementRef;
  activeIndex: any = 0;
  id: number;
  // Start Map Configs
  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;

  center: google.maps.LatLngLiteral = { lat: 24.774265, lng: 46.738586 }; // Coordinates of Riyadh, Saudi Arabia
  zoom: any = 5;
  darkMode: any = darkModeTheme;
  markerPositions: any[] = [];
  locations: any;
  tabs: any;

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    this.center = {
      lat: this.storeDetails?.lat,
      lng: this.storeDetails?.long,
    };
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    this.selecedMarker = markerPosition?.content;
    this.infoWindow.open(marker);
  }
  closeAllInfoWindows(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.infoWindow?.close();
      this.center = {
        lat: this.storeDetails?.lat,
        lng: this.storeDetails?.long,
      };
    }
  }
  // End Map Configs

  storeDetails: any;
  isLoadingStoreDetails: boolean = false;
  storeId: any;

  showGalleria: boolean = false;
  // galleriaOptions: any[] = storeGalleriaOptions;

  // recommendedPlacesOptions = storeRecommendedPlacesOptions;
  relatedStores: any;
  isLoadingRelatedStores: boolean = false;

  isLoadingTestimonialsData: boolean = false;
  isLoadingTestimonials: boolean = false;

  rateForm = this.fb.group({
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
    massage: [
      '',
      {
        validators: [Validators.required, Validators.minLength(10), Validators.pattern('[a-zA-Z ]+')],
        updateOn: 'blur',
      },
    ],
  });
  get formControls(): any {
    return this.rateForm?.controls;
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
  isUserLoggedin: boolean = false;

  //rating
  ratingsPerPage = 3;
  currentRatingPage = 0;
  isLoadingReviews: boolean = false;

  homeShowFooter = false;

  constructor() {
    this.localizationLanguageService.updatePathAccordingLang();
  }
  ngOnInit(): void {
    this.isUserLoggedin = this.authService.isLoggedIn();
    this.initPageData();
    this.tabs = [
      {
        type: AllTabsTypes.Description,
        title: 'places.description',
        icon: 'assets/images-v2/pages/place-details/tabs/description.svg'
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
    this.moduleTypeRating = moduleTypeRating?.STORE;

  }
  removeQueryParams() {
    this.router?.navigate(['/stores/list']);
  }
  private initPageData(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.setupBrowserSpecificTasks();
      if (
        JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')
          ?.user
      ) {
        this.currentLoginInformation = JSON.parse(
          window?.localStorage?.getItem(keys?.userLoginData) || '{}'
        )?.user;
      }
    }

    this.activatedRoute.params.subscribe((params) => {
      this.storeId = params['id'];
      if (this.storeId) {
        this.getStoreDataById(this.storeId);
        // this.getRelatedStores(this.storeId);
        // this.fullUrl = environment.publicUrl + '/stores/' + this.storeId;
        this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
      }
    });
    // Subscribe to placeCategoryDetails with a proper unsubscribe mechanism
    this.publicService.placeCategoryDetails.subscribe((res) => {
      if (res?.id) {
        this.getStoreDataById(res.id);
        // this.getRelatedStores(res.id);
        if (isPlatformBrowser(this.platformId)) {
          window.scrollTo(0, 0); // Move this to a browser-specific function
        }
      }
    });
  }
  private setupBrowserSpecificTasks(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  saveStore(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingSaveStore = true;
      this.publicService
        .isSaved(this.storeDetails?.type, this.storeDetails?.id)
        .pipe(finalize(() => (this.isLoadingSaveStore = false)))
        .subscribe(
          (res: any) => {
            if (res.code == 200) {
              this.getStoreDataById(this.storeId, true);
              const messageKey = this.storeDetails.is_saved ? 'general.removeSaved' : 'general.addSaved';
              this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
              this.storeDetails.is_saved = true;
            } else {
              this.alertsService?.openToast(
                'error',
                res?.message || 'Error occurred'
              );
            }
          },
          (err: any) => {
            this.alertsService?.openToast('error', err?.message || 'An error occurred');
          }
        );
    }
  }
  goToLocation(): void {
    if (this.locationTab && isPlatformBrowser(this.platformId)) {
      this.activeIndex = 1;
      this.locationTab.nativeElement.scrollIntoView({ behavior: 'smooth' });
    }
  }

  getStoreDataById(id: any, preventLoading: boolean = false, reviewLoading?: boolean): void {
    reviewLoading ? this.isLoadingReviews = true : '';
    if (!id) return;
    if (!preventLoading) this.isLoadingStoreDetails = true;

    this.storesService.getStoreById(id).pipe(
      finalize(() => {
        this.isLoadingReviews = false;
      })
    ).subscribe({
      next: (res) => this.handleStoreDetailsResponse(res),
      error: (err) => this.handleErrorStoreDetails(err)
    });

  }
  handleStoreDetailsResponse(res: any): void {
    if (res.code != 200) {
      this.alertsService.openToast(
        'error',
        res.message || 'Error loading store data'
      );
      this.isLoadingStoreDetails = false;
      this.isLoadingTestimonials = false;
      return;
    }
    this.processStoreDetails(res.data);
    this.isLoadingStoreDetails = false;
    this.isLoadingTestimonials = false;
  }
  processStoreDetails(data: any): void {
    this.storeDetails = { ...data, rate: Math.ceil(data.rate || 0) };
    this.locations = [this.storeDetails?.region?.name, this.storeDetails?.city?.name]
    this.id = this.storeDetails?.id;
    if (this.storeDetails.lat == 0 && this.storeDetails.long == 0) {
      this.storeDetails.lat = 24.774265;
      this.storeDetails.long = 46.738586;
    }
    if (isPlatformBrowser(this.platformId)) {
      this.markerPositions = [
        {
          lat: this.storeDetails?.lat,
          lng: this.storeDetails?.long,
          place_icon: 'assets/images/icons/location2.svg',
          icon: {
            url: this.storeDetails?.place_icon
              ? this.storeDetails?.place_icon
              : 'assets/images/icons/location2.svg',
            size: this.storeDetails?.place_icon
              ? new google.maps.Size(30, 30)
              : new google.maps.Size(50, 50),
          },
          content: {
            id: this.storeDetails?.id,
            title: this.storeDetails.title,
            location_name: this.storeDetails.address_name,
            address: this.storeDetails.address,
            rate: this.storeDetails.rate ? this.storeDetails.rate : 0,
            reviews: this.storeDetails?.review,
            icon: this.storeDetails.place_icon,
            thumbil_image: this.storeDetails.image
              ? this.storeDetails?.image
              : 'assets/images/icons/location2.svg',
          },
        },
      ];
    }
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
    }
    this.handleGalleries();
    this.constructAddressName();
    this.setupMapDetails();
    this.isLoadingStoreDetails = false;
    this.isLoadingTestimonials = false;
    this.isLoadingTestimonialsData = false;
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.storeDetails.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.storeDetails.title}` },
      { name: 'description', content: stripHtmlAndClamp(this.storeDetails.description) },
    ]);
    this.metadataService.updateMetaTagsProperty([
      {
        property: 'og:url',
        content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stores/${this.storeDetails.slug}`,
      },
      { property: 'og:title', content: `${this.storeDetails.title}` },
      { property: 'og:description', content: stripHtmlAndClamp(this.storeDetails.description) },
    ]);
    this.metadataService.setSharePreviewImage(this.storeDetails.image);
  }
  private handleGalleries(): void {
    if (!this.storeDetails?.galleries?.length) {
      this.storeDetails.galleries = [
        {
          id: this.storeDetails?.id || null,
          file: this.storeDetails?.image,
          type: 'store',
        },
      ];
    }
  }
  private constructAddressName(): void {
    const { region, city } = this.storeDetails;
    if (region?.name && city?.name) {
      this.storeDetails.address_name = `${region.name}, ${city.name}`;
    } else {
      this.storeDetails.address_name = region?.name || city?.name || '';
    }
  }
  private setupMapDetails(): void {
    if (this.storeDetails?.lat !== null && this.storeDetails?.long !== null) {
      this.setAddressLink();
      this.setMapCenter();
      this.setupMarkerPositions();
    }
  }
  private setAddressLink(): void {
    if (this.storeDetails?.address_type === 'map') {
      this.storeDetails.address = this.publicService.createGoogleMapsLink(
        this.storeDetails.lat,
        this.storeDetails.long
      );
    }
  }
  private setMapCenter(): void {
    this.center = { lat: this.storeDetails.lat, lng: this.storeDetails.long };
  }
  async setupMarkerPositions(): Promise<any[]> {
    const storeDetails = await this.storeDetails; // Fetch data using a service
    if (!storeDetails) {
      return []; // Return an empty array if there are no store details
    }

    return [
      {
        lat: storeDetails.lat,
        lng: storeDetails.long,
        icon: {
          url: storeDetails.place_icon
            ? `${storeDetails.place_icon}`
            : 'assets/images/icons/location2.svg',
          size: storeDetails.place_icon
            ? new google.maps.Size(30, 30)
            : new google.maps.Size(50, 50),
        },
        content: this.createMarkerContent(storeDetails),
      },
    ];
  }
  private createMarkerContent(storeDetails): any {
    return {
      title: storeDetails.title,
      location_name: storeDetails.address_name,
      rate: storeDetails.rate || 0,
      reviews: storeDetails.review || 0,
      icon: storeDetails.place_icon,
      thumbil_image: storeDetails.image
        ? `${storeDetails.image}`
        : 'assets/images/icons/location2.svg',
    };
  }

  handleErrorStoreDetails(err: any): void {
    this.alertsService.openToast(
      'error',
      err || 'An unexpected error occurred'
    );
    this.isLoadingStoreDetails = false;
    this.isLoadingTestimonials = false;
  }
  recallNewStoreDetails(id: number): void {
    this.router.navigate(['/stores/', id]);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(0, 0);
    }
    this.getStoreDataById(id);
    // this.getRelatedStores(id);
  }

  // getRelatedStores(id: any, preventLoading: boolean = false): void {
  //   if (isPlatformBrowser(this.platformId)) {
  //     if (!id) return;
  //     if (!preventLoading) this.isLoadingRelatedStores = true;

  //     this.storesService.getRelatedStores(id).subscribe(
  //       (res) => this.handleRelatedStoresResponse(res),
  //       (err) => this.handleError(err)
  //     );
  //   }
  // }
  handleRelatedStoresResponse(res: any): void {
    if (res.code != 200) {
      this.alertsService.openToast(
        'error',
        res.message || 'Error loading related stores'
      );
      this.isLoadingRelatedStores = false;
      return;
    }
    this.relatedStores = res.data.items;
    // Further processing if needed
    this.isLoadingRelatedStores = false;
  }

  // sendFeedbackFromStore(): void {
  //   this.messageService.clear();
  //   if (isPlatformBrowser(this.platformId)) {
  //     if (this.rateForm?.valid) {
  //       this.isLoadingBtn = true;
  //       let data = {
  //         email: this.currentLoginInformation ? this.currentLoginInformation?.email : this.rateForm?.value?.email,
  //         name: this.currentLoginInformation ? this.currentLoginInformation?.full_name : this.rateForm?.value?.name,
  //         rate: this.rateForm?.value?.status,
  //         rateText: this.rateForm?.value?.massage,
  //         type: 'stores',
  //         parent_id: this.storeId,
  //       };
  //       this.storesService?.sendFeedbackFromStore(data)?.subscribe(
  //         (res: any) => {
  //           if (res?.code == 200) {
  //             this.isLoadingBtn = false;
  //             this.isLoadingTestimonials = true;
  //             this.getStoreDataById(this.storeId, true);
  //             this.getRelatedStores(this.storeId, true);
  //             this.rateForm?.reset();
  //             this.alertsService?.openToast(
  //               'success',
  //               this.publicService?.translateTextFromJson('general.successRate')
  //             );
  //           } else {
  //             this.isLoadingBtn = false;
  //             res?.message
  //               ? this.alertsService?.openToast('error', res?.message)
  //               : '';
  //           }
  //         },
  //         (err: any) => {
  //           err ? this.alertsService?.openToast('error', err) : '';
  //           this.isLoadingBtn = false;
  //         }
  //       );
  //     } else {
  //       this.publicService.validateAllFormFields(this.rateForm);
  //     }
  //   }
  // }
  sendFeedbackFromStore(feedbackData): void {
    this.messageService.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (feedbackData) {
        this.isLoadingBtn = true;
        this.storesService.sendFeedbackFromStore(feedbackData).subscribe(
          (res: any) => {
            if (res?.code == 200) {
              this.isLoadingBtn = false;
              this.getStoreDataById(this.storeId, true, true);
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
        this.publicService.validateAllFormFields(this.rateForm);
      }
    }
  }
  cancel(): void {
    this.rateForm?.reset();
  }
  getChatGpt(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingChat = true;
      this.storeDetails?.getChatGpt()?.subscribe(
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
  }
  onFileSelected(event: any): void {
    this.selectedFile = event.target.files[0] ?? null;
    if (this.selectedFile.size <= 5000) {
      this.chatForm.patchValue({
        message: this.selectedFile.name,
      });
      // this.chatForm.setValue()
    }
  }
  addToChat(e: any, message: any): void {
    this.chat.push({ text: message?.value, type: 'two' }), (message.value = '');
    setTimeout(() => {
      this.chat.push({ text: 'Thank You', type: 'one' });
    }, 5000);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(e.yPosition);
    }
  }
  share(link: any): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl,
      },
      styleClass: 'rate',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    });
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
        name: el?.content?.title,
        image: el?.content?.thumbil_image,
        address_name: el?.content?.location_name,
        review: 8,
        rate: 2,
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
  newRatingAdded(event: any): void {
    if (event == true) {
      this.isLoadingTestimonialsData = true;
      this.getStoreDataById(this.storeId, true);
    }
  }
  showReviews(): void {
    const ref = this.dialogService.open(ReviewsComponent, {
      header: this.publicService?.translateTextFromJson('general.reviews'),
      width: '50%',
      baseZIndex: 10000,
      data: this.storeDetails?.ratings,
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
  onLocationTabClick(): void {
    setTimeout(() => {
      this.tabsComponents.forEach((tabs) => {
        tabs.locationTabClick(1);
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

  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.isLoadingfavouritePlace = true;
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getStoreDataById(this.storeId, true);
          // this.getRelatedStores(this.storeId, true);
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
  /* --- End Add To Favorite Functions --- */

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
    this.isLoadingRelatedStores = false;
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
