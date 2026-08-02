// Modules
import { Component, ElementRef, Inject, PLATFORM_ID, ViewChild, ChangeDetectorRef, inject } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { catchError } from 'rxjs/internal/operators/catchError';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { Carousel, CarouselModule } from 'primeng/carousel';
import { finalize } from 'rxjs/internal/operators/finalize';
import { Subscription } from 'rxjs/internal/Subscription';
import { DialogService } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { tap } from 'rxjs/internal/operators/tap';
import { DropdownModule } from 'primeng/dropdown';
import { SidebarModule } from 'primeng/sidebar';
import { RatingModule } from 'primeng/rating';
import { MessageService } from 'primeng/api';
import { ToastModule } from 'primeng/toast';
// Services
import { staticPlacesDataAr, staticPlacesDataEn, staticPlacesDataRu, staticPlacesDataZh } from '../../store/staticData';
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PlacesService } from 'src/app/services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { environment } from 'src/environments/environment';
// Components
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { StartTripStepsComponent } from 'src/app/components/trips/start-trip-steps/start-trip-steps.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { placesConfig } from '../../store/places-configrations';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { TabViewModule } from 'primeng/tabview';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ListCardComponent } from "../../../../Common/component/list-card/list-card.component";

import { ListSliderComponent } from "../../../../Common/component/list-card/list-slider/list-slider.component";
import { Categories, Place, Region, RegionResponse } from './interfaces/places-list';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { TabsComponent } from 'src/app/Common/layout/tabs/tabs.component';
import { SharedPaginationComponent } from "../../../../Common/layout/shared-pagination/shared-pagination.component";
import { NoResultComponent } from 'src/app/Common/layout/no-result/no-result.component';
import { SearchListComponent } from 'src/app/Common/layout/search-list/search-list.component';
import { PlacesStartTripComponent } from './places-start-trip/places-start-trip.component';
import { SearchListSmComponent } from 'src/app/Common/layout/search-list-sm/search-list-sm.component';
import { AllInputTypes } from 'src/app/Common/enums/all-input-types.enum';

@Component({
  selector: 'app-places-list-v2',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    DropdownModule,
    CarouselModule,
    SidebarModule,
    CommonModule,
    RouterModule,
    RatingModule,
    FormsModule,
    ToastModule,
    TabViewModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    LazyLoadSectionDirective,
    ListCardComponent,
    ListSliderComponent,
    BannerComponent,
    TabsComponent,
    SharedPaginationComponent,
    NoResultComponent,
    SearchListComponent,
    PlacesStartTripComponent,
    SearchListSmComponent
  ],
  templateUrl: './places-list-v2.component.html',
  styleUrls: ['./places-list-v2.component.scss']
})
export class PlacesListV2Component {
  private subscriptions: Subscription[] = [];
  currentLanguage: any;

  heroSliderData: any = [];
  isLoadingSliderPlaces: boolean = false;

  displaySearch: boolean = false;

  categories: any = [];
  categoriesIds: any = null;
  isLoadingCategories: boolean = false;
  selectedCategories: any = [];
  searchSection: boolean = false;
  categoriesSection: boolean = false;
  plannerSection1: boolean = false;

  plannerSection2: boolean = false;
  tripStepsSection: boolean = false;
  homeShowFooter = false;
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
  @ViewChild('carousel') carousel!: Carousel;

  regions: any = [];
  isLoadingRegions: boolean = false;

  cities: any = [];
  isLoadingCities: boolean = false;

  subCategories: any = [];
  isLoadingSubCategories: boolean = false;

  pricesList: any = [];
  isLoadingPrices: boolean = false;
  mapLocations: any = [];
  @ViewChild('categories') categoriesScroll!: ElementRef;

