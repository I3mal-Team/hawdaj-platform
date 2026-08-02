// Modules
import { Component, ElementRef, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer, NgOptimizedImage } from '@angular/common';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { catchError } from 'rxjs/internal/operators/catchError';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { finalize } from 'rxjs/internal/operators/finalize';
import { Subscription } from 'rxjs/internal/Subscription';
import { RadioButtonModule } from 'primeng/radiobutton';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { CarouselModule } from 'primeng/carousel';
import { CheckboxModule } from 'primeng/checkbox';
import { tap } from 'rxjs/internal/operators/tap';
import { Subject } from 'rxjs/internal/Subject';
import { SidebarModule } from 'primeng/sidebar';
import { RatingModule } from 'primeng/rating';
import { MessageService } from 'primeng/api';
import { ToastModule } from 'primeng/toast';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { environment } from 'src/environments/environment';
import { RestaurantsService } from 'src/app/restaurants-management';

// Components
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { RegionModalComponent } from 'src/app/modules/shared/components/region-modal/region-modal.component';
import { CityModalComponent } from 'src/app/modules/shared/components/city-modal/city-modal.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';

// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { TabViewModule } from 'primeng/tabview';
import { AuthService } from 'src/app/services/auth.service';
// Pipes
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-resturants-list',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    RadioButtonModule,
    PaginatorModule,
    TranslateModule,
    CheckboxModule,
    CarouselModule,
    SidebarModule,
    RouterModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    TabViewModule,
    // Components
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    NgOptimizedImage,
    // Pipes
    StripHtmlPipe
  ],
  templateUrl: './resturants-list.component.html',
  styleUrls: ['./resturants-list.component.scss']
})
export class ResturantsListComponent {
  heroSectionInView = false;
  specialitySectionInView = false;
  topRestaurantsSectionInView = false;
  nearbyRestaurantsSectionInView = false;
  footerSectionInView = false;


  private subscriptions: Subscription[] = [];
  private searchSubject = new Subject<any>();
  currentLanguage: any;

  // Start Restaurants Categories Variables
  restaurantCategories: any = [];
  isLoadingRestaurantCategories: boolean = false;
  restaurantCategoriesIds: any = [];
  selectedRestaurantCategories: any = [];
  @ViewChild('category') category!: ElementRef;
  configCategories: any = [
    {
      breakpoint: '1024px',
      numVisible: 4,
      numScroll: 1,
    },
    {
      breakpoint: '768px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '560px',
      numVisible: 2,
      numScroll: 1,
    }
  ];
  currentCategory: any;
  // End Restaurants Categories Variables

  foodCategories: any = [];
  isLoadingFoodCategories: boolean = false;
  selectedFoodCategory: any = [];
  selectedScore: number | undefined;;
  region: any = null;
  city: any = null;
  displayFilter: boolean = false;
  showNearest: boolean = false;
  showNearest2: boolean = false;
  scores: any = [
    { rate: 5 },
    { rate: 4 },
    { rate: 3 },
    { rate: 2 },
    { rate: 1 }
  ];

  latitude: any;
  longitude: any;
  topRestaurants: any = [];
  paginatedTopRestaurants: any = [];
  isLoadingTopRestaurants: boolean = false;
  isLoadingMoreTopRestaurants: boolean = false;
  isLoadingSearch: boolean = false;
  topRestaurantsPage: any = 1;
  currentRestaurantPage: any = 1;
  topRestaurantsPerPage: any = 6;
  topRestaurantsTotalCount: any;
  keyword: any;
  @ViewChild('paginatorRestaurant') paginatorRestaurant: Paginator | undefined;

