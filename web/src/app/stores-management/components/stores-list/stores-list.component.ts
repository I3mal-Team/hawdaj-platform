// Modules
import { ChangeDetectorRef, Component, inject, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { environment } from 'src/environments/environment';
import { Subscription } from 'rxjs/internal/Subscription';
import { DialogService } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { CarouselModule } from 'primeng/carousel';
import { DropdownModule } from 'primeng/dropdown';
import { SidebarModule } from 'primeng/sidebar';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { PlacesService } from 'src/app/services/places.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
// Components
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';
import { TabViewModule } from 'primeng/tabview';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
// import { storesConfig } from '../stores/store/storesStaticDataConfigs';
import { ListSliderComponent } from 'src/app/Common/component/list-card/list-slider/list-slider.component';
import { SharedPaginationComponent } from 'src/app/Common/layout/shared-pagination/shared-pagination.component';
import { NoResultComponent } from 'src/app/Common/layout/no-result/no-result.component';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { TabsComponent } from 'src/app/Common/layout/tabs/tabs.component';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { SearchListComponent } from 'src/app/Common/layout/search-list/search-list.component';
import { SearchListSmComponent } from 'src/app/Common/layout/search-list-sm/search-list-sm.component';
import { AllInputTypes } from 'src/app/Common/enums/all-input-types.enum';
import { StartTripComponent } from '../start-trip/start-trip.component';
import { StoresService } from '../../services';
import { finalize } from 'rxjs';
import { AccordingSliderComponent } from 'src/app/shared/components/according-slider/according-slider.component';

@Component({
  selector: 'app-stores-list',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    DropdownModule,
    CarouselModule,
    SidebarModule,
    TabViewModule,
    CommonModule,
    RouterModule,
    RatingModule,
    FormsModule,
    ToastModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    AccordingSliderComponent,
    ListSliderComponent,
    SharedPaginationComponent,
    NoResultComponent,
    ListCardComponent,
    TabsComponent,
    BannerComponent,
    SearchListComponent,
    SearchListSmComponent,
    StartTripComponent,
    // Directives
    LazyLoadSectionDirective,
    LazyLoadSectionDirective
  ],
  templateUrl: './stores-list.component.html',
  styleUrls: ['./stores-list.component.scss']
})
export class StoresListComponent {
  private unsubscribe: Subscription[] = [];

  storesAllStoresSection = false;
  storesTopStoresSection = false;
  homeShowFooter = false;

  // Start Stores Categories Variables
  storesCategories: any = [];
  isLoadingStoresCategories: boolean = false;
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
  storesCategoriesIds: any = [];
  selectedStoresCategories: any = [];
  searchFields: any;

  // End Stores Categories Variables

  // Start Popular Stores List Variables
  storesList: any = [];
  storesListTotalCount: number = 0;
  isLoadingStores: boolean = false;
  isLoadingMoreStores: boolean = false;
  storesPageNumber: any = 1;
  storesPerPageCount: any = 12;
  storesListKeyword: any = null;
  // storesListOptions: any = storesConfig;
  isLastStore: boolean = false;
  isSearch: boolean = false;
  // End Popular Stores List Variables

  // Start Popular Stores List Variables
  topStoresList: any = [];
  topStoresListTotalCount: number = 0;
  isLoadingTopStores: boolean = false;
  isLoadingMoreTopStores: boolean = false;
  topStoresPageNumber: any = 1;
  topStoresPerPageCount: any = 5;
  isLastTopStore: boolean = false;
  // End Popular Stores List Variables

  @ViewChild('scrollTarget', { static: true }) scrollTarget: any;
  isChangePage: boolean = false;

  storesStats: any;
  isLoadingStoresStats: boolean = false;

  mapLocations: any = [];


  private localizationLanguageService = inject(LocalizationLanguageService);
  private platformId = inject(PLATFORM_ID);
  private metadataService = inject(MetadataService);
  private messageService = inject(MessageService);
  private activatedRoute = inject(ActivatedRoute);
  private dialogService = inject(DialogService);
  private storesService = inject(StoresService);
  private alertsService = inject(AlertsService);
  private placesService = inject(PlacesService);
  private publicService = inject(PublicService);
  private cdr = inject(ChangeDetectorRef);
  private fb = inject(FormBuilder);
  private router = inject(Router);

