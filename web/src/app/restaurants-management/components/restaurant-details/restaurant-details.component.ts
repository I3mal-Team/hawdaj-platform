// Modules
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { Component, inject, Inject, PLATFORM_ID, QueryList, Renderer2, ViewChild, ViewChildren } from '@angular/core';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ActivatedRoute, RouterModule } from '@angular/router';
import { environment } from 'src/environments/environment';
import { Subscription } from 'rxjs/internal/Subscription';
import { Observable } from 'rxjs/internal/Observable';
import { TranslateModule } from '@ngx-translate/core';
import { PaginatorModule } from 'primeng/paginator';
import { CarouselModule } from 'primeng/carousel';
import { of } from 'rxjs/internal/observable/of';
import { ToastModule } from 'primeng/toast';
// Service
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { DialogService } from 'primeng/dynamicdialog';

import { OfferDetailsModalComponent } from 'src/app/components/resturants/components/offer-details-modal/offer-details-modal.component';
import { ViewMenuModalComponent } from 'src/app/components/resturants/components/view-menu-modal/view-menu-modal.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ShareSocialComponent } from 'src/app/Common/component/share-social/share-social.component';
import { BreadCrumbComponent } from 'src/app/Common/component/bread-crumb/bread-crumb.component';
import { Tabs2Component } from 'src/app/Common/layout/tabs2/tabs2.component';
import { RateSiteComponent } from 'src/app/components/home-page/components/rate-site/rate-site.component';
import { AllTabsTypes } from 'src/app/Common/enums/details-tabs.enum';
import { MessageService } from 'primeng/api';
import { OfferComponent } from '../offer/offer.component';
import { AuthService } from 'src/app/services/auth.service';
import { catchError, finalize, tap, throwError } from 'rxjs';
import { RestaurantsService } from '../../services';
import { moduleTypeRating } from 'src/app/Common/enums/module-type-rating.enum';
import { RateItemComponent } from 'src/app/Common/component/rate-place/rate-item.component';
import { MediaViewerComponent } from "../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-restaurant-details',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    CarouselModule,
    RouterModule,
    CommonModule,
    FormsModule,
    ToastModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    ShareSocialComponent,
    BreadCrumbComponent,
    Tabs2Component,
    RateItemComponent,
    RateSiteComponent,
    OfferComponent,
    // Directives
    LazyLoadSectionDirective,
    NewFooterComponent,
    MediaViewerComponent
  ],
  templateUrl: './restaurant-details.component.html',
  styleUrls: ['./restaurant-details.component.scss']
})
export class RestaurantDetailsComponent {
  private unsubscribe: Subscription[] = [];
  @ViewChildren(Tabs2Component) tabsComponents!: QueryList<Tabs2Component>;

  currentLanguage: any;
  fullUrl: any;
  currentLoginInformation: any;

  restaurantDetails: any;
  restaurantId: any;
  isLoading: boolean = false;
  isLoadingBtn: boolean = false;
  menus: any = [];
  paginatedMenus: any = [];
  page: any = 1;
  perPage: any = 6;
  menusTotalCount: any;
  currentPage: any = 1;

  isLoadingMenus: boolean = false;
  isLoadingMoreMenus: boolean = false;
  isLoadingReviews: boolean = false;

  offers: any;
  isLoadingOffer: boolean = false;
  isLoadingSaveRestaurant: boolean = false;
  isLoadingfavouriteRestaurant: boolean = false;
  isUserLoggedin: boolean = false;


  locations: any;
  longitude: any;
  latitude: any;
  tabs: any;
  markerPositions: any;

  homeShowFooter: boolean = false;

  moduleTypeRating: string;