  nearbyRestaurants: any = [];
  paginatedNearbyRestaurants: any = [];
  isLoadingNearbyRestaurants: boolean = false;
  isLoadingMoreRestaurants: boolean = false;
  page: any = 1;
  currentPage: any = 1;
  perPage: any = 6;
  nearbyRestaurantsRows: any = 6;
  nearbyRestaurantsTotalCount: any;
  categoryId: any;
  isChangePage: boolean = false;
  isUserLoggedin: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private restaurantsService: RestaurantsService,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private messageService: MessageService,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    private publicService: PublicService,
    private authService: AuthService,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.isUserLoggedin = this.authService.isLoggedIn();
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('restauranstsList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('restauranstsList');

    }
    this.activatedRoute.params.subscribe(params => {
      this.categoryId = params['category'];
    });
    this.publicService?.restaurantCategory?.subscribe((res: any) => {
      this.restaurantCategories?.forEach((el: any) => {
        el.isSelected = false;
      });
      this.restaurantCategoriesIds = [];
      this.selectedRestaurantCategories = [];
      this.restaurantCategories?.forEach((item: any) => {
        if (item?.id == res?.id) {
          this.selectCategory(item);
          this.category.nativeElement.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
    this.searchSubject.pipe(debounceTime(750)).subscribe(event => {
      this.searchService(event);
    });
    this.loadData();
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | زاد`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | زاد` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/restaurants/list` },
      { property: 'og:title', content: `هودج | زاد` },
    ]);
  }
  loadData(): void {
    this.getRestaurantCategories();

    this.getFoodCategories();
    navigator?.permissions?.query({ name: "geolocation" }).then((result) => {
      result?.addEventListener("change", () => {
      });
      if (result.state === "granted") {
        this.getLocation();
      }
      else if (result?.state === "prompt") {
        this.latitude = 24.774265;
        this.longitude = 46.738586;
        this.getTopRestaurants();
        this.getNearbyRestaurants();
      } else if (result.state === "denied") {
        this.latitude = 24.774265;
        this.longitude = 46.738586;
        this.getTopRestaurants();
        this.getNearbyRestaurants();
      }
    });
  }
  removeQueryParams() {
    this.router?.navigate(['/restaurants/list']);
  }

  // Start Restaurants Categories Functions
  getRestaurantCategories(): void {
    this.isLoadingRestaurantCategories = true;
    this.restaurantsService?.getCategories()?.subscribe(
      (res: any) => this.handleCategoriesResponse(res),
      (err: any) => this.handleCategoriesError(err)
    );
  }
  private handleCategoriesResponse(res: any): void {
    if (res?.code == 200) {
      this.initializeCategories(res.data);
      this.isLoadingRestaurantCategories = false;
    } else {
      this.handleCategoriesFailure(res.message);
    }
  }
  private initializeCategories(data: any): void {
    if (data) {
      data.forEach((item: any) => {
        item.isSelected = false;
      });
      this.restaurantCategories = data;
      this.currentCategory = this.restaurantCategories[0];
      if (this.categoryId) {
        this.restaurantCategories?.forEach((el: any) => {
          el.isSelected = false;
        });
        this.restaurantCategoriesIds = [];
        this.selectedRestaurantCategories = [];
        this.restaurantCategories?.forEach((item: any) => {
          if (item?.id == parseInt(this.categoryId)) {
            this.selectCategory(item);
            this.category.nativeElement.scrollIntoView({ behavior: 'smooth' });
          }
        });
      }
    }
  }
  private handleCategoriesFailure(message: any): void {
    message ? this.alertsService?.openToast('error', message) : '';
    this.isLoadingRestaurantCategories = false;
  }
  private handleCategoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingRestaurantCategories = false;
  }
  selectCategory(item: any): void {
    this.restaurantCategories?.forEach((el: any) => {
      if (el?.id == item?.id) {
        el.isSelected = !el.isSelected;
      }
    });
    let arr: any = [];
    let arr2: any = [];
    this.restaurantCategories?.forEach((el: any) => {
      if (el?.isSelected) {
        arr?.push([el?.id]);
        arr2?.push(el);
      }
    });
    this.restaurantCategoriesIds = arr;
    this.selectedRestaurantCategories = arr2;
    this.getTopRestaurants(false);
  }
  resetCategories(): void {
    this.restaurantCategories?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.restaurantCategoriesIds = [];
    this.selectedRestaurantCategories = [];
    this.getTopRestaurants(false);
    // this.removeQueryParams();
  }
  // End Restaurants Categories Functions

  // Start Food Categories Functions
  getFoodCategories(): void {
    this.isLoadingFoodCategories = true;
    this.restaurantsService?.getFoodCategories()?.subscribe(
      (res: any) => this.handleFoodCategoriesResponse(res),
      (err: any) => this.handleFoodCategoriesError(err)
    );
  }
  private handleFoodCategoriesResponse(res: any): void {
    if (res?.code == 200) {
      this.foodCategories = res?.data;
    } else {
      this.handleFoodCategoriesFailure(res.message);
    }
    this.isLoadingFoodCategories = false;
  }
  private handleFoodCategoriesFailure(message: any): void {
    message ? this.alertsService?.openToast('error', message) : '';
  }
  private handleFoodCategoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingFoodCategories = false;
  }
  // End Food Categories Functions

  getLocation() {
    if (isPlatformBrowser(this.platformId)) {
      if (navigator?.geolocation) {
        navigator?.geolocation.getCurrentPosition(
          (position) => {
            this.latitude = position?.coords?.latitude ? position?.coords?.latitude : null;
            this.longitude = position?.coords?.longitude ? position?.coords?.longitude : null;
            this.getTopRestaurants();
            this.getNearbyRestaurants();
          },
          (error) => {
            console.error('Error getting location:', error);
          }
        );
      } else {
        console.error('Geolocation is not supported by this browser.');
      }
    }
  }

  getTopRestaurants(loadingMore?: boolean, rate?: any): void {
    this.messageService?.clear();
    loadingMore ? this.isLoadingMoreRestaurants = true : this.isLoadingTopRestaurants = true;
    this.restaurantsService?.getRestaurants({
      page: this.topRestaurantsPage,
      per_page: this.topRestaurantsPerPage,
      lat: this.latitude,
      lng: this.longitude,
      region_id: this.region ? this.region.id : null,
      city_id: this.city ? this.city.id : null,
      categories: this.restaurantCategoriesIds?.length > 0 ? this.restaurantCategoriesIds : null,
      rate: this.selectedScore,
      keyword: this.keyword,

    }
    )?.subscribe(
      (res: any) => {
        this.handleTopRestaurantsResponse(res);
      },
      (err: any) => {
        this.handleTopRestaurantsError(err);
      }
    );
  }
  private handleTopRestaurantsResponse(res: any): void {
    if (res?.code == 200) {
      this.processRestaurantItems(res.data.items, res);
    } else {
      this.handleTopRestaurantsFailure(res.message);
    }
  }
  private processRestaurantItems(items: any[], res): void {
    items?.forEach((element: any) => {
      element.distance = Math.round(this.publicService?.calculateDistance(this.latitude, this.longitude, element.lat, element.long));
      element.rate = element.rate ? Math.round(element.rate) : 0;
      element.isFavorite = false;
      if (element.lat && element.long && element.address_type == 'map') {
        element.address = this.publicService.createGoogleMapsLink(element.lat, element.long);
      }
      element.address_name = element.region?.name ? element.region.name : (element.city?.name ? element.city.name : '');
    });

    // if (this.topRestaurantsPage == 1) {
    this.topRestaurants = items || [];
    // }
    // else {
    //   this.topRestaurants.push(...(items || []));
    // }

    this.topRestaurantsTotalCount = res?.data?.total;
    this.isLoadingTopRestaurants = false;
    this.isLoadingSearch = false;
    this.isChangePage = false;
    this.isLoadingMoreRestaurants = false;
    // this.topRestaurantsPage == 1 ? this.getPaginatedRestaurants() : null;
  }
  private handleTopRestaurantsFailure(message: any): void {
    message ? this.alertsService?.openToast('error', message) : '';
    this.isLoadingTopRestaurants = false;
    this.isLoadingSearch = false;
    this.isChangePage = false;
    this.isLoadingMoreRestaurants = false;
  }
  private handleTopRestaurantsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingTopRestaurants = false;
    this.isLoadingSearch = false;
    this.isLoadingMoreRestaurants = false;
    this.isChangePage = false;
  }
  onPageChangeTopRestaurants(event: any): void {
    this.topRestaurantsPage = event.page + 1;
    // this.getPaginatedRestaurants();
    this.isChangePage ? '' : this.getTopRestaurants();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 1200, behavior: 'smooth' });
    }
  }
  getPaginatedRestaurants(): any {
    const startIndex: any = (this.currentRestaurantPage - 1) * 6;
    const endIndex: any = startIndex + 6;
    this.paginatedTopRestaurants = this.topRestaurants?.slice(startIndex, endIndex);
  }
  loadMoreTopRestaurants(): void {
    this.topRestaurantsPage++;
    this.getTopRestaurants(true);
  }
  changePageActiveNumber(number: number): void {
    this.isChangePage = true;
    this.paginatorRestaurant?.changePage(number - 1);
  }
  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchService(event: any): void {
    this.keyword = event;
    this.isLoadingSearch = true;
    this.onChangeFilter();
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.keyword = null;
    this.isLoadingSearch = true;
    this.onChangeFilter();
  }
  getNearbyRestaurants(loadingMore?: boolean): void {
    loadingMore ? this.isLoadingMoreRestaurants = true : this.isLoadingNearbyRestaurants = true;
    this.restaurantsService?.getRestaurants({ page: this.page, per_page: this.perPage })?.subscribe(
      (res: any) => this.handleNearbyRestaurantsResponse(res),
      (err: any) => this.handleNearbyRestaurantsError(err)
    );
  }
  private handleNearbyRestaurantsResponse(res: any): void {
    if (res?.code == 200) {
      this.processNearbyRestaurantItems(res.data.items, res);
    } else {
      this.handleNearbyRestaurantsFailure(res.message);
    }
  }
  private processNearbyRestaurantItems(items: any[], res): void {
    items?.forEach((element: any) => {
      element.rate = element.rate ? Math.round(element.rate) : 0;
      element.distance = Math.round(this.publicService?.calculateDistance(this.latitude, this.longitude, element.lat, element.long));
      if (element.lat && element.long && element.address_type == 'map') {
        element.address = this.publicService.createGoogleMapsLink(element.lat, element.long);
      }
      element.address_name = element.region?.name ? element.region.name : (element.city?.name ? element.city.name : '');
    });
    // if (this.page == 1) {
    this.nearbyRestaurants = items || [];
    // this.getPaginatedData();
    // }
    //  else {
    //   this.nearbyRestaurants.push(...(items || []));
    // }
    this.nearbyRestaurantsTotalCount = res?.data?.total;
    this.isLoadingNearbyRestaurants = false;
    this.isLoadingMoreRestaurants = false;
  }
  private handleNearbyRestaurantsFailure(message: any): void {
    message ? this.alertsService?.openToast('error', message) : '';
    this.isLoadingNearbyRestaurants = false;
    this.isLoadingMoreRestaurants = false;
  }
  private handleNearbyRestaurantsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingNearbyRestaurants = false;
    this.isLoadingMoreRestaurants = false;
  }
  onPageChangeRestaurant(event: any): void {
    this.page = event.page + 1;
    // this.getPaginatedData();
    this.getNearbyRestaurants();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 2350, behavior: 'smooth' });
    }
  }
  getPaginatedData(): any {
    const startIndex: any = (this.currentPage - 1) * this.nearbyRestaurantsRows;
    const endIndex: any = startIndex + this.nearbyRestaurantsRows;
    this.paginatedNearbyRestaurants = this.nearbyRestaurants?.slice(startIndex, endIndex);
  }
  loadMoreRestaurants(): void {
    this.page++;
    this.getNearbyRestaurants(true);
  }
  onChangeFilter(): void {
    this.topRestaurantsPage = 1;
    this.changePageActiveNumber(this.topRestaurantsPage);
    this.getTopRestaurants();
  }
  clear(): void {
    this.selectedFoodCategory = [];
    this.selectedScore = null;
    this.region = null;
    this.city = null;
    this.keyword = null;
    this.showNearest = false;
    this.topRestaurantsPage = 1;
    this.changePageActiveNumber(this.topRestaurantsPage);
    this.getTopRestaurants();
  }
  openMap(el: any): void {
    let data: any = [];
    data?.push({
      lat: el?.lat,
      lng: el?.long,
      name: el?.description,
      image: el?.image,
      address_name: el?.address_name,
      review: 8,
      rate: 2,
      place_icon: el?.place_icon
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

  openRegionsModal(): void {
    const ref = this.dialogService.open(RegionModalComponent, {
      header: this.publicService?.translateTextFromJson('general.allRegions'),
      dismissableMask: true,
      width: '40%',
      data: this.region ? this.region : '',
      styleClass: 'see-all-modal'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
        this.region = res?.region;
        this.city = null;
        this.onChangeFilter();
      }
    })
  }
  clearRegions(): void {
    this.region = null;
    this.onChangeFilter();
  }
  openCityModal(): void {
    const ref = this.dialogService.open(CityModalComponent, {
      header: this.publicService?.translateTextFromJson('general.allCities'),
      dismissableMask: true,
      width: '40%',
      data: { data: this.city ? this.city : '', regionId: this.region?.id },
      styleClass: 'see-all-modal'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
        this.city = res?.city;
        this.onChangeFilter();
      }
    })
  }
  clearCity(): void {
    this.city = null;
    this.onChangeFilter();
  }

  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getNearbyRestaurants(true);
          this.getTopRestaurants(true);
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.addFavorites'));
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => { })
    ).subscribe();

    this.subscriptions.push(addToFavoriteSubscription);
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
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