  searchForm: any = this.fb.group(
    {
      storeName: ['', {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      region: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      city: [null, {
        validators: [],
        updateOn: 'change'
      }],
      storeType: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
    },
  );
  get formControls(): any {
    return this.searchForm?.controls;
  }

  regions: any = [];
  isLoadingRegions: boolean = false;

  cities: any = [];
  isLoadingCities: boolean = false;

  storesTypes: any = [];
  storeName: any = null;
  regionId: any = null;
  cityId: any = null;
  storeType: any = null;
  isSubmitted: boolean = false;
  isSubmittedSearch: boolean = false;
  displaySearch: boolean = false;
  rate: any = 2;

  categoryId: any;
  @ViewChild('paginatorStoresList') paginatorStoresList: Paginator | undefined;

  constructor() {
    this.localizationLanguageService.updatePathAccordingLang();
  }
  ngOnInit(): void {
    this.searchFields = [
      {
        type: AllInputTypes.Text,
        name: 'placeName',
        label: 'labels.storeName',
        placeholder: 'placeholder.enterStoreName',
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
        listValues: this.cities,
        icon: 'assets/images-v2/pages/Home/quick-search/new-location-icon.webp',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: false,
        hint: 'placeholder.selectRegionFirst',
        widthClass: 'col-3 ps-4 input-search-result border-end',
        validation: []
      }
      ,
      {
        type: AllInputTypes.Select,
        name: 'type',
        label: 'stores.type',
        placeholder: 'placeholder.selectType',
        listValues: this.storesTypes,
        icon: 'assets/images-v2/pages/Home/quick-search/type.svg',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: true,
        widthClass: 'col-3 ps-4 input-search-result',
        validation: []
      }
    ];
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storesList');
    }
    this.publicService?.storeCategory.pipe(
      finalize(() => {
      })
    ).subscribe({
      next: (res: any) => {
        this.storesCategories?.forEach((el: any) => {
          el.isSelected = false;
        });

        this.selectedStoresCategories = [];

        this.storesCategories?.forEach((item: any) => {
          if (item?.id === res?.id) {
            this.selectCategory(item);

            if (isPlatformBrowser(this.platformId)) {
              window.scrollTo(this.scrollTarget?.yPosition);
            }
          }
        });
      },
      error: (err: any) => {
        console.error('Error fetching store category:', err);
      }
    });
    this.activatedRoute.queryParams.subscribe(params => {
      this.categoryId = params['category'];
      this.getStoresCategories();
    });
    this.loadData(); // Universal-compatible data loading
  }

  loadData(): void {
    this.getPopularStoresList();
    this.getStoresCategories();
    // this.getStoresStats();
    this.getStoresType();
    this.getTopStores();
    this.getRegions();
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | المتاجر`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | المتاجر` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stores/list` },
      { property: 'og:title', content: `هودج | المتاجر` },
    ]);
  }
  removeQueryParams() {
    this.router?.navigate(['/stores/list']);
  }
  // getStoresStats(): void {
  //   this.isLoadingStoresStats = true;
  //   this.storesService?.getStoresStats()?.subscribe(
  //     (res: any) => this.handleStoresStatsResponse(res),
  //     (err: any) => this.handleStoresStatsError(err)
  //   );
  // }
  // private handleStoresStatsResponse(res: any): void {
  //   if (res?.code == 200) {
  //     this.storesStats = res?.data?.stores;
  //   } else {
  //     res?.message ? this.alertsService?.openToast('error', res?.message) : '';
  //   }
  //   this.isLoadingStoresStats = false;
  // }
  // private handleStoresStatsError(err: any): void {
  //   err ? this.alertsService?.openToast('error', err) : '';
  //   this.isLoadingStoresStats = false;
  // }

  getStoresCategories(): void {
    this.isLoadingStoresCategories = true;
    this.storesService?.getCategories()?.subscribe({
      next: (res: any) => this.handleStoresCategoriesResponse(res),
      error: (err: any) => this.handleStoresCategoriesError(err),
    });
  }
  private handleStoresCategoriesResponse(res: any): void {
    if (res?.code == 200) {
      res?.data?.forEach((item: any) => {
        item['isSelected'] = false;
      });
      this.storesCategories = res?.data;
      if (this.categoryId) {
        this.storesCategories?.forEach((el: any) => {
          el.isSelected = false;
        });
        this.storesCategoriesIds = [];
        this.selectedStoresCategories = [];
        this.storesCategories?.forEach((item: any) => {
          if (item?.id == parseInt(this.categoryId)) {
            this.selectCategory(item);
            if (isPlatformBrowser(this.platformId)) {
              window.scrollTo(this.scrollTarget?.yPosition);
            }
          }
        });
      }
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingStoresCategories = false;
  }
  private handleStoresCategoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingStoresCategories = false;
  }
  selectCategory(item: any): void {
    if (item?.id) {
      let oldSelectedIds = this.storesCategoriesIds;
      this.storesCategories?.forEach((el: any) => {
        if (el?.id == item?.id) {
          el.isSelected = !el.isSelected;
        }
      });
      let arr: any = [];
      let arr2: any = [];
      this.storesCategories?.forEach((el: any) => {
        if (el?.isSelected) {
          arr?.push([el?.id]);
          arr2?.push(el);
        }
      });
      this.storesCategoriesIds = arr;
      this.selectedStoresCategories = arr2;
      if (oldSelectedIds !== this.storesCategoriesIds) {
        this.storesPageNumber = 1;
        this.changePageActiveNumber(this.storesPageNumber);
        this.isChangePage ? '' : this.getPopularStoresList(false);
      }
    }
  }
  selectedCategories: any;
  onSelectedItemsChange(selectedItems: any[]): void {
    let arr: any[] = [];
    let arr2: any[] = [];
    selectedItems.forEach(item => {
      arr.push(item.id);
    });

    this.storesCategories.forEach(el => {
      if (el.isSelected) {
        arr.push(el.id);
        arr2.push(el);
      }
    });

    this.selectedCategories = arr2;
    this.storesCategoriesIds = arr;

    this.searchForm?.patchValue({
      category: arr2
    });

    this.storesPageNumber = 1;
    this.changePageActiveNumber(this.storesPageNumber);
    this.getPopularStoresList();
  }

  resetCategories(): void {
    this.storesCategories?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.storesCategoriesIds = [];
    this.selectedStoresCategories = [];
    this.storesPageNumber = 1;
    this.changePageActiveNumber(this.storesPageNumber);
    this.isChangePage ? '' : this.getPopularStoresList(false);
    // this.removeQueryParams();
  }

  handleViewAll() {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  getRegions(): void {
    this.isLoadingRegions = true;
    this.storesService?.getRegions().pipe(
      finalize(() => {
      })
    ).subscribe({
      next: (res: any) => this.handleRegionsResponse(res),
      error: (err: any) => this.handleRegionsError(err)
    });

  }
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
  getCitiesByRegionId(region: any): void {
    this.formControls.city.reset();
    this.isLoadingCities = true;
    this.isLoadingCities = true;
    this.searchFields[2].isLoading = true;
    const regionId = region?.value?.id;
    this.storesService?.getCities(regionId).pipe(
      finalize(() => {
        this.searchFields[2].isLoading = false;
      })
    ).subscribe({
      next: (res: any) => {
        this.handleCitiesByRegionIdResponse(res);
        this.searchFields[2].listValues = this.cities;
      },
      error: (err: any) => {
        this.handleCitiesByRegionIdError(err);
      }
    });
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

  getStoresType(): void {
    this.storesTypes = [
      { id: 1, name: this.publicService?.translateTextFromJson('stores.online'), value: 'online' },
      { id: 1, name: this.publicService?.translateTextFromJson('stores.offline'), value: 'offline' }
    ];
    this.searchFields[3].listValues = this.storesTypes;
    this.searchFields[3].isLoading = false;
  }

  onChangeControl(type: string): void {
    const validStoreName = this.formControls?.storeName?.valid;
    const validRegion = this.formControls?.region?.valid;
    const validStoreType = this.formControls?.storeType?.valid;
    switch (type) {
      case 'storeName':
        this.handleStoreName(validStoreName);
        break;
      case 'region':
        this.handleRegion(validRegion);
        break;
      case 'storeType':
        this.handleStoreType(validStoreType);
        break;
      default:
        break;
    }
  }
  private handleStoreName(validStoreName: boolean): void {
    if (validStoreName) {
      this.updateValidators(['region', 'storeType'], 'remove');
    } else {
      this.updateValidators(['region', 'storeType'], 'add');
    }
  }
  private handleRegion(validRegion: boolean): void {
    if (validRegion) {
      this.updateValidators(['storeName', 'storeType'], 'remove');
    } else {
      this.updateValidators(['storeName', 'storeType'], 'add');
    }
  }
  private handleStoreType(validStoreType: boolean): void {
    if (validStoreType) {
      this.updateValidators(['storeName', 'region'], 'remove');
    } else {
      this.updateValidators(['storeName', 'region'], 'add');
    }
  }
  changePage(direction: number) {
    if (direction === -1 && this.storesPageNumber > 1) {
      this.storesPageNumber--;
    } else if (direction === 1 && this.storesPageNumber < Math.ceil(this.storesListTotalCount / this.storesPerPageCount)) {
      this.storesPageNumber++;

    }
    this.isChangePage ? '' : this.getPopularStoresList();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  private updateValidators(fields: string[], action: 'add' | 'remove'): void {
    fields.forEach(field => {
      if (action === 'add') {
        this.publicService?.addValidators(this.searchForm, [field]);
      } else {
        this.publicService?.removeValidators(this.searchForm, [field]);
      }
    });
  }
  clear(type: any): void {
    type == 'name' ? this.storeName = null : null;
    type == 'region' ? this.regionId = null : null;
    type == 'city' ? this.cityId = null : null;
    type == 'type' ? this.storeType = null : null;
    this.storesPageNumber = 1;
    this.changePageActiveNumber(this.storesPageNumber);
    this.isChangePage ? '' : this.getPopularStoresList();
  }
  clearField(field: string): void {
    if (field === 'region') {
      this.searchForm.controls['region'].setValue(null);
      this.searchForm.controls['city'].setValue(null);
    }
    if (field === 'city') {
      this.searchForm.controls['city'].setValue(null);
    }

  }

  // search(): void {
  //   this.messageService?.clear();
  //   this.isSubmitted = true;
  //   if (this.searchForm?.valid) {
  //     this.displaySearch = false;
  //     let formInfo: any = this.searchForm?.value;
  //     this.storeName = formInfo?.storeName;
  //     this.regionId = formInfo?.region?.id;
  //     this.cityId = formInfo?.city?.id;
  //     this.storeType = formInfo?.storeType?.value;
  //     this.storesPageNumber = 1;
  //     this.isSubmittedSearch = true;
  //     this.changePageActiveNumber(this.storesPageNumber);
  //     this.isChangePage ? '' : this.getPopularStoresList(false);
  //   } else {
  //     this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterStoreNameOrRegion'), 'search')
  //     this.publicService?.validateAllFormFields(this.searchForm);
  //   }
  // }

  // Start Popular Stores List Functions
  getPopularStoresList(hideFullLoading?: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isSearch = false;
      if (hideFullLoading) {
        this.isLoadingStores = false;
        this.isLoadingMoreStores = true;
      } else {
        this.isLoadingStores = true;
        this.isLoadingMoreStores = false;
      }
      this.storesService?.getStores({
        page: this.storesPageNumber,
        per_page: this.storesPerPageCount,
        search: this.storeName,
        region_id: this.regionId,
        city_id: this.cityId,
        category_id: this.storesCategoriesIds?.length > 0 ? this.storesCategoriesIds : null,
        is_online: this.storeType ? (this.storeType === 'online' ? 1 : 0) : null
      }).pipe(
        finalize(() => {
          this.isLoadingStores = false;
          this.isLoadingMoreStores = false;
          this.isChangePage = false;
        })
      ).subscribe({
        next: (res: any) => {
          if (res?.code == 200) {
            res?.data?.items?.forEach((element: any) => {
              if (element?.lat && element?.long && element?.address_type == 'map') {
                element['address'] = this.publicService.createGoogleMapsLink(element?.lat, element?.long);
              }
              if (element?.region?.name && element?.city?.name) {
                element['address_name'] = element?.region?.name + ', ' + element?.city?.name;
              } else if (element?.region?.name) {
                element['address_name'] = element?.region?.name;
              } else if (element?.city?.name) {
                element['address_name'] = element?.city?.name;
              }
            });

            let data = res?.data?.items;
            this.storesList = data ? data : [];

            if (this.storeName || this.regionId || this.cityId || this.storeType) {
              this.isSearch = true;
            }

            if (this.storesList?.length > 0) {
              this.storesList?.forEach((element: any) => {
                if (element?.lat && element?.long && element?.address_type == 'map') {
                  element['address'] = this.publicService.createGoogleMapsLink(element?.lat, element?.long);
                }
                if (element?.region?.name && element?.city?.name) {
                  element['address_name'] = element?.region?.name + ', ' + element?.city?.name;
                } else if (element?.region?.name) {
                  element['address_name'] = element?.region?.name;
                } else if (element?.city?.name) {
                  element['address_name'] = element?.city?.name;
                }
              });

              this.storesList?.forEach((el: any) => {
                this.mapLocations?.push({
                  lat: el?.lat,
                  lng: el?.long,
                  name: el?.title,
                  image: el?.image,
                  address_name: el?.address_name,
                  review: el?.review,
                  rate: el?.rate ? el?.rate : 0
                });
              });
            }

            this.storesListTotalCount = res?.data?.total ? res?.data?.total : 0;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
          }
        },
        error: (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
        }
      });

      this.cdr.detectChanges();
    }
  }
  onPageChangePopularStores(event: any) {
    this.storesPageNumber = event.page + 1;
    this.isLoadingStores ? '' : this.getPopularStoresList();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 600, behavior: 'smooth' });
    }
  }
  onPageChangeTopMarkets(event: any) {
    this.topStoresPageNumber = event.page + 1;
    this.isLoadingStores ? '' : this.getTopStores();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 1220, behavior: 'smooth' });
    }
  }
  changePageActiveNumber(number: number): void {
    this.isChangePage = true;
    this.paginatorStoresList?.changePage(number - 1);
  }
  // End Popular Stores List Functions

  // Start Top Stores List Functions
  getTopStores(hideFullLoading?: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      if (hideFullLoading) {
        this.isLoadingTopStores = false;
        this.isLoadingMoreTopStores = true;
      } else {
        this.isLoadingTopStores = true;
        this.isLoadingMoreTopStores = false;
      }

      this.storesService?.getTopStores({
        page: this.topStoresPageNumber,
        perPage: this.topStoresPerPageCount,
        top_stores: true
      }).pipe(
        finalize(() => {
          this.isLoadingTopStores = false;
          this.isLoadingMoreTopStores = false;
        })
      ).subscribe({
        next: (res: any) => {
          if (res?.code == 200) {
            res?.data?.items?.forEach((element: any) => {
              if (element?.lat && element?.long && element?.address_type == 'map') {
                element['address'] = this.publicService.createGoogleMapsLink(element?.lat, element?.long);
              }
              element['address_name'] = [
                element?.region?.name,
                element?.city?.name
              ].filter(Boolean).join(', ');
            });

            this.topStoresList = res?.data?.items ?? [];

            if (this.topStoresList.length > 0) {
              this.topStoresList.forEach((el: any) => {
                this.mapLocations?.push({
                  lat: el?.lat,
                  lng: el?.long,
                  name: el?.title,
                  image: el?.image,
                  address_name: el?.address_name,
                  review: el?.review,
                  rate: el?.rate || 0
                });
              });
            }

            this.topStoresListTotalCount = res?.data?.total || 0;
          } else {
            res?.message && this.alertsService?.openToast('error', res?.message);
          }
        },
        error: (err: any) => {
          err && this.alertsService?.openToast('error', err);
        }
      });

      this.cdr.detectChanges();
    }
  }
  onPageChangeTopStores(event: any) {
    this.storesPageNumber = event.page + 1;
    this.isLoadingStores ? '' : this.getPopularStoresList();
  }

  onFieldChanged(event: any) {
    if (event.fieldName === 'region') {
      console.log('Selected value:', event.value, event?.value?.id);
      this.getCitiesByRegionId(event)
    }
  }
  clearSearch(event?: any) {
    this.messageService?.clear();
    this.isSubmitted = true;
    this.displaySearch = false;
    if (this.regionId != null) {
      this.storeName = event?.value?.placeName;
      this.regionId = event?.value?.region?.id;
      this.cityId = event?.value?.city?.id;
      this.storesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.getPopularStoresList(false);
    }
  }

  // End Top Stores List Functions

  search(event?: any): void {
    this.messageService?.clear();
    this.isSubmitted = true;
    if (event?.valid) {
      this.displaySearch = false;
      this.storeName = event?.value?.placeName;
      this.regionId = event?.value?.region?.id;
      this.cityId = event?.value?.city?.id;
      this.storeType = event?.value?.type?.value;
      this.storesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.getPopularStoresList(false);
      if (isPlatformBrowser(this.platformId)) {
        window.scrollTo({ top: 300, behavior: 'smooth' });
      }
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterPlaceNameOrRegionOrCategory'), 'search')
      this.publicService?.validateAllFormFields(event);

    }

  }

  openMap(item: any): void {
    let data: any = [];
    if (item?.region?.name && item?.city?.name) {
      item['address_name'] = item?.region?.name + ', ' + item?.city?.name;
    } else if (item?.region?.name) {
      item['address_name'] = item?.region?.name;
    } else if (item?.city?.name) {
      item['address_name'] = item?.city?.name;
    }
    data?.push({
      lat: item?.lat,
      lng: item?.long,
      name: item?.title,
      image: item?.image,
      address_name: item?.location_name,
      review: item?.review,
      rate: item?.rate ? item?.rate : 0
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

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