  private platformId = inject(PLATFORM_ID);
  private restaurantsService = inject(RestaurantsService);
  private metadataService = inject(MetadataService);
  private activatedRoute = inject(ActivatedRoute);
  private dialogService = inject(DialogService);
  private alertsService = inject(AlertsService);
  private publicService = inject(PublicService);
  private renderer = inject(Renderer2);
  private messageService = inject(MessageService);
  private authService = inject(AuthService);

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.tabs = [
      {
        type: AllTabsTypes.Description,
        title: 'places.description',
        icon: 'assets/images-v2/pages/restaurant-details/tabs/description.svg'
      },
      {
        type: AllTabsTypes.Menu,
        title: 'general.menu',
        icon: `assets/images-v2/pages/restaurant-details/tabs/menu.svg`
      },
      {
        type: AllTabsTypes.Location,
        title: 'places.location',
        icon: `assets/images-v2/pages/restaurant-details/tabs/location.svg`
      }
    ];
    this.moduleTypeRating = moduleTypeRating?.ZAD;
    this.isUserLoggedin = this.authService.isLoggedIn();

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
      this.restaurantId = params['id'];
      this.getRestaurantDetails(true);
      this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();

    });

    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      navigator?.permissions?.query({ name: 'geolocation' }).then((result) => {
        result?.addEventListener('change', () => {
          this.handleLocationPermissionChange(result);
        });
        if (result.state === 'granted') {
          this.getLocation();
        } else {
          this.setDefaultLocation();
        }
      }).catch(() => {
        this.setDefaultLocation();
      });
    } else {
      this.setDefaultLocation();
    }
  }
  setDefaultLocation(): void {
    this.latitude = 24.774265;
    this.longitude = 46.738586;
  }
  handleLocationPermissionChange(result: PermissionStatus): void {
    if (result.state === 'granted') {
      this.getLocation();
    } else {
      this.setDefaultLocation();
    }
  }
  saveRestaurant(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingSaveRestaurant = true;
      this.publicService
        .isSaved(this.restaurantDetails?.type, this.restaurantDetails?.id)
        .pipe(
          tap((res: any) => {
            if (res.code === 200) {
              const messageKey = this.restaurantDetails.is_saved ? 'general.removeSaved' : 'general.addSaved';
              this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));

              this.restaurantsService
                ?.getRestaurantById(this.restaurantDetails?.slug)
                ?.subscribe(
                  (res: any) => this.handleRestaurantDetailsResponse(res),
                  (err: any) => this.handleRestaurantDetailsError(err)
                );
              this.restaurantDetails.is_saved = true;
            } else {
              this.alertsService?.openToast(
                'error',
                res?.message || 'Error occurred'
              );
            }
          }),
          catchError((err: any) => {
            this.alertsService?.openToast('error', err?.message || 'An error occurred');
            return throwError(() => err);
          }),
          finalize(() => (this.isLoadingSaveRestaurant = false))
        )
        .subscribe({
        });

    }
  }
  getLocation(): any {
    if (navigator?.geolocation) {
      navigator?.geolocation.getCurrentPosition(
        (position) => {
          this.latitude = position?.coords?.latitude;
          this.longitude = position?.coords?.longitude;
          this.getRestaurantDetails();
        },
        (error) => {
          console.error('Error getting location:', error);
        }
      );
    } else {
      console.error('Geolocation is not supported by this browser.');
    }
  }

  private setupBrowserSpecificTasks(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }

  getRestaurantDetails(loadingShimmer?: boolean, isLoadingReviews?: boolean): void {
    if (loadingShimmer) {
      this.isLoading = true;
    }
    if (isLoadingReviews) {
      this.isLoadingReviews = true;
    }

    this.restaurantsService?.getRestaurantById(this.restaurantId)?.pipe(
      tap((res: any) => this.handleRestaurantDetailsResponse(res)),
      catchError((err: any) => {
        this.handleRestaurantDetailsError(err);
        return throwError(() => err);
      })
    ).subscribe({});

  }
  private handleRestaurantDetailsResponse(res: any): void {
    if (res?.code === 200) {
      if (isPlatformBrowser(this.platformId)) {
        this.processRestaurantDetails(res);
      }
      if (isPlatformServer(this.platformId)) {
        this.processRestaurantDetails(res);
      }
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoading = false;
    this.isLoadingReviews = false;
  }
  private processRestaurantDetails(res: any): void {
    this.restaurantDetails = res?.data;
    this.locations = [this.restaurantDetails?.region?.name, this.restaurantDetails?.city?.name]
    if (isPlatformBrowser(this.platformId)) {
      this.markerPositions = [
        {
          lat: this.restaurantDetails?.lat,
          lng: this.restaurantDetails?.long,
          place_icon: 'assets/images/icons/location2.svg',
          icon: {
            url: this.restaurantDetails?.place_icon
              ? this.restaurantDetails?.place_icon
              : 'assets/images/icons/location2.svg',
            size: this.restaurantDetails?.place_icon
              ? new google.maps.Size(30, 30)
              : new google.maps.Size(50, 50),
          },
          content: {
            id: this.restaurantDetails?.id,
            title: this.restaurantDetails.title,
            location_name: this.restaurantDetails.address_name,
            address: this.restaurantDetails.address,
            rate: this.restaurantDetails.rate ? this.restaurantDetails.rate : 0,
            reviews: this.restaurantDetails?.review,
            icon: this.restaurantDetails.place_icon,
            thumbil_image: this.restaurantDetails.image
              ? this.restaurantDetails?.image
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
    this.restaurantId = res?.data?.id;
    this.getOffers();
    this.getRestaurantMenu();
    this.calculateRestaurantDistance();
    this.setRestaurantRate();
    this.setRestaurantFavoriteStatus();
    this.setRestaurantAddressDetails();
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.restaurantDetails?.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.restaurantDetails?.title}` },
      { name: 'description', content: this.restaurantDetails?.description },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/restaurants/${this.restaurantDetails?.slug}` },
      { property: 'og:title', content: `${this.restaurantDetails?.title}` },
      { property: 'og:description', content: this.restaurantDetails?.description },
    ]);
    this.metadataService.setSharePreviewImage(this?.restaurantDetails?.image);
  }

  private calculateRestaurantDistance(): void {
    this.restaurantDetails['distance'] = Math.round(
      this.publicService?.calculateDistance(
        this.latitude,
        this.longitude,
        this.restaurantDetails?.lat,
        this.restaurantDetails?.long
      )
    );
  }
  private setRestaurantRate(): void {
    this.restaurantDetails['rate'] = this.restaurantDetails?.rate ? Math.round(this.restaurantDetails?.rate) : 0;
  }
  private setRestaurantFavoriteStatus(): void {
    this.restaurantDetails['isFavorite'] = false;
  }
  private setRestaurantAddressDetails(): void {
    if (
      this.restaurantDetails?.lat &&
      this.restaurantDetails?.long &&
      this.restaurantDetails?.address_type == 'map'
    ) {
      this.restaurantDetails['address'] =
        this.publicService.createGoogleMapsLink(
          this.restaurantDetails?.lat,
          this.restaurantDetails?.long
        );
    }
    if (
      this.restaurantDetails?.region?.name &&
      this.restaurantDetails?.city?.name
    ) {
      this.restaurantDetails['address_name'] =
        this.restaurantDetails?.region?.name +
        ', ' +
        this.restaurantDetails?.city?.name;
    } else if (this.restaurantDetails?.region?.name) {
      this.restaurantDetails['address_name'] =
        this.restaurantDetails?.region?.name;
    } else if (this.restaurantDetails?.city?.name) {
      this.restaurantDetails['address_name'] =
        this.restaurantDetails?.city?.name;
    }
  }
  private handleRestaurantDetailsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoading = false;
  }

  viewMenu(): void {
    const ref = this?.dialogService?.open(ViewMenuModalComponent, {
      width: '50%',
      height: '70%',
      styleClass: 'auth-dialog',
      data: this.restaurantDetails?.menu_file,
    });
  }
  downloadMenu(url?: any): void {
    // this.downloadFile('https://www.clickdimensions.com/links/TestPDFfile.pdf');
    this.downloadFile(url);
  }
  downloadFile(url?: string): void {
    if (!url) {
      console.error('No URL provided for download');
      return;
    }
    // Check if the URL is already a PDF
    if (url.endsWith('.pdf')) {
      this.openInNewTab(url);
    } else {
      // Convert to PDF (assuming you have an API endpoint to handle this)
      this.convertToPdf(url).subscribe(convertedUrl => {
        this.openInNewTab(convertedUrl);
      });
    }
  }
  private openInNewTab(url: string): void {
    if (isPlatformBrowser(this.platformId)) {
      const link = this.renderer.createElement('a');
      this.renderer.setAttribute(link, 'href', url);
      this.renderer.setAttribute(link, 'target', '_blank');
      this.renderer.appendChild(document.body, link);
      link.click();
      this.renderer.removeChild(document.body, link);
    }
  }
  private convertToPdf(url: string): Observable<string> {
    // Replace with actual HTTP request to your server-side conversion endpoint
    // Example: return this.http.post<string>(this.conversionEndpoint, { url });
    let URL_of_converted_PDF = url;
    return of(URL_of_converted_PDF); // Placeholder
  }

  getRestaurantMenu(loadingMore?: boolean): void {
    loadingMore
      ? (this.isLoadingMoreMenus = true)
      : (this.isLoadingMenus = true);
    this.restaurantsService?.getRestaurantMenu(3, { page: this.page, perPage: 10 })?.pipe(
      tap((res: any) => {
        if (res?.code === 200) {
          this.handleSuccessfulMenuResponse(res);
        } else {
          this.handleFailedMenuResponse(res);
        }
      }),
      catchError((err: any) => {
        this.handleMenuError();
        return throwError(() => err);
      }),).subscribe({});

  }
  private handleSuccessfulMenuResponse(res: any): void {
    if (this.page == 1) {
      this.menus = res?.data?.items || [];
      this.getPaginatedMenus();
    } else {
      this.menus.push(...(res?.data?.items || []));
    }
    this.menusTotalCount = res?.data?.total;
    this.isLoadingMenus = false;
    this.isLoadingMoreMenus = false;
  }
  private handleFailedMenuResponse(res: any): void {
    res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    this.isLoadingMenus = false;
    this.isLoadingMoreMenus = false;
  }
  private handleMenuError(): void {
    this.isLoadingMenus = false;
    this.isLoadingMoreMenus = false;
  }
  onPageChangeMenus(event: any): void {
    this.currentPage = event.page + 1;
    this.getPaginatedMenus();
  }
  getPaginatedMenus(): any {
    const startIndex: any = (this.currentPage - 1) * 6;
    const endIndex: any = startIndex + 6;
    this.paginatedMenus = this.menus?.slice(startIndex, endIndex);
  }
  loadMoreMenus(): void {
    this.page++;
    this.getRestaurantMenu(true);
  }

  getOffers(): void {
    this.isLoadingOffer = true;
    this.restaurantsService?.getOffers(3)?.subscribe({
      next: (res: any) => {
        if (res?.code === 200) {
          this.handleSuccessfulOffersResponse(res);
        } else {
          this.handleFailedOffersResponse(res);
        }
      },
      error: (err: any) => {
        this.handleOffersError();
      },
      complete: () => {
      }
    });

  }
  private handleSuccessfulOffersResponse(res: any): void {
    this.offers = res?.data?.items[0] || [];
    this.isLoadingOffer = false;
  }
  private handleFailedOffersResponse(res: any): void {
    res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    this.isLoadingOffer = false;
  }
  private handleOffersError(): void {
    this.isLoadingOffer = false;
  }

  openOfferDetails(): void {
    let data: any = this.offers;
    data['food_categories'] = this.restaurantDetails?.food_categories;
    data['distance'] = this.restaurantDetails?.distance;
    data['address_name'] = this.restaurantDetails?.address_name;
    const ref = this?.dialogService?.open(OfferDetailsModalComponent, {
      header: this.publicService?.translateTextFromJson('general.offerDetails'),
      width: '35%',
      data: data,
      dismissableMask: true,
    });
  }
  sendFeedbackFromRestaurant(feedbackData): void {
    this.messageService.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (feedbackData) {
        this.isLoadingBtn = true;
        this.restaurantsService.sendFeedbackFromRestaurant(feedbackData).pipe(
          tap((res: any) => {
            if (res?.code === 200) {
              this.activatedRoute.params.subscribe((params) => {
                this.restaurantId = params['id'];
                this.getRestaurantDetails(false, true)
              });
              this.alertsService?.openToast(
                'success',
                this.publicService?.translateTextFromJson('general.successRate')
              );
              if (isPlatformBrowser(this.platformId)) {
                window.scrollTo({ top: 200, behavior: 'smooth' });
              }
            } else {
              if (res?.message) {
                this.alertsService?.openToast('error', res?.message);
              }
            }
          }),
          catchError((err: any) => {
            this.alertsService?.openToast('error', err || 'An error occurred');
            return throwError(() => err);
          }),
          finalize(() => (this.isLoadingBtn = false))
        ).subscribe({});

      } else {
        // this.publicService.validateAllFormFields(this.rateForm);
      }
    }
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
  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.isLoadingfavouriteRestaurant = true;
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.restaurantsService?.getRestaurantById(this.restaurantId);
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
          this.restaurantsService?.getRestaurantById(this.restaurantDetails?.slug)?.subscribe(
            (res: any) => this.handleRestaurantDetailsResponse(res),
            (err: any) => this.handleRestaurantDetailsError(err)
          );
          this.restaurantDetails.is_favourite = true;
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => {
        this.isLoadingfavouriteRestaurant = false;
      })
    ).subscribe();
    this.unsubscribe.push(addToFavoriteSubscription);
  }
  /* --- End Add To Favorite Functions --- */

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
  }
  private setErrorMessage(message: string): void {
    // Implementation for displaying the error message, e.g., using a sweetalert
    this.alertsService?.openToast('error', message);
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