  @ViewChild('scrollTarget', { static: true }) scrollTarget: any;
  private localizationLanguageService = inject(LocalizationLanguageService);
  private platformId = inject(PLATFORM_ID);
  private metadataService = inject(MetadataService);
  private messageService = inject(MessageService);
  private activatedRoute = inject(ActivatedRoute);
  private placesService = inject(PlacesService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private dialogService = inject(DialogService);
  private cdr = inject(ChangeDetectorRef);
  private fb = inject(FormBuilder);
  private router = inject(Router);

  searchForm: any = this.fb.group(
    {
      placeName: ['', {
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
      category: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      price: [null, {
        validators: [],
        updateOn: 'change'
      }]
    },
  );
  get formControls(): any {
    return this.searchForm?.controls;
  }
  isSubmitted: boolean = false;

  placesList: any = [];
  placesListTotalCount: number = 0;
  placesPageCount: any = 0;
  isLoadingPlaces: boolean = false;
  isLoadingMorePlaces: boolean = false;
  placesPageNumber: any = 1;
  placesPerPageCount: any = 12;
  placesListKeyword: any = null;
  placesListOptions = placesConfig?.placesListOptions;
  placesListOptionsIndex: any = 0;
  isLastPlace: boolean = false;
  currentPage: any = 1;
  paginatedPlacesList: any = [];
  @ViewChild('paginatorPlacesList') paginatorPlacesList: Paginator | undefined;

  visitedPlacesList: any = [];
  visitedPlacesListTotalCount: number = 0;
  visitedPlacesPageCount: any = 0;
  isLoadingVisitedPlaces: boolean = false;
  isLoadingMoreVisitedPlaces: boolean = false;
  visitedPlacesPageNumber: any = 1;
  visitedPlacesPerPageCount: any = 12;
  visitedPlacesListKeyword: any = null;
  visitedPlacesListOptions = placesConfig?.placesListOptions;
  visitedPlacesListOptionsIndex: any = 0;
  isLastVisitedPlaces: boolean = false;
  currentPageVisitedPlaces: any = 1;
  paginatedVisitedPlacesList: any = [];

  placeNameFocus: boolean = false;
  focusRegion: boolean = false;
  focusCity: boolean = false;
  focusCategory: boolean = false;
  focusSubCategory: boolean = false;
  focusPrice: boolean = false;

  placeName: any = null;
  regionId: any = null;
  cityId: any = null;
  categoryId: any = null;
  subCategoryId: any = null;
  priceId: any = null;
  isSearch: boolean = false;
  isSubmittedSearch: boolean = false;

  tripSteps: any = [];
  isLoadingTripSteps: boolean = false;
  category: any;
  isChangePage: boolean = true;

  searchFields: any;
  formValues: any;

  isLoadingFavourite: boolean = false;
  constructor() {
    this.localizationLanguageService.updatePathAccordingLang();
  }
  ngOnInit(): void {
    this.searchFields = [
      {
        type: AllInputTypes.Text,
        name: 'placeName',
        label: 'labels.placeName',
        placeholder: 'placeholder.placeName',
        icon: 'assets/images-v2/pages/Home/quick-search/new-search-icon.webp',
        smIcon: 'assets/images/icons/placeName.svg',
        widthClass: 'col-4 px-3 input-search-result border-end',
        validation: []
      },
      {
        type: AllInputTypes.Select,
        name: 'region',
        label: 'labels.region',
        placeholder: 'placeholder.selectRegion',
        listValues: this.regions,
        icon: 'assets/images-v2/pages/Home/quick-search/new-area-icon.webp',
        smIcon: 'assets/images/icons/region.svg',
        isLoading: true,
        widthClass: 'col-4 px-4 input-search-result border-end',
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
        widthClass: 'col-4 ps-4 input-search-result',
        validation: []
      }
    ];
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('placesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('placesList');

    }
    this.activatedRoute.params.subscribe(params => {
      this.category = params['category'];
    });
    this.publicService?.placeCategory?.subscribe((res: any) => {
      this.categories?.forEach((el: any) => {
        el.isSelected = false;
      });
      this.categoriesIds = [];
      this.selectedCategories = [];
      this.categories?.forEach((item: any) => {
        if (item?.id == res?.id) {
          this.getCategories();
          this.selectCategory(item);
          this.categoriesScroll?.nativeElement.scrollIntoView({ behavior: 'smooth' });
        }
      });
    });
    this.loadData();
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | الأماكن`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | الأماكن` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/places` },
      { property: 'og:title', content: `هودج | الأماكن` },
    ]);
  }
  loadData(): void {
    this.getCategories();
    this.getPlacesList();
    this.getRegions();
    this.getVisitedPlacesList();

    // this.getPrices();

    this.searchForm?.get('placeName')?.valueChanges.subscribe((res: any) => {
      if (res) {
        this.messageService.clear('search');
      }
    });
  }

  getRegions(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingRegions = true;
      this.placesService?.getRegions()?.subscribe(
        (res: RegionResponse) => {
          if (res?.code == 200) {
            this.regions = res?.data;
            this.isLoadingRegions = false;
            this.searchFields[1].listValues = this.regions;
            this.searchFields[1].isLoading = false;

          } else {
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
            this.isLoadingRegions = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingRegions = false;
        }
      );
      this.cdr.detectChanges();
    }
  }
  clearRegion(event: Event): void {
    event.stopPropagation();
    this.searchForm.controls['region'].setValue(null);
    this.searchForm.controls['city'].setValue(null);
    this.regionId = null;
    this.cityId = null;
    this.getPlacesList();
  }
  getCitiesByRegionId(region: any): void {
    this.formControls.city.reset();
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingCities = true;
      this.searchFields[2].isLoading = true;

      this.placesService?.getCities(region?.value?.id)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.cities = res?.data;
            this.isLoadingCities = false;
            this.searchFields[2].listValues = this.cities;
            this.searchFields[2].isLoading = false;

          } else {
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
            this.isLoadingCities = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err?.message) : '';
          this.isLoadingCities = false;
        }
      );
    }
  }
  clearCity(event: Event): void {
    event.stopPropagation();
    this.searchForm.controls['city'].setValue(null);
    this.cityId = null;
    this.search();
  }
  getCategories(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingCategories = true;
      this.placesService?.getCategories()?.subscribe(
        (res: Categories) => {
          if (res?.code == 200) {
            this.categories = res?.data;
            res?.data ? res?.data?.forEach((item: any) => {
              item['isSelected'] = false;
            }) : '';

            if (this.category) {
              this.categoriesIds = [];
              this.selectedCategories = [];
              this.categories?.forEach((item: any) => {
                if (item?.id == parseInt(this.category)) {
                  this.selectCategory(item);
                  this.searchForm?.patchValue({
                    category: item
                  });
                  // this.categoriesScroll.nativeElement.scrollIntoView({ behavior: 'smooth' });
                }
              });
            }
            this.isLoadingCategories = false;
          } else {
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
            this.isLoadingCategories = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingCategories = false;
        }
      );
    }
  }
  onSelectedItemsChange(selectedItems: any[]): void {
    let arr: any[] = [];
    let arr2: any[] = [];
    selectedItems.forEach(item => {
      arr.push(item.id);
    });

    this.categories.forEach(el => {
      if (el.isSelected) {
        arr.push(el.id);
        arr2.push(el);
      }
    });

    this.selectedCategories = arr2;
    this.categoriesIds = arr;

    this.searchForm?.patchValue({
      category: arr2
    });

    this.placesPageNumber = 1;
    this.changePageActiveNumber(this.placesPageNumber);

    this.getPlacesList();
  }

  selectCategory(item: any): void {
    this.categories?.forEach((el: any) => {
      if (el?.id == item?.id) {
        el.isSelected = !el.isSelected;
      }
    });
    let arr: any = [];
    let arr2: any = [];
    this.categories?.forEach((el: any) => {
      if (el?.isSelected) {
        arr?.push([el?.id]);
        arr2?.push(el);
      }
    });
    this.searchForm?.patchValue({
      category: arr2
    });
    this.categoriesIds = arr;
    this.selectedCategories = arr2;
    this.placesPageNumber = 1;
    this.changePageActiveNumber(this.placesPageNumber);
    this.getPlacesList();
  }

  resetCategories(): void {
    this.categories?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.searchForm?.patchValue({
      category: null
    });
    this.categoriesIds = null;
    this.selectedCategories = [];
    this.placesPageNumber = 1;
    this.changePageActiveNumber(this.placesPageNumber);
    this.getPlacesList();
    // this.router?.navigate(['/places']);
  }
  getVisitedPlacesList(hideFullLoading?: boolean, lastActiveIndex?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isSearch = false;
      if (hideFullLoading) {
        this.isLoadingVisitedPlaces = false;
        this.isLoadingMoreVisitedPlaces = true;
      } else {
        this.isLoadingVisitedPlaces = true;
        this.isLoadingMoreVisitedPlaces = false;
      }
      this.placesService?.getPlaces(this.visitedPlacesPageNumber, this.visitedPlacesPerPageCount, this.visitedPlacesListKeyword, this.placeName, this.regionId, this.cityId, this.categoryId, this.subCategoryId, this.priceId, true)?.subscribe(
        (res: Place) => {
          if (res?.code == 200) {
            this.mapLocations = [];
            this.visitedPlacesList = res?.data?.items ? res?.data?.items : [];
            if (this.visitedPlacesList?.length > 0) {
              this.visitedPlacesList?.forEach((element: any) => {
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
              this.visitedPlacesList?.forEach((el: any) => {
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

            this.isLoadingVisitedPlaces = false;
            this.isLoadingMoreVisitedPlaces = false;
            this.visitedPlacesListTotalCount = res?.data?.total ? res?.data?.total : 0;
          } else {
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
            this.isLoadingVisitedPlaces = false;
            this.isLoadingMoreVisitedPlaces = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingVisitedPlaces = false;
          this.isLoadingMoreVisitedPlaces = false;
        }
      );
      this.cdr.detectChanges();
    }
  }
  onPageChangeVisitedPlaces(event: any) {
    this.visitedPlacesPageNumber = event.page + 1;
    this.getVisitedPlacesList();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 2020, behavior: 'smooth' });
    }
  }

  search(event?: any): void {
    this.messageService?.clear();
    this.isSubmitted = true;
    if (event?.valid) {
      this.displaySearch = false;
      this.placeName = event?.value?.placeName;
      this.regionId = event?.value?.region?.id;
      this.cityId = event?.value?.city?.id;
      this.priceId = event?.value?.price?.id;
      this.placesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.getPlacesList(false);
      if (isPlatformBrowser(this.platformId)) {
        window.scrollTo({ top: 300, behavior: 'smooth' });
      }
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterPlaceNameOrRegionOrCategory'), 'search')
      this.publicService?.validateAllFormFields(event);

    }

  }
  clearSearch(event?: any) {
    this.messageService?.clear();
    this.isSubmitted = true;
    this.displaySearch = false;
    if (this.regionId != null) {
      this.placeName = event?.value?.placeName;
      this.regionId = event?.value?.region?.id;
      this.cityId = event?.value?.city?.id;
      this.priceId = event?.value?.price?.id;
      this.placesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.getPlacesList(false);
    }
  }

  getPrices(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingPrices = true;
      this.placesService?.getPrices()?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.pricesList = res?.data;
            this.isLoadingPrices = false;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.isLoadingPrices = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingPrices = false;
        }
      );
      this.cdr.detectChanges();
    }
  }
  onChangeControl(type?: any): void {
    this.messageService.clear('search');
    if (type == 'placeName') {
      if (this.formControls?.placeName?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['region']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
      } else {
        this.publicService?.addValidators(this.searchForm, ['region']);
        this.publicService?.addValidators(this.searchForm, ['category']);
      }
    }
    if (type == 'region') {
      if (this.formControls?.region?.valid) {
        this.publicService?.removeValidators(this.searchForm, ['placeName']);
        this.publicService?.removeValidators(this.searchForm, ['category']);
      } else {
        this.publicService?.addValidators(this.searchForm, ['placeName']);
        this.publicService?.addValidators(this.searchForm, ['category']);
      }
    }
    this.placesPageNumber = 1;
    let formInfo: any = this.searchForm?.value;
    this.placeName = formInfo?.placeName;
    this.regionId = formInfo?.region?.id;
    this.cityId = formInfo?.city?.id;
    this.getPlacesList(true);
  }
  changePageActiveNumber(number: number): void {
    this.isChangePage = true;
    this.paginatorPlacesList?.changePage(number - 1);
  }

  getPlacesList(hideFullLoading?: boolean, lastActiveIndex?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isSearch = false;
      if (hideFullLoading) {
        this.isLoadingPlaces = false;
        this.isLoadingMorePlaces = true;
      } else {
        this.isLoadingPlaces = true;
        this.isLoadingMorePlaces = false;
      }
      this.placesService?.getPlaces(this.placesPageNumber, this.placesPerPageCount, this.placesListKeyword, this.placeName, this.regionId, this.cityId, this.categoriesIds ? this.categoriesIds : null, this.subCategoryId, this.priceId, null, true)?.subscribe(
        (res: Place) => {
          if (res?.code == 200) {
            this.mapLocations = [];
            this.placesList = res?.data?.items ? res?.data?.items : [];
            if (this.placeName || this.regionId || this.cityId || this.categoryId || this.subCategoryId) {
              this.isSearch = true;
            }
            if (this.placesList?.length > 0) {
              this.placesList?.forEach((element: any) => {
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
              this.placesList?.forEach((el: any) => {
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
            this.isLoadingPlaces = false;
            this.isLoadingMorePlaces = false;
            this.isChangePage = false;
            this.placesListTotalCount = res?.data?.total ? res?.data?.total : 0;
            // this.placesPageNumber == 1 ? this.getPaginatedData() : '';
          } else {
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
            this.isLoadingPlaces = false;
            this.isLoadingMorePlaces = false;
            this.isChangePage = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingPlaces = false;
          this.isLoadingMorePlaces = false;
          this.isChangePage = false;
        }
      );
      this.cdr.detectChanges();
    }
  }

  loadMorePlaces(): void {
    this.placesPageNumber++;
    // this.placesPerPageCount += 16;
    // this.getPlacesList(true);
    this.getPlacesList(true, this.placesListOptionsIndex);
  }

  // onPageChangePlaces(event: any) {
  //   this.placesPageNumber = event.page + 1;
  //   // this.getPaginatedData();
  //   this.isChangePage ? '' : this.getPlacesList();
  //   if (isPlatformBrowser(this.platformId)) {
  //     window.scrollTo({ top: 1050, behavior: 'smooth' });
  //   }
  // }
  changePage(direction: number) {
    if (direction === -1 && this.placesPageNumber > 1) {
      this.placesPageNumber--;
    } else if (direction === 1 && this.placesPageNumber < Math.ceil(this.placesListTotalCount / this.placesPerPageCount)) {
      this.placesPageNumber++;

    }
    this.isChangePage ? '' : this.getPlacesList();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  changeCommonPage(direction: number) {
    if (direction === -1 && this.visitedPlacesPageNumber > 1) {
      this.visitedPlacesPageNumber--;
    } else if (direction === 1 && this.visitedPlacesPageNumber < Math.ceil(this.visitedPlacesListTotalCount / this.visitedPlacesPerPageCount)) {
      this.visitedPlacesPageNumber++;

    }
    this.isChangePage ? '' : this.getVisitedPlacesList();
    console.log(this.isChangePage)
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 2000, behavior: 'smooth' });
    }
  }
  handleViewAll() {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  getPaginatedData(): any {
    const startIndex: any = (this.currentPage - 1) * 12;
    const endIndex: any = startIndex + 12;
    this.paginatedPlacesList = this.placesList?.slice(startIndex, endIndex);
  }
  showMap(): void {
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: this.mapLocations,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  onFieldChanged(event: any) {
    if (event.fieldName === 'region') {
      console.log('Selected value:', event.value, event?.value?.id);
      this.getCitiesByRegionId(event)
    }
  }

  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    if (this.isLoadingPlaces || this.isLoadingMorePlaces || this.isLoadingVisitedPlaces || this.isLoadingMoreVisitedPlaces) {
      return;
    }

    item.isLoadingFavourite = true;
    this.messageService?.clear();

    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getPlacesList(true);
          this.getVisitedPlacesList(true);
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => {
        item.isLoadingFavourite = false;
      })
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

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug])
    }
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
