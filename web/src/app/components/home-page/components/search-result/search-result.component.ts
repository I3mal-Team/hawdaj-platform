import { Component, ElementRef, QueryList, ViewChild, ViewChildren, ChangeDetectorRef, HostListener, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { MapInfoWindow, MapMarker } from '@angular/google-maps';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { DialogService } from 'primeng/dynamicdialog';
import { MessageService } from 'primeng/api';
import { Subscription } from 'rxjs';
import { catchError, finalize, tap } from 'rxjs/operators';
import { environment } from 'src/environments/environment';
import { AlertsService } from 'src/app/services/alerts.service';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { HomeService } from 'src/app/services/home.service';
import { PlacesService } from 'src/app/services/places.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { darkModeTheme } from '../map/map-options';
import { TranslateModule } from '@ngx-translate/core';
import { MultiSelectModule } from 'primeng/multiselect';
import { DropdownModule } from 'primeng/dropdown';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { SidebarModule } from 'primeng/sidebar';
import { ImageModule } from 'primeng/image';
import { RatingModule } from 'primeng/rating';
import { GoogleMapsModule } from '@angular/google-maps';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';

@Component({
  standalone: true,
  imports: [
    TranslateModule,
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    MultiSelectModule,
    DropdownModule,
    SkeletonComponent,
    SidebarModule,
    ImageModule,
    RatingModule,
    GoogleMapsModule,
    HeaderComponent,
    FooterComponent,
    NewFooterComponent
  ],
  selector: 'app-search-result',
  templateUrl: './search-result.component.html',
  styleUrls: ['./search-result.component.scss']
})
export class SearchResultComponent {
  private subscriptions: Subscription[] = [];
  currentLang: any;

  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;
  @ViewChild('searchMapInput', { static: false }) searchInputRef!: ElementRef;

  center!: google.maps.LatLngLiteral;// Coordinates of Riyadh, Saudi Arabia
  zoom: any = 5;
  // darkMode: any;
  darkMode: any = darkModeTheme;
  searchValue: any;
  markerPositions: any = [];
  views: any = [];
  filterForm: any = this.fb.group(
    {
      view: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      name: ['', {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      region: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
      city: [null, {
        validators: [Validators.required],
        updateOn: 'change'
      }],
    },
  );
  get formControls(): any {
    return this.filterForm?.controls;
  }
  regions: any = [];
  isLoadingRegions: boolean = false;

  cities: any = [];
  isLoadingCities: boolean = false;

  searchResults: any = [];
  searchInputResults: any = [];
  isLoadingItems: boolean = false;
  isLoadingFilter: boolean = false;

  page: any = 1;
  perPage: any = 20;
  eventsKeyword: any = null;
  searchResultsTotalCount: any;
  isLoadingMoreResults: boolean = false;
  view: any = null;
  name: any = '';
  regionId: any;
  cityId: any;
  data: any;
  showOnMap: boolean = true;
  displaySearch: boolean = false;
  @HostListener("window:scroll", ["$event"])
  handleScroll(event: Event) {
    this.handleKeyDown();
  }
  ngAfterViewInit() {
    this.handleKeyDown();
  }
  handleKeyDown() {
    let element: any = document.querySelector(".search-content") as HTMLElement;
    if (element) {
      if (window.pageYOffset > 5) {
        element ? element.classList.add("shadow-sm") : '';
      } else {
        element ? element.classList.remove("shadow-sm") : '';
      }
    } else {
      console.error("Element with class 'navbar' not found");
    }
  }
  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private messageService: MessageService,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private placesService: PlacesService,
    private dialogService: DialogService,
    private homeService: HomeService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLang = this.publicService.getCurrentLanguage();
      this.views = [
        {
          id: 1, name: this.currentLang == 'ar' ? 'الأماكن' : this.currentLang == 'ru' ? 'Места' : this.currentLang == 'zh' ? '地点' : 'Places', value: 'places'
        },
        { id: 1, name: this.currentLang == 'ar' ? 'المتاجر' : this.currentLang == 'ru' ? 'Магазины' : this.currentLang == 'zh' ? '商店' : 'Stores', value: 'stores' },
        { id: 1, name: this.currentLang == 'ar' ? 'زاد' : this.currentLang == 'ru' ? 'Ресторан' : this.currentLang == 'zh' ? '餐厅' : 'Restaurants', value: 'zads' },
        { id: 1, name: this.currentLang == 'ar' ? 'الفعاليات ' : this.currentLang == 'ru' ? 'События' : this.currentLang == 'zh' ? '活动' : 'Events', value: 'events' },
      ]
    }
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
    }
    this.data = this.activatedRoute?.snapshot?.params;
    if (this.data?.name) {
      this.filterForm.controls['name'].setValue(this.data?.name);
      this.name = this.data?.name;
    }
    if (this.data?.regionId) {
      this.regionId = this.data?.regionId;
    }
    if (this.data?.cityId) {
      this.cityId = this.data?.cityId;
    }
    this.getRegions();
    this.getGlobalSearch(false, false);
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | نتائج البحث`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | نتائج البحث` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/search-resul` },
      { property: 'og:title', content: `هودج | نتائج البحث` },
    ]);
    this.metadataService.setSharePreviewImage(null);
  }

  searchItems(event: any): void {
    this.searchInputResults = this.searchResults?.filter((item: any) => {
      return item?.title?.toLocaleLowerCase()?.includes(event?.toLocaleLowerCase());
    });

  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.filterForm?.get('name')?.setValue('');
    this.filterForm?.get('view')?.setValue(null);
    this.page = 1;
    this.eventsKeyword = null;
    this.name = null;
    this.view = null;
    this.searchInputResults = this.searchResults;
    // this.getGlobalSearch();
  }

  getRegions(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingRegions = true;
      this.placesService?.getRegions()?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.regions = res?.data;
            this.isLoadingRegions = false;
            this.regions?.forEach((el: any) => {
              if (this.data?.regionId) {
                if (el?.id == this.data?.regionId) {
                  this.filterForm.controls['region'].setValue(el);
                  this.getCitiesByRegionId(el?.id, true);
                }
              }
            });
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
  clearSelectedRegion(event: Event): void {
    event.stopPropagation();
    this.filterForm.patchValue({ region: null });
    this.getCitiesByRegionId(null, false);
  }

  clearSelectedCity(event: Event): void {
    event.stopPropagation();
    this.filterForm.patchValue({ city: null });
  }
  getCitiesByRegionId(id: any, isPatch?: any): void {
    this.formControls.city.reset();
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingCities = true;
      this.placesService?.getCities(id)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.cities = res?.data;
            this.isLoadingCities = false;
            if (isPatch) {
              this.cities?.forEach((el: any) => {
                if (this.data?.cityId) {
                  if (el?.id == this.data?.cityId) {
                    this.filterForm.controls['city'].setValue(el);
                  }
                }
              });
            }
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

  getGlobalSearch(loadFilter?: any, loadMore?: any): void {
    if (isPlatformBrowser(this.platformId)) {
      if (loadFilter) {
        this.isLoadingFilter = true;
        this.isLoadingMoreResults = false;
        this.isLoadingItems = false;
      }
      if (loadMore) {
        this.isLoadingFilter = false;
        this.isLoadingMoreResults = true;
        this.isLoadingItems = false;
      }
      if (!loadFilter && !loadMore) {
        this.isLoadingItems = true;
      }

      this.homeService?.getGlobalSearch(this.page, this.perPage, this.name ? this.name : null, this.regionId, this.cityId, this.view?.length > 0 ? this.view : null)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            let marks: any = [];
            res?.data?.items?.forEach((item: any) => {
              item['isFavorite'] = false;
              if (item?.region?.name && item?.city?.name) {
                item['address_name'] = item?.region?.name + ', ' + item?.city?.name;
              } else if (item?.region?.name) {
                item['address_name'] = item?.region?.name;
              } else if (item?.city?.name) {
                item['address_name'] = item?.city?.name;
              }
            });
            res?.data?.items?.forEach((item: any) => {
              item['image'] = item?.image;
              if (item?.type == 'place') {
                item['type_name'] = this.publicService?.translateTextFromJson('titles.places');
              } else if (item?.type == 'store') {
                item['type_name'] = this.publicService?.translateTextFromJson('titles.stores');
              } else if (item?.type == 'zad') {
                item['type_name'] = this.publicService?.translateTextFromJson('titles.zad');
              }
              else if (item?.type == 'event') {
                item['type_name'] = this.publicService?.translateTextFromJson('titles.events');
              }
            });

            if (this.page == 1) {
              this.searchResults = res?.data?.items ? res?.data?.items : [];
            }
            else {
              this.searchResults.push(...(res?.data?.items ? res?.data?.items : []));
            }
            this.searchInputResults = this.searchResults;
            res?.data?.items ? this.searchResults?.forEach((el: any) => {
              marks?.push(
                {
                  lat: el?.lat,
                  lng: el?.long,
                  icon: {
                    // url: el?.place_icon ? this.el?.place_icon : 'assets/images/icons/location2.svg',
                    // size: el?.place_icon ? new google.maps.Size(30, 30) : new google.maps.Size(50, 50),
                    url: el?.image ? el?.image : 'assets/images/not-found/no-img.svg',
                    scaledSize: new google.maps.Size(30, 30),
                    origin: new google.maps.Point(0, 0),
                  },
                  content: {
                    id: el?.id,
                    slug: el?.slug,
                    title: el?.title,
                    type: el?.type,
                    address_name: el?.address_name,
                    thumbil_image: el?.image,
                    review: el?.ratings_count,
                    rate: el?.rate ? el?.rate : 0
                  }
                }
              );
            }) : '';
            this.markerPositions = marks;
            let count = 0;
            this.markerPositions?.forEach((el: any, index: any) => {
              if (el?.lat != 0 && el?.lng != 0) {
                this.center = { lat: this.markerPositions[index]?.lat, lng: this.markerPositions[index]?.lng };
              } else {
                count++;
              }
            });
            if (count == this.markerPositions?.length) {
              this.center = { lat: 24.774265, lng: 46.738586 };
            }
            this.searchResultsTotalCount = res?.data?.total;
            this.isLoadingItems = false;
            this.isLoadingFilter = false;
            this.isLoadingMoreResults = false;
            this.displaySearch = false;
          } else {
            this.isLoadingItems = false;
            this.isLoadingFilter = false;
            this.isLoadingMoreResults = false;
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoadingItems = false;
          this.isLoadingMoreResults = false;
          this.isLoadingFilter = false;
        }
      );
    }
  }
  loadMoreResults(): void {
    this.page++;
    this.getGlobalSearch(false, true);
  }
  goToDetails(selecedMarker: any): void {
    if (selecedMarker?.slug) {
      switch (selecedMarker?.type) {
        case 'place':
          this.router.navigate(['/places/details/', selecedMarker?.slug]);
          break;
        case 'event':
          this.router.navigate(['/events/event-details/', selecedMarker?.slug]);
          break;
        case 'store':
          this.router.navigate(['/stores/', selecedMarker?.slug]);
          break;
        case 'zad':
          this.router.navigate(['/restaurants/', selecedMarker?.slug]);
          break;
        default:
          break;
      }
    }
  }
  filter(): void {
    let form: any = this.filterForm?.value;
    let arr: any = [];
    form?.view?.forEach((item: any) => {
      arr?.push(item?.value);
    });
    this.view = arr;
    this.name = form?.name;
    this.regionId = form?.region?.id;
    this.cityId = form?.city?.id;
    this.page = 1;
    this.getGlobalSearch(true, false);
    window?.scrollTo(0, 0);
  }
  reset(): void {
    this.filterForm?.reset();
    this.page = 1;
    this.eventsKeyword = null;
    this.name = null;
    this.view = null;
    this.regionId = null;
    this.cityId = null;
    this.getGlobalSearch(false, false);
    window?.scrollTo(0, 0);
  }
  showMap(): void {
    let mapLocations: any = [];
    this.markerPositions?.forEach((el: any) => {
      mapLocations?.push({
        lat: el?.lat,
        lng: el?.lng,
        name: el?.content?.title,
        image: el?.content?.thumbil_image,
        address_name: el?.content?.address_name,
        review: el?.content?.review,
        rate: el?.content?.rate ? el?.content?.rate : 0,
        type: el?.content?.type,
        slug: el?.content?.slug,
      });
    });
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: mapLocations,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
  }
  onImageError(item: any): void {
    this.searchInputResults?.forEach((el: any) => {
      if (item?.id == el?.id) {
        item['image'] = "assets/images/not-found/no-img.svg";
      }
    });
  }
  onPageChangeResults(event: any): void { }

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    // let newMarker: any = event.latLng.toJSON();
    // console.log(newMarker);
    // newMarker['content'] = { title: 'New Place', location_name: 'Location Name', thumbil_image: 'assets/images/icons/location.svg' };
    // this.markerPositions.push(newMarker);
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    this.selecedMarker = markerPosition?.content;
    this.infoWindow.open(marker);
  }
  closeAllInfoWindows(): void {

    this.infoWindow?.close();
  }


  capitalizeFirstLetter(str: string): string {
    return str.replace(/^\w/, (match) => match.toUpperCase());
  }
  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getGlobalSearch(true);
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

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
