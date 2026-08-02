// Modules
import { Component, ElementRef, Inject, PLATFORM_ID, ViewChild, ChangeDetectorRef } from '@angular/core';
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
import { PrepearTripStepperComponent } from 'src/app/domains';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-places-list',
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
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    FooterComponent,
    NewFooterComponent,
    LazyLoadSectionDirective,
    // Pipes
    StripHtmlPipe

  ],
  templateUrl: './places-list.component.html',
  styleUrls: ['./places-list.component.scss']
})
export class PlacesListComponent {
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
  searchForm: any = this.fb.group(
    {
      placeName: ['', {
        validators: [Validators.required, Validators.min(3)],
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
  placesPerPageCount: any = 8;
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
  visitedPlacesPerPageCount: any = 8;
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

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private messageService: MessageService,
    private activatedRoute: ActivatedRoute,
    private placesService: PlacesService,
    private alertsService: AlertsService,
    private publicService: PublicService,
    private dialogService: DialogService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router,
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('placesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('placesList');

    }

    this.publicService?.placeCategory?.subscribe((res: any) => {
      this.categories?.forEach((el: any) => {
        el.isSelected = false;
      });
      this.categoriesIds = [];
      this.selectedCategories = [];
      this.categories?.forEach((item: any) => {
        if (item?.id == res?.id) {
          this.selectCategory(item);
          this.categoriesScroll.nativeElement.scrollIntoView({ behavior: 'smooth' });
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
    this.getHeroSliderPlaces();
    this.getTripSteps();
    this.getCategories();
    this.getPlacesList();
    this.getVisitedPlacesList();
    this.getRegions();
    // this.getPrices();

    this.searchForm?.get('placeName')?.valueChanges.subscribe((res: any) => {
      if (res) {
        this.messageService.clear('search');
      }
    });
  }
  getHeroSliderPlaces(): void {
    this.heroSliderData = this.currentLanguage == 'ar' ? staticPlacesDataAr?.heroSliderPlaces : this.currentLanguage == 'ru' ? staticPlacesDataRu?.heroSliderPlaces : this.currentLanguage == 'zh' ? staticPlacesDataZh?.heroSliderPlaces : staticPlacesDataEn?.heroSliderPlaces;
  }
  getRegions(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingRegions = true;
      this.placesService?.getRegions()?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.regions = res?.data;
            this.isLoadingRegions = false;
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
  }
  getCitiesByRegionId(region: any): void {
    this.formControls.city.reset();
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingCities = true;
      this.placesService?.getCities(region?.value?.id)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.cities = res?.data;
            this.isLoadingCities = false;
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
  }

  getCategories(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingCategories = true;
      this.placesService?.getCategories()?.subscribe(
        (res: any) => {
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
        (res: any) => {
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
  onPageChangePlaces(event: any) {
    this.placesPageNumber = event.page + 1;
    // this.getPaginatedData();
    this.isChangePage ? '' : this.getPlacesList();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 1050, behavior: 'smooth' });
    }
  }

  getPaginatedData(): any {
    const startIndex: any = (this.currentPage - 1) * 8;
    const endIndex: any = startIndex + 8;
    this.paginatedPlacesList = this.placesList?.slice(startIndex, endIndex);
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
        (res: any) => {
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

  search(): void {
    this.messageService?.clear();
    this.isSubmitted = true;
    if (this.searchForm?.valid) {
      this.displaySearch = false;
      let formInfo: any = this.searchForm?.value;
      this.placeName = formInfo?.placeName;
      this.regionId = formInfo?.region?.id;
      this.cityId = formInfo?.city?.id;
      this.priceId = formInfo?.price?.id;
      this.placesPageNumber = 1;
      this.isSubmittedSearch = true;
      this.getPlacesList(false);
      if (isPlatformBrowser(this.platformId)) {
        window.scrollTo({ top: 1050, behavior: 'smooth' });
      }
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterPlaceNameOrRegionOrCategory'), 'search')
      this.publicService?.validateAllFormFields(this.searchForm);
    }
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

  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }
  getTripSteps(): void {
    // this.isLoadingTripSteps = true;
    // this.placesService?.getTripSteps()?.subscribe(
    //   (res: any) => {
    //     if (res?.code== 200) {
    //       this.isLoadingTripSteps = false;
    //       this.tripSteps = res?.result;
    //     } else {
    //       this.isLoadingTripSteps = false;
    //       res?.message
    //         ? this.alertsService?.openToast('error', res?.message)
    //         : '';
    //     }
    //   },
    //   (err: any) => {
    //     err ? this.alertsService?.openToast('error', err?.message) : '';

    //     this.isLoadingTripSteps = false;
    //   }
    // );
    this.tripSteps = this.currentLanguage == 'ar' ? staticPlacesDataAr?.tripSteps : this.currentLanguage == 'ru' ? staticPlacesDataRu?.tripSteps : this.currentLanguage == 'zh' ? staticPlacesDataZh?.tripSteps : staticPlacesDataEn?.tripSteps;
  }

  openTripSteps(): void {
    const ref = this.dialogService?.open(StartTripStepsComponent, {
      width: '70%',
    });
  }

  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    if (this.isLoadingPlaces || this.isLoadingMorePlaces || this.isLoadingVisitedPlaces || this.isLoadingMoreVisitedPlaces) {
      return;
    }
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getPlacesList(true);
          this.getVisitedPlacesList(true);
          this.alertsService?.openToast('success', res.message || this.publicService?.translateTextFromJson('general.addFavorites'));
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
