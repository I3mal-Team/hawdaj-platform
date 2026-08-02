// Modules
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
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
import { storesConfig } from '../../store/storesStaticDataConfigs';
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
import { StoresService } from 'src/app/services/stores.service';
import { TabViewModule } from 'primeng/tabview';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

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
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    FooterComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    LazyLoadDirective,
    // Pipes
    StripHtmlPipe
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
  // End Stores Categories Variables

  // Start Popular Stores List Variables
  storesList: any = [];
  storesListTotalCount: number = 0;
  isLoadingStores: boolean = false;
  isLoadingMoreStores: boolean = false;
  storesPageNumber: any = 1;
  storesPerPageCount: any = 4;
  storesListKeyword: any = null;
  storesListOptions: any = storesConfig;
  isLastStore: boolean = false;
  isSearch: boolean = false;
  // End Popular Stores List Variables

  // Start Popular Stores List Variables
  topStoresList: any = [];
  topStoresListTotalCount: number = 0;
  isLoadingTopStores: boolean = false;
  isLoadingMoreTopStores: boolean = false;
  topStoresPageNumber: any = 1;
  topStoresPerPageCount: any = 4;
  isLastTopStore: boolean = false;
  // End Popular Stores List Variables

  @ViewChild('scrollTarget', { static: true }) scrollTarget: any;
  isChangePage: boolean = false;

  storesStats: any;
  isLoadingStoresStats: boolean = false;

  mapLocations: any = [];

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

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private messageService: MessageService,
    private activatedRoute: ActivatedRoute,
    private dialogService: DialogService,
    private storesService: StoresService,
    private alertsService: AlertsService,
    private placesService: PlacesService,
    private publicService: PublicService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storesList');
    }
    this.activatedRoute.params.subscribe(params => {
      this.categoryId = params['category'];
    });
    this.publicService?.storeCategory?.subscribe((res: any) => {
      this.storesCategories?.forEach((el: any) => {
        el.isSelected = false;
      });
      this.storesCategoriesIds = [];
      this.selectedStoresCategories = [];
      this.storesCategories?.forEach((item: any) => {
        if (item?.id == res?.id) {
          this.selectCategory(item);
          if (isPlatformBrowser(this.platformId)) {
            window.scrollTo(this.scrollTarget.yPosition);
          }
        }
      });
    });

    this.loadData(); // Universal-compatible data loading
  }

  loadData(): void {
    this.getPopularStoresList();
    this.getStoresCategories();
    this.getStoresStats();
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
  getStoresStats(): void {
    this.isLoadingStoresStats = true;
    this.storesService?.getStoresStats()?.subscribe(
      (res: any) => this.handleStoresStatsResponse(res),
      (err: any) => this.handleStoresStatsError(err)
    );
  }
  private handleStoresStatsResponse(res: any): void {
    if (res?.code == 200) {
      this.storesStats = res?.data?.stores;
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingStoresStats = false;
  }
  private handleStoresStatsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingStoresStats = false;
  }

  getStoresCategories(): void {
    this.isLoadingStoresCategories = true;
    this.storesService?.getStoresCategories()?.subscribe(
      (res: any) => this.handleStoresCategoriesResponse(res),
      (err: any) => this.handleStoresCategoriesError(err)
    );
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
              window.scrollTo(this.scrollTarget.yPosition);
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

  getRegions(): void {
    this.isLoadingRegions = true;
    this.placesService?.getRegions()?.subscribe(
      (res: any) => this.handleRegionsResponse(res),
      (err: any) => this.handleRegionsError(err)
    );
  }
  private handleRegionsResponse(res: any): void {
    if (res?.code == 200) {
      this.regions = res?.data;
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
    const regionId = region?.value?.id;
    this.placesService?.getCities(regionId)?.subscribe(
      (res: any) => {
        this.handleCitiesByRegionIdResponse(res);
      },
      (err: any) => {
        this.handleCitiesByRegionIdError(err);
      }
    );
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
      { id: 1, name: 'stores.online', value: 'online' },
      { id: 1, name: 'stores.offline', value: 'offline' }
    ];
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

  search(): void {
    this.messageService?.clear();
    this.isSubmitted = true;
    if (this.searchForm?.valid) {
      this.displaySearch = false;
      let formInfo: any = this.searchForm?.value;
      this.storeName = formInfo?.storeName;
      this.regionId = formInfo?.region?.id;
      this.cityId = formInfo?.city?.id;
      this.storeType = formInfo?.storeType?.value;
      this.storesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.changePageActiveNumber(this.storesPageNumber);
      this.isChangePage ? '' : this.getPopularStoresList(false);
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterStoreNameOrRegion'), 'search')
      this.publicService?.validateAllFormFields(this.searchForm);
    }
  }

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
      this.storesService?.getFeatureStores(this.storesPageNumber, this.storesPerPageCount, this.storeName, this.regionId, this.cityId, this.storesCategoriesIds?.length > 0 ? this.storesCategoriesIds : null, null, null, this.storeType ? this.storeType == 'online' ? 1 : 0 : null)?.subscribe(
        (res: any) => {
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
            this.isLoadingStores = false;
            this.isLoadingMoreStores = false;
            this.storesListTotalCount = res?.data?.total ? res?.data?.total : 0;
            this.isChangePage = false;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.isLoadingStores = false;
            this.isLoadingMoreStores = false;
            this.isChangePage = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingStores = false;
          this.isLoadingMoreStores = false;
          this.isChangePage = false;
        }
      );
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

      this.storesService?.getTopStores(this.topStoresPageNumber, this.topStoresPerPageCount)?.subscribe(
        (res: any) => {
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
            this.topStoresList = data ? data : [];
            if (this.topStoresList?.length > 0) {
              this.topStoresList?.forEach((element: any) => {
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
              this.topStoresList?.forEach((el: any) => {
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

            this.isLoadingTopStores = false;
            this.isLoadingMoreTopStores = false;
            this.topStoresListTotalCount = res?.data?.total ? res?.data?.total : 0;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.isLoadingTopStores = false;
            this.isLoadingMoreTopStores = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingTopStores = false;
          this.isLoadingMoreTopStores = false;
        }
      );
      this.cdr.detectChanges();
    }
  }
  onPageChangeTopStores(event: any) {
    this.storesPageNumber = event.page + 1;
    this.isLoadingStores ? '' : this.getPopularStoresList();
  }
  // End Top Stores List Functions

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
