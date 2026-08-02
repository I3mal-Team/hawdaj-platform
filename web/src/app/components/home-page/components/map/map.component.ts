import { DialogService, DynamicDialogModule } from 'primeng/dynamicdialog';
import { ShareComponent } from '../../../../modules/shared/components/share/share.component';
import { TranslationChildModule } from '../../../../services/translation-child.module';
import { GoogleMap, GoogleMapsModule, MapInfoWindow, MapMarker } from '@angular/google-maps';
import { CUSTOM_ELEMENTS_SCHEMA, ChangeDetectorRef, Component, ElementRef, Inject, PLATFORM_ID, QueryList, ViewChild, ViewChildren } from '@angular/core';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { environment } from '../../../../../environments/environment';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { CommonModule, isPlatformBrowser, NgOptimizedImage } from '@angular/common';
import { HomeService } from '../../../../services/home.service';
import { Router, RouterModule } from '@angular/router';
import { SkeletonModule } from 'primeng/skeleton';
import { SidebarModule } from 'primeng/sidebar';
import { darkModeTheme } from './map-options';
import { FormsModule } from '@angular/forms';
import { RatingModule } from 'primeng/rating';
import { Subscription } from 'rxjs';
import { CarouselModule } from 'primeng/carousel';
import { LazyImgDirective } from 'src/app/modules/shared/directives/lazy-img.directive';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  standalone: true,
  imports: [SidebarModule, FormsModule, TranslationChildModule, CarouselModule, GoogleMapsModule, LazyLoadImageDirective, NgOptimizedImage, CommonModule, RouterModule, SkeletonModule, RatingModule, DynamicDialogModule],
  selector: 'app-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.scss'],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class MapComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  @ViewChild(GoogleMap, { static: false }) map: GoogleMap | undefined;

  explore: boolean = false;

  selecedMarker: any = null;
  @ViewChild(MapInfoWindow) infoWindow!: MapInfoWindow;
  @ViewChildren(MapMarker) markers!: QueryList<MapMarker>;
  @ViewChild('searchMapInput', { static: false }) searchInputRef!: ElementRef;

  center!: google.maps.LatLngLiteral;
  zoom: any = 5;
  darkMode: any = darkModeTheme;
  isLight: boolean = false;
  showSatellite: boolean = false;
  selectSatellite: boolean = false;

  resultsDataList: any = [];
  resultsTotalCount: number = 0;
  rate = 5

  markerPositions: any[] = [];
  page: any = 1;
  perPage: any = 50;
  totalPlaces: number = 0;
  searchValue: any = null;
  isLoading: boolean = false;
  isLoadingFilter: boolean = false;
  locations: any = [];
  mapResultDataConfig = [
    {
      breakpoint: '1240px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 1,
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1,
    },
  ];
  showInputSearch: boolean = false;
  collapsedMenu: boolean = false;
  tabs: any = [
    {
      id: 'places', name: 'titles.places', value: 'place', isActive: false
    },
    { id: 'stores', name: 'titles.stores', value: 'store', isActive: false },
    { id: 'zads', name: 'titles.zad', value: 'zad', isActive: false },
    { id: 'events ', name: 'titles.events', value: 'event', isActive: false },
  ];
  views: any = null;
  tabsView: any = [];
  isTogglePlaces: boolean = false;
  openSidebar: boolean = false;
  openSidebarFromTop: boolean = false;
  isLoadingSaveLocation: boolean = false;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    private publicService: PublicService,
    private homeService: HomeService,
    private cdr: ChangeDetectorRef,
    private router: Router
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      const windowWidth = window.innerWidth;
      windowWidth <= 576 ? this.openSidebarFromTop = true : this.openSidebarFromTop = false;
    }
    this.page = 1;
    this.markerPositions = [];
    this.getPlacesDataForMap();

    this.publicService?.showMap?.subscribe((res: any) => {
      if (res) {
        this.explore = res;
        this.page = 1;
        this.markerPositions = [];
        this.getPlacesDataForMap();
        // this.publicService?.toggleBodyScroll(false);
      }
    });
  }

  togglePlaces(): void {

  }
  ngAfterViewInit(): void {
    // setTimeout(() => {
    //   this.initAutocomplete();
    // }, 500);
    if (this.map) {
      // console.log('Map instance is ready');
    }
  }

  onMapClick(event: any): void {
    this.closeAllInfoWindows();
    this.collapsedMenu = false;
    // let newMarker: any = event.latLng.toJSON();
    // console.log(newMarker);
    // newMarker['content'] = { title: 'New Place', location_name: 'Location Name', thumbil_image: 'assets/images/icons/location.svg' };
    // this.markerPositions.push(newMarker);
  }
  openInfoWindow(marker: MapMarker, markerPosition: any): void {
    this.selecedMarker = markerPosition?.content;
    this.openSidebar = true;
    // this.infoWindow.open(marker);
  }
  closeAllInfoWindows(): void {
    this.infoWindow?.close();
  }

  // initAutocomplete(): void {
  //   if (typeof google === 'undefined' || typeof google.maps === 'undefined' || typeof google.maps.places === 'undefined') {
  //     console.error('Google Maps Places library is not loaded.');
  //     return;
  //   }

  //   const searchInput = this.searchInputRef.nativeElement;
  //   const autocomplete = new google.maps.places.Autocomplete(searchInput);

  //   autocomplete.addListener('place_changed', () => {
  //     const place: any = autocomplete.getPlace();

  //     if (place.geometry && place.geometry.location) {
  //       const lat = place.geometry.location.lat();
  //       const lng = place.geometry.location.lng();
  //       if (lat && lng) {
  //         this.center = { lat: lat, lng: lng };
  //         this.zoom = 16;
  //       }
  //     } else {
  //       // If the location isn't available, you can set a default location
  //       this.center = { lat: this.markerPositions[0]?.lat, lng: this.markerPositions[0]?.long }; // Coordinates of Riyadh, Saudi Arabia
  //       this.zoom = 5;
  //     }
  //   });
  // }

  onSearchMap(event: any): void {
    this.searchValue = event.target.value;
    this.getPlacesDataForMap();
  }
  clearSearch(event?: any): void {
    this.zoom = 5;
    this.page = 1;
    this.searchValue = null;
    this.getPlacesDataForMap();
    this.zoom = 5;
  }
  getPlacesDataForMap(preventLoading?: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      preventLoading ? this.isLoadingFilter = true : this.isLoading = true;
      this.homeService?.getGlobalSearch(this.page, this.perPage, this.searchValue, null, null, this.views)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            let marks: any = [];
            if (this.page == 1) {
              this.resultsDataList = res?.data?.items ? res?.data?.items : [];
            }
            else {
              this.resultsDataList.push(...(res?.data?.items ? res?.data?.items : []));
            }
            this.resultsDataList?.forEach((event: any) => {
              if (event?.lat && event?.long) {
                event['address_location'] = this.publicService.createGoogleMapsLink(event?.lat, event?.long);
              }
              if (event?.region?.name && event?.city?.name) {
                event['address_name'] = event?.region?.name + ', ' + event?.city?.name;
              } else if (event?.region?.name) {
                event['address_name'] = event?.region?.name;
              } else if (event?.city?.name) {
                event['address_name'] = event?.city?.name;
              }

              if (event?.type == 'place') {
                event['type_name'] = this.publicService?.translateTextFromJson('titles.places');
              } else if (event?.type == 'store') {
                event['type_name'] = this.publicService?.translateTextFromJson('titles.stores');
              } else if (event?.type == 'zad') {
                event['type_name'] = this.publicService?.translateTextFromJson('titles.zad');
              }
              else if (event?.type == 'event') {
                event['type_name'] = this.publicService?.translateTextFromJson('titles.events');
              }
            });
            this.resultsTotalCount = res?.data?.total ? res?.data?.total : 0;
            res?.data?.items ? this.resultsDataList?.forEach((el: any) => {
              marks?.push(
                {
                  lat: el?.lat,
                  lng: el?.long,
                  icon: {
                    url: el?.image ? el?.image : 'assets/images/icons/location2.svg',
                    scaledSize: new google.maps.Size(30, 30),
                    // url: el?.place_icon ? this.el?.place_icon : 'assets/images/icons/location2.svg',
                    // scaledSize: new google.maps.Size(50, 50),
                    origin: new google.maps.Point(0, 0), // origin
                    optimized: false,
                    shape: {
                      coords: [1, 1, 1, 20, 18, 20, 18, 1],
                      type: "circle",
                    }
                    // size: el?.place_icon ? new google.maps.Size(30, 30) : new google.maps.Size(50, 50),
                    // shape: { type: 'circle' },
                    // size: el?.place_icon ? new google.maps.Size(30, 30) : new google.maps.Size(50, 50),
                  },
                  content: {
                    id: el?.id,
                    slug: el?.slug,
                    title: el?.title,
                    type: el?.type,
                    type_name: el?.type_name,
                    address_name: el?.address_name,
                    address: el?.address,
                    address_location: el?.address_location,
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
            this.totalPlaces = res?.data?.total;
            this.isLoading = false;
            this.isLoadingFilter = false;
            this.cdr.detectChanges();
          } else {
            this.isLoading = false;
            this.isLoadingFilter = false;
            res?.message
              ? this.alertsService?.openToast('error', res?.message)
              : '';
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoading = false;
          this.isLoadingFilter = false;
        }
      );
    }
  }
  selectView(): void {
    let arr: any = [];
    this.tabs?.forEach((item: any) => {
      if (item?.isActive) {
        arr?.push(item?.id);
      }
    });
    this.views = arr;
    this.tabsView = arr;
    this.getPlacesDataForMap(true);
  }
  goToDetails(selecedMarker: any): void {
    if (selecedMarker?.slug) {
      this.close();
      this.openSidebar = false;
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
      this.publicService?.toggleBodyScroll(true);
      this.explore = false;
    }
  }

  getMorePlaces(): void {
    if (this.map) {
      let currentZoom: any = this.map.getZoom();
      if (currentZoom > this.zoom) {
        if (this.totalPlaces > this.markerPositions?.length) {
          this.page = this.page + 1;
          this.getPlacesDataForMap(true);
        }
      } else {
        // this.page = this.page - 1;
        // this.publicService.removeLastNthElements(this.markerPositions, this.perPage);
      }
      this.cdr.detectChanges();
    }
  }

  close(): void {
    this.explore = false;
    this.publicService?.toggleBodyScroll(true);
    this.clearSearch();
    this.closeAllInfoWindows();
    // this.initAutocomplete();
    this.center = { lat: this.markerPositions[0]?.lat, lng: this.markerPositions[0]?.long };
    this.zoom = 5;
    this.tabs.forEach((element: any) => {
      element.isActive = false;
    });
    this.views = null;
    this.publicService.showMap.next(false);
  }
  share(): void {
    let fullUrl: any;
    switch (this.selecedMarker?.type) {
      case 'place':
        fullUrl = environment.publicUrl + '/places/details/' + this.selecedMarker?.slug;
        break;
      case 'event':
        fullUrl = environment.publicUrl + '/events/event-details/' + this.selecedMarker?.slug;
        break;
      case 'store':
        fullUrl = environment.publicUrl + '/stores/' + this.selecedMarker?.slug;
        break;
      case 'zad':
        fullUrl = environment.publicUrl + '/restaurants/' + this.selecedMarker?.slug;
        break;
      default:
        break;
    }
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: fullUrl,
      },
      styleClass: 'rate',
    });
  }
  saveLocation(): void {
    this.isLoadingSaveLocation = true;
    this.publicService?.isSaved(this.selecedMarker?.type, this.selecedMarker?.id)?.subscribe(
      (res: any) => {
        if (res.code == 200) {
          this.isLoadingSaveLocation = false;
          this.alertsService?.openToast(
            'success',
            res.message || this.publicService.translateTextFromJson('general.sentSuccessfully')
          );
          this.selecedMarker.is_saved = true;
        } else {
          res?.message
            ? this.alertsService?.openToast('error', res?.message)
            : '';
          this.isLoadingSaveLocation = false;
        }
      },
      (err: any) => {
        this.isLoadingSaveLocation = false;
        err ? this.alertsService?.openToast('error', err) : '';
      }
    );
  }
  goToLocation(): void { }
  closeSidebar(): void {
    this.openSidebar = false;
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
