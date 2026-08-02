// Modules
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { Component, Inject, PLATFORM_ID, Renderer2 } from '@angular/core';
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
import { RestaurantsService } from 'src/app/services/restaurants.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { DialogService } from 'primeng/dynamicdialog';

import { ReviewEventSliderComponent } from 'src/app/shared/components/review-event-slider/review-event-slider.component';
import { OfferDetailsModalComponent } from 'src/app/components/resturants/components/offer-details-modal/offer-details-modal.component';
import { ViewMenuModalComponent } from 'src/app/components/resturants/components/view-menu-modal/view-menu-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from "../../../../modules/shared/components/new-footer/new-footer.component";
// Pipes
import { SafeHtmlPipe } from 'src/app/Common/pipes/safe-html.pipe';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';
import { stripHtmlAndClamp } from 'src/app/Common/functions/html.util';

@Component({
  selector: 'app-resturant-details',
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
    ReviewEventSliderComponent,
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    FooterComponent,
    // Directives
    LazyLoadSectionDirective,
    NewFooterComponent,
    // Pipes
    SafeHtmlPipe,
    StripHtmlPipe
  ],
  templateUrl: './resturant-details.component.html',
  styleUrls: ['./resturant-details.component.scss']
})
export class ResturantDetailsComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  restaurantDetails: any;
  restaurantId: any;
  isLoading: boolean = false;
  menus: any = [];
  paginatedMenus: any = [];
  page: any = 1;
  perPage: any = 6;
  menusTotalCount: any;
  currentPage: any = 1;

  isLoadingMenus: boolean = false;
  isLoadingMoreMenus: boolean = false;

  offers: any;
  isLoadingOffer: boolean = false;

  longitude: any;
  latitude: any;

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private restaurantsService: RestaurantsService,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private renderer: Renderer2
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  // ngOnInit(): void {
  //   if (isPlatformBrowser(this.platformId)) {
  //     this.currentLanguage = this.publicService.getCurrentLanguage();
  //     navigator?.permissions?.query({ name: 'geolocation' }).then((result) => {
  //       result?.addEventListener('change', () => { });
  //       if (result.state === 'granted') {
  //         this.getLocation();
  //       } else if (result?.state === 'prompt') {
  //         this.latitude = 24.774265;
  //         this.longitude = 46.738586;
  //         this.getRestaurantDetails();
  //       } else if (result.state === 'denied') {
  //         this.latitude = 24.774265;
  //         this.longitude = 46.738586;
  //         this.getRestaurantDetails();
  //       }
  //     });
  //   }
  //   this.activatedRoute.params.subscribe((params) => {
  //     this.restaurantId = params['id'];
  //   });

  // }
  ngOnInit(): void {
    this.activatedRoute.params.subscribe((params) => {
      this.restaurantId = params['id'];
      this.getRestaurantDetails();
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
  getRestaurantDetails(): void {
    this.isLoading = true;
    this.restaurantsService?.getRestaurantById(this.restaurantId)?.subscribe(
      (res: any) => this.handleRestaurantDetailsResponse(res),
      (err: any) => this.handleRestaurantDetailsError(err)
    );
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
  }
  private processRestaurantDetails(res: any): void {
    this.restaurantDetails = res?.data;
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
      { name: 'description', content: stripHtmlAndClamp(this.restaurantDetails?.description) },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/restaurants/${this.restaurantDetails?.slug}` },
      { property: 'og:title', content: `${this.restaurantDetails?.title}` },
      { property: 'og:description', content: stripHtmlAndClamp(this.restaurantDetails?.description) },
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
    // this.restaurantsService?.getRestaurantMenu(this.restaurantId, this.page, 10)?.subscribe(
    this.restaurantsService?.getRestaurantMenu(3, this.page, 10)?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          this.handleSuccessfulMenuResponse(res);
        } else {
          this.handleFailedMenuResponse(res);
        }
      },
      (err: any) => {
        this.handleMenuError();
      }
    );
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
    this.restaurantsService?.getOffers(3)?.subscribe(
      (res: any) => {
        if (res?.code == 200) {
          this.handleSuccessfulOffersResponse(res);
        } else {
          this.handleFailedOffersResponse(res);
        }
      },
      (err: any) => {
        this.handleOffersError();
      }
    );
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
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
