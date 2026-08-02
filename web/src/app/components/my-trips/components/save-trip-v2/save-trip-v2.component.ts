import { ComingSoonModalComponent } from '../../../../modules/shared/components/coming-soon-modal/coming-soon-modal.component';
import { LocalizationLanguageService } from '../../../../modules/shared/services/localization-language.service';
import { ConfirmDeleteTripComponent } from '../confirm-delete-trip/confirm-delete-trip.component';
import { ShowTripMapModalComponent } from '../show-trip-map-modal/show-trip-map-modal.component';
import { CreateTripModalComponent } from 'src/app/components/my-trips/components/create-trip-modal/create-trip-modal.component';
import { TripEmailModalComponent } from '../trip-email-modal/trip-email-modal.component';
import { AuthService } from '../../../../services/auth.service';
import { SaveTripModalComponent } from 'src/app/components/my-trips/components/save-trip-modal/save-trip-modal.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { environment } from '../../../../../environments/environment';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { TripsService } from '../../../../services/trips.service';
import { Component, inject, Inject, PLATFORM_ID } from '@angular/core';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { catchError, finalize, Subscription, tap } from 'rxjs';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { AddAnotherPlaceComponent } from 'src/app/components/my-trips/components/add-another-place/add-another-place.component';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { InputTextModule } from 'primeng/inputtext';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { RatingModule } from 'primeng/rating';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { ToastModule } from 'primeng/toast';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { getKeyFromValue } from 'src/app/Common/enums/map.enum';
import { SaveTripCardComponent } from './save-trip-card/save-trip-card.component';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { Banner2Component } from 'src/app/Common/layout/banner-2/banner-2.component';
import { getFormattedDate } from 'src/app/Common/functions/getFormatedDate.utils';
import { getFormattedIndex } from 'src/app/Common/functions/getFormattedIndex.utils';
import { NoSaveTripDataComponent } from './no-save-trip-data/no-save-trip-data.component';
import { getAdjustedDate } from 'src/app/Common/functions/getAdjustedDate';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    InputTextModule,
    RatingModule,
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    ToastModule,
    NewFooterComponent,
    RouterModule,
    SkeletonComponent,
    // Directives
    LazyLoadSectionDirective,
    Banner2Component,
  ],
  selector: 'app-save-trip-v2',
  templateUrl: './save-trip-v2.component.html',
  styleUrls: ['./save-trip-v2.component.scss']
})

export class SaveTripV2Component {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  commingSoonImage: string = 'assets/images-v2/pages/place-details/hawdaj-bot.png';

  isLoading: boolean = false;
  tripId: any;
  tripData: any;
  tripPlaces: any = [];
  finalPlaces: any;
  allDates: any = [];
  mapLocations: any = [];
  placeLocations: any = [];
  notShowPage: boolean = false;
  staticData: any;
  regions: any;
  region1: any;
  region2: any;
  token: any;

  homeShowFooter: boolean = false;
  private ref: DynamicDialogRef | null = null;

