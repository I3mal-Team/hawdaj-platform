// Modules
import { Component, ElementRef, inject, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer, NgOptimizedImage } from '@angular/common';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { ReactiveFormsModule, FormsModule, Validators } from '@angular/forms';
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
// Components
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { RegionModalComponent } from 'src/app/modules/shared/components/region-modal/region-modal.component';
import { CityModalComponent } from 'src/app/modules/shared/components/city-modal/city-modal.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { TabViewModule } from 'primeng/tabview';
import { AuthService } from 'src/app/services/auth.service';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ListSliderComponent } from 'src/app/Common/component/list-card/list-slider/list-slider.component';
import { SharedPaginationComponent } from 'src/app/Common/layout/shared-pagination/shared-pagination.component';
import { NoResultComponent } from 'src/app/Common/layout/no-result/no-result.component';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { TabsComponent } from 'src/app/Common/layout/tabs/tabs.component';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { SearchListComponent } from 'src/app/Common/layout/search-list/search-list.component';
import { AllInputTypes } from 'src/app/Common/enums/all-input-types.enum';
import { PlacesService } from 'src/app/services/places.service';
import { SearchListSmComponent } from 'src/app/Common/layout/search-list-sm/search-list-sm.component';
import { RestaurantsService } from '../../services';
import { throwError } from 'rxjs';

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
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    SearchListSmComponent,
    // Directives
    LazyLoadSectionDirective,
    ListSliderComponent,
    SharedPaginationComponent,
    NoResultComponent,
    ListCardComponent,
    TabsComponent,
    BannerComponent,
    SearchListComponent
  ],
  templateUrl: './restaurants-list.component.html',
  styleUrls: ['./restaurants-list.component.scss']
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
  searchFields: any;

  // End Restaurants Categories Variables

  foodCategories: any = [];
  isLoadingFoodCategories: boolean = false;
  selectedFoodCategory: any;
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
  topRestaurantsPerPage: any = 12;
  topRestaurantsTotalCount: any;
  selectedCategories: any;
  defaultSelectedType: number;
  keyword: any;
  @ViewChild('paginatorRestaurant') paginatorRestaurant: Paginator | undefined;

  bestRestaurants: any = [];
  nearbyRestaurants: any = [];
  paginatedNearbyRestaurants: any = [];
  isLoadingNearbyRestaurants: boolean = false;
  isLoadingMoreRestaurants: boolean = false;
  page: any = 1;
  currentPage: any = 1;
  perPage: any = 4;
  nearbyRestaurantsRows: any = 6;
  nearbyRestaurantsTotalCount: any;
  categoryId: any;
  isChangePage: boolean = false;
  isUserLoggedin: boolean = false;
  cities: any = [];
  isLoadingCities: boolean;
  private localizationLanguageService = inject(LocalizationLanguageService);
  private platformId = inject(PLATFORM_ID);
  private restaurantsService = inject(RestaurantsService);
  private metadataService = inject(MetadataService);
  private activatedRoute = inject(ActivatedRoute);
  private messageService = inject(MessageService);
  private alertsService = inject(AlertsService);
  private dialogService = inject(DialogService);
  private publicService = inject(PublicService);
  private authService = inject(AuthService);
  private router = inject(Router);

  constructor(
  ) {
    this.localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.searchFields = [
      {
        type: AllInputTypes.Text,
        name: 'placeName',
        label: 'labels.restaurantName',
        placeholder: 'placeholder.enterRestaurantName',
        icon: 'assets/images-v2/pages/Home/quick-search/new-search-icon.webp',
        smIcon: 'assets/images/icons/placeName.svg',
        widthClass: 'col-3 px-3 input-search-result border-end',
        validation: []
      },
      {
        type: AllInputTypes.Select,
        name: 'region',
        label: 'labels.region',
        placeholder: 'placeholder.selectRegion',
        listValues: this?.regions,
        icon: 'assets/images-v2/pages/Home/quick-search/new-area-icon.webp',
        smIcon: 'assets/images/icons/region.svg',
        isLoading: true,
        widthClass: 'col-3 px-4 input-search-result border-end',
        validation: [Validators.required]
      },
      {
        type: AllInputTypes.Select,
        name: 'city',
        label: 'labels.city',
        placeholder: 'placeholder.selectCity',
        // listValues: this.citis,
        icon: 'assets/images-v2/pages/Home/quick-search/new-location-icon.webp',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: false,
        hint: 'placeholder.selectRegionFirst',
        widthClass: 'col-3 ps-4 input-search-result border-end',
        validation: []
      }
      ,
      {
        type: AllInputTypes.MultiSelect,
        name: 'type',
        label: 'restaurants.type',
        placeholder: 'placeholder.selectType',
        listValues: this.foodCategories,
        icon: 'assets/images-v2/pages/Home/quick-search/type.svg',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: true,
        widthClass: 'col-3 ps-4 input-search-result',
        validation: []
      }
    ];
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
    this.activatedRoute.queryParams.subscribe(params => {
      this.categoryId = params['categoryId'];
      this.getRestaurantCategories();
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
          this.category?.nativeElement.scrollIntoView({ behavior: 'smooth' });
          if (isPlatformBrowser(this.platformId)) {
            const currentUrl = this.router.url;
            if (currentUrl.includes('/restaurants/list')) {
              this.router.navigate([], {
                queryParams: { category: item.id },
                queryParamsHandling: 'merge',
              });
            }
          }
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
    this.getRegions();

    this.getFoodCategories();
    this.getCommonRestaurants();
    navigator?.permissions?.query({ name: "geolocation" }).then((result) => {
      result?.addEventListener("change", () => {
      });
      if (result.state === "granted") {
        this.getLocation();
      }
      else if (result?.state === "prompt") {
        this.getTopRestaurants();
        this.latitude = 24.774265;
        this.longitude = 46.738586;
        this.getNearbyRestaurants();
      } else if (result.state === "denied") {
        this.getTopRestaurants();
        this.latitude = 24.774265;
        this.longitude = 46.738586;
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
    this.restaurantsService?.getCategories()?.pipe(
      tap((res: any) => this.handleCategoriesResponse(res)),
      catchError((err: any) => {
        this.handleCategoriesError(err);
        return throwError(() => err);
      })
    ).subscribe({});

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
            this.category?.nativeElement?.scrollIntoView({ behavior: 'smooth' });
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
  selectCategory(item?: any): void {
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
    this.restaurantsService?.getFoodCategories()?.pipe(
      tap((res: any) => this.handleFoodCategoriesResponse(res)),
      catchError((err: any) => {
        this.handleFoodCategoriesError(err);
        return throwError(() => err);
      })
    ).subscribe({});

  }
  private handleFoodCategoriesResponse(res: any): void {
    if (res?.code == 200) {
      this.foodCategories = res?.data;
      this.searchFields[3].listValues = this.foodCategories;
      this.searchFields[3].isLoading = false;
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
      region_id: this.region,
      city_id: this.city,
      categories: this.restaurantCategoriesIds,
      ...(this.selectedFoodCategory?.length ? { "food_categories[]": this.selectedFoodCategory } : {}),
      selectedScore: this.selectedScore,
      search: this.keyword,
      showNearest: this.showNearest,
    })?.pipe(
      tap((res: any) => {
        this.handleTopRestaurantsResponse(res);
      }),
      catchError((err: any) => {
        this.handleTopRestaurantsError(err);
        return throwError(() => err);
      })
    ).subscribe({});

  }
  getCommonRestaurants(loadingMore?: boolean, rate?: any): void {
    this.messageService?.clear();
    loadingMore ? this.isLoadingMoreRestaurants = true : this.isLoadingTopRestaurants = true;
    this.restaurantsService?.getRestaurants({
      page: 1,
      per_page: 5,
      selectedScore: 5,
    })?.pipe(
      tap((res: any) => {
        this.bestRestaurants = res.data.items;
      }),
      catchError((err: any) => {
        this.handleTopRestaurantsError(err);
        return throwError(() => err);
      })
    ).subscribe({});

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
  getCitiesByRegionId(region: any): void {
    this.isLoadingCities = true;
    this.isLoadingCities = true;
    this.searchFields[2].isLoading = true;
    const regionId = region?.value?.id;
    this.restaurantsService?.getCities(regionId)?.pipe(
      tap((res: any) => {
        this.handleCitiesByRegionIdResponse(res);
        this.searchFields[2].listValues = this.cities;
        this.searchFields[2].isLoading = false;
      }),
      catchError((err: any) => {
        this.handleCitiesByRegionIdError(err);
        return throwError(() => err);
      })
    ).subscribe({});

  }
  private handleCitiesByRegionIdResponse(res: any): void {
    if (res?.code == 200) {
      this.cities = res?.data;
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingCities = false;
  }
  private handleCitiesByRegionIdError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingCities = false;
  }
  onSelectedItemsChange(selectedItems: any[]): void {
    let arr: any[] = [];
    let arr2: any[] = [];
    selectedItems.forEach(item => {
      arr.push(item.id);
      if (item?.id === 0)
        arr = [];
    });

    this.restaurantCategoriesIds = arr;
    this.topRestaurantsPage = 1;
    // this.changePageActiveNumber(this.storesPageNumber);
    this.getTopRestaurants();
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
  changePage(direction: number) {
    if (direction === -1 && this.topRestaurantsPage > 1) {
      this.topRestaurantsPage--;
    } else if (direction === 1 && this.topRestaurantsPage < Math.ceil(this.topRestaurantsTotalCount / this.topRestaurantsPerPage)) {
      this.topRestaurantsPage++;

    }
    this.isChangePage ? '' : this.getTopRestaurants();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  changeNearlyPage(direction: number) {
    if (direction === -1 && this.page > 1) {
      this.page--;
    } else if (direction === 1 && this.page < Math.ceil(this.nearbyRestaurantsTotalCount / this.perPage)) {
      this.page++;

    }
    this.isChangePage ? '' : this.getNearbyRestaurants();
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

  search(event?: any): void {
    console.log(event?.value?.type)
    if (event?.valid) {
      this.keyword = event?.value?.placeName;
      this.region = event?.value?.region?.id;
      this.city = event?.value?.city?.id;
      this.selectedFoodCategory = event?.value?.type?.map(item => item.id);
      this.topRestaurantsPage = 1;
      this.getTopRestaurants(false);
      if (isPlatformBrowser(this.platformId)) {
        window.scrollTo({ top: 300, behavior: 'smooth' });
      }
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterPlaceNameOrRegionOrCategory'), 'search')
      this.publicService?.validateAllFormFields(event);

    }
  }

  searchService(event: any): void {
    this.keyword = event;
    this.isLoadingSearch = true;
    this.onChangeFilter();
  }
  clearSearch(event?: any) {
    this.messageService?.clear();
    if (this.region != null) {
      this.keyword = event?.value?.placeName;
      this.region = event?.value?.region?.id;
      this.city = event?.value?.city?.id;
      this.topRestaurantsPage = 1;
      this.getTopRestaurants(false);
      return;
    }
    if (event?.value?.type === null) {
      this.selectedFoodCategory = null;
    }
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.keyword = null;
    this.isLoadingSearch = true;
    this.onChangeFilter();
  }
  getNearbyRestaurants(loadingMore?: boolean): void {
    loadingMore ? this.isLoadingMoreRestaurants = true : this.isLoadingNearbyRestaurants = true;
    this.restaurantsService?.getRestaurants({ page: this.page, per_page: this.perPage, showNearest: true })?.pipe(
      tap((res: any) => this.handleNearbyRestaurantsResponse(res)),
      catchError((err: any) => {
        this.handleNearbyRestaurantsError(err);
        return throwError(() => err);
      })
    ).subscribe({});

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
    this.selectedFoodCategory = null;
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
  handleViewAll() {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  regions: any = [];
  getRegions(): void {
    this.isLoadingRegions = true;
    this.restaurantsService?.getRegions()?.pipe(
      tap((res: any) => this.handleRegionsResponse(res)),
      catchError((err: any) => {
        this.handleRegionsError(err);
        return throwError(() => err);
      })
    ).subscribe({});

  }
  isLoadingRegions: any
  private handleRegionsResponse(res: any): void {
    if (res?.code == 200) {
      this.regions = res?.data;
      this.isLoadingRegions = false;
      this.searchFields[1].listValues = this.regions;
      this.searchFields[1].isLoading = false;
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingRegions = false;
  }
  private handleRegionsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingRegions = false;
  }
  onFieldChanged(event: any) {
    if (event.fieldName === 'region') {
      this.getCitiesByRegionId(event)
    }
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
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
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