  private platformId = inject(PLATFORM_ID);
  private activatedRoute = inject(ActivatedRoute);
  private dialogService = inject(DialogService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private tripsService = inject(TripsService);
  private authService = inject(AuthService);
  private router = inject(Router);
  private route = inject(ActivatedRoute)
  getFormattedDate = getFormattedDate;
  getFormattedIndex = getFormattedIndex;
  getAdjustedDate = getAdjustedDate;
  constructor(
    private localizationLanguageService: LocalizationLanguageService,
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.route.paramMap.subscribe(params => {
      this.token = params.get('token');
    });
    // this.tripId = this.activatedRoute?.snapshot?.params?.['id'];
    this.getTrip();
  }
  // getTripForSave(): void {
  //   const tripDataString = window.localStorage?.getItem(keys?.saveTripData);
  //   // this.tripData = tripDataString !== null ? JSON.parse(tripDataString) : null;
  //   this.staticData = this.tripData;

  //   if (this.tripData == null) {
  //     this.notShowPage = true;
  //     const ref = this?.dialogService?.open(CreateTripModalComponent, {
  //       width: '35%',
  //       closable: false
  //     });
  //   } else {
  //     this.notShowPage = false;
  //   }
  //   let indexNumber: any = 0;
  //   this.tripData?.places?.forEach((item: any) => {
  //     item?.forEach((onePlace: any) => {
  //       if (onePlace?.region?.name && onePlace?.city?.name) {
  //         onePlace['address_name'] = this.getItemNameByLocale(onePlace?.region, this.currentLanguage) + ', ' + this.getItemNameByLocale(onePlace?.city, this.currentLanguage);
  //       } else if (onePlace?.region?.name) {
  //         onePlace['address_name'] = this.getItemNameByLocale(onePlace?.region, this.currentLanguage);
  //       } else if (onePlace?.city?.name) {
  //         onePlace['address_name'] = this.getItemNameByLocale(onePlace?.city, this.currentLanguage);
  //       }
  //     });
  //     this.region1 = getKeyFromValue(+this.tripData.region1)
  //     this.region2 = getKeyFromValue(+this.tripData.region2)
  //     item?.length > 0 ? indexNumber = indexNumber + 1 : null;
  //     this.tripPlaces?.push({
  //       placesItems: item,
  //       indexNumber: indexNumber
  //     });

  //     if (item?.length > 0) {
  //       item?.forEach((el: any) => {
  //         this.mapLocations?.push({
  //           lat: el?.lat,
  //           lng: el?.long,
  //           name: el?.title,
  //           image: el?.image,
  //           address_name: el?.address_name,
  //           review: el?.review,
  //           type: el?.type,
  //           rate: el?.rate ? el?.rate : 0,
  //           slug: el?.slug
  //         });
  //       });
  //     }
  //   });

  //   if (this.tripData != null) {
  //     this.allDates = this.publicService.getDateArrayFromDateRange(this.tripData?.daterange);
  //     this.allDates?.forEach((date: any, dateIndex: any) => {
  //       this.tripPlaces?.forEach((place: any, placeIndex: any) => {
  //         if (dateIndex == placeIndex) {
  //           place['date'] = date
  //         }
  //       });
  //     });
  //   }
  // }
  getTrip() {
    this.isLoading = true;
    this.tripsService.getpreparedTrip(this.token).pipe(
      tap(response => {
        if (!response?.data) {
          if (this.tripData == null) {
            this.notShowPage = true;
            const ref = this?.dialogService?.open(NoSaveTripDataComponent, {
              width: '35%',
              closable: false,
              data: {
                token: this.token
              }
            });
          } else {
            this.notShowPage = false;
          }
          return;
        }
        this.tripData = response.data;

        let indexNumber: any = 0;
        this.tripData?.places?.forEach((item: any) => {
          item?.forEach((onePlace: any) => {
            if (onePlace?.region && onePlace?.city) {
              // console.log(onePlace?.region)
              onePlace['address_name'] = this.getItemNameByLocale(onePlace?.region, this.currentLanguage) + ', ' + this.getItemNameByLocale(onePlace?.city, this.currentLanguage);
            } else if (onePlace?.region?.name) {
              onePlace['address_name'] = this.getItemNameByLocale(onePlace?.region, this.currentLanguage);
            } else if (onePlace?.city?.name) {
              onePlace['address_name'] = this.getItemNameByLocale(onePlace?.city, this.currentLanguage);
            }
          });
          this.region1 = getKeyFromValue(+this.tripData.region1)
          this.region2 = getKeyFromValue(+this.tripData.region2)
          item?.length > 0 ? indexNumber = indexNumber + 1 : null;
          this.tripPlaces?.push({
            placesItems: item,
            indexNumber: indexNumber
          });

          if (item?.length > 0) {
            item?.forEach((el: any) => {
              this.mapLocations?.push({
                lat: el?.lat,
                lng: el?.long,
                name: el?.title,
                image: el?.image,
                address_name: el?.address_name,
                review: el?.review,
                type: el?.type,
                rate: el?.rate ? el?.rate : 0,
                slug: el?.slug
              });
            });
          }
        });
        if (this.tripData != null) {
          this.allDates = this.publicService.getDateArrayFromDateRange(this.tripData?.start_date);
          this.allDates?.forEach((date: any, dateIndex: any) => {
            this.tripPlaces?.forEach((place: any, placeIndex: any) => {
              if (dateIndex == placeIndex) {
                place['date'] = date
              }
            });
          });
        }
        this.isLoading = false;

      })).subscribe();
  }

  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      // height: '87vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }

  saveTrip(): void {
    if (this.authService?.checkIsLogin()) {
      const ref = this?.dialogService?.open(SaveTripModalComponent, {
        width: '35%',
        styleClass: 'auth-dialog confirm-delete-trip',
        data: { tripData: this.tripData, tripPlaces: this.tripPlaces, token: this.token }
      });
      ref?.onClose?.subscribe((res: any) => {
        if (res?.isSave == true) {
          this.publicService?.toggleBodyScroll(true);
          if (isPlatformBrowser(this.platformId)) {
            window.localStorage.removeItem(keys?.saveTripData);
            window.localStorage.removeItem(keys?.prepareStepData);
          }
        }
      });
    }
  }
  getItemNameByLocale(item: any, locale: string): any {
    const translation = item.translations.find((t: any) => t.locale === locale);
    return translation ? translation.name : 'Unknown';
  }
  getPlaceNameByLocale(item: any, locale: string): any {
    const translation = item.translations.find((t: any) => t.locale === locale);
    return translation ? translation.title : 'Unknown';
  }

  addToCalendar(): void {
    const ref = this?.dialogService?.open(ComingSoonModalComponent, {
      width: '35%',
      styleClass: '',
      header: '',
      dismissableMask: true,
      data: {
        image: this.commingSoonImage,
      }
    });
    ref?.onClose?.subscribe((res: any) => {

    });
  }
  emailTripTo(): void {
    const ref = this?.dialogService?.open(TripEmailModalComponent, {
      width: '45%',
      styleClass: 'auth-dialog confirm-delete-trip',
      data: { tripData: this.tripData, tripPlaces: this.tripPlaces }
    });
    ref?.onClose?.subscribe((res: any) => {
      if (res?.isSave == true) {
        this.publicService?.toggleBodyScroll(true);
      }
    });
  }

  regenerateTrip(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoading = true;
      let stepData: any = JSON.parse(window.localStorage?.getItem(keys?.prepareStepData) || '');
      this.tripsService?.prepareTrip(stepData)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.tripData = [];
            this.tripPlaces = [];
            this.tripData = res?.data;
            window.localStorage?.setItem(keys?.saveTripData, JSON.stringify(res?.data));
            this.tripData = JSON.parse(window.localStorage?.getItem(keys?.saveTripData) || '');
            let indexNumber: any = 0;
            this.tripData?.places?.forEach((item: any) => {
              item?.forEach((onePlace: any) => {
                if (onePlace?.region?.name && onePlace?.city?.name) {
                  onePlace['address_name'] = onePlace?.region?.name + ', ' + onePlace?.city?.name;
                } else if (onePlace?.region?.name) {
                  onePlace['address_name'] = onePlace?.region?.name;
                } else if (onePlace?.city?.name) {
                  onePlace['address_name'] = onePlace?.city?.name;
                }
              });
              item?.length > 0 ? indexNumber += 1 : '';
              this.tripPlaces?.push({
                placesItems: item,
                indexNumber: indexNumber
              });

              if (item?.length > 0) {
                item?.forEach((el: any) => {
                  this.mapLocations?.push({
                    lat: el?.lat,
                    lng: el?.long,
                    name: el?.title,
                    image: el?.image,
                    address_name: el?.address_name,
                    review: el?.review,
                    type: el?.type,
                    rate: el?.rate ? el?.rate : 0,
                    slug: el?.slug
                  });
                });
              }
            });
            this.allDates = this.publicService.getDateArrayFromDateRange(this.tripData?.start_date);
            this.allDates?.forEach((date: any, dateIndex: any) => {
              this.tripPlaces?.forEach((place: any, placeIndex: any) => {
                if (dateIndex == placeIndex) {
                  place['date'] = date
                }
              });
            });
            this.isLoading = false;
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.isLoading = false;
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : '';
          this.isLoading = false;
        }
      );
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
  placeMap(el): void {
    this.placeLocations?.push({
      lat: el?.lat,
      lng: el?.long,
      name: el?.title,
      image: el?.image,
      address_name: el?.address_name,
      review: el?.review,
      type: el?.type,
      rate: el?.rate ? el?.rate : 0,
      slug: el?.slug
    });
    // console.log(this.placeLocations)
    this.showPlaceMap()
  }
  showPlaceMap(): void {
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      data: this.placeLocations,
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  addAnotherPlace(): void {
    const ref = this?.dialogService?.open(AddAnotherPlaceComponent, {
      width: '50%',
      data: { id: this.tripId, allDates: this.allDates },
      header: this.publicService?.translateTextFromJson('saveTrip.addPlaces')
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }

  deletePlaceTrip(place: any, timeLineIndex: any): void {
    const ref = this?.dialogService?.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService?.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
      data: {
        title: this.publicService.translateTextFromJson('trip.areYouSureToDeletePlace'),
        description: this.publicService.translateTextFromJson('trip.beSureToDeletePlace')
      }
    });
    ref?.onClose?.subscribe((res: any) => {
      if (res?.isConfirmed) {
        this.tripPlaces[timeLineIndex]?.placesItems?.forEach((element: any, index: any) => {
          if (place?.id == element?.id) {
            this.tripPlaces[timeLineIndex]?.placesItems?.splice(index, 1);
          }
        });
        let data: any = [];
        let indexNumber: any = 0;
        this.tripPlaces?.forEach((item: any, index: any) => {
          item?.placesItems?.length > 0 ? indexNumber += 1 : '';
          if (item?.placesItems?.length > 0) {
            data?.push({
              date: item?.date,
              indexNumber: indexNumber,
              placesItems: item?.placesItems
            });
          }
          if (item?.length > 0) {
            item?.forEach((el: any) => {
              this.mapLocations?.push({
                lat: el?.lat,
                lng: el?.long,
                name: el?.title,
                image: el?.image,
                address_name: el?.address_name,
                review: el?.review,
                type: el?.type,
                rate: el?.rate ? el?.rate : 0,
                slug: el?.slug
              });
            });
          }
        });
        this.tripPlaces = data;
        // if (isPlatformBrowser(this.platformId)) {
        //   window.localStorage?.setItem(keys?.saveTripData, JSON.stringify(this.staticData));
        // }
        this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.deletePlace'));
      }
    });
  }

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/places/details/', item?.slug])
    }
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}


