// Modules
import { CommonModule, isPlatformBrowser, isPlatformServer, NgOptimizedImage } from '@angular/common';
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID } from '@angular/core';
import { DialogService, DynamicDialogModule } from 'primeng/dynamicdialog';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { PaginatorModule } from 'primeng/paginator';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';

// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { TripsService } from 'src/app/services/trips.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { environment } from 'src/environments/environment';
// Components
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { ShowTripMapModalComponent } from 'src/app/components/my-trips/components/show-trip-map-modal/show-trip-map-modal.component';
import { CreateTripModalComponent } from 'src/app/components/my-trips/components/create-trip-modal/create-trip-modal.component';
import { AddAnotherPlaceComponent } from 'src/app/components/my-trips/components/add-another-place/add-another-place.component';
import { SaveTripModalComponent } from 'src/app/components/my-trips/components/save-trip-modal/save-trip-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { LoginComponent } from 'src/app/components/authentication/components/login/login.component';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { LoginPopupComponent } from 'src/app/components/authentication/components/login-popup/login-popup.component';

@Component({
  selector: 'app-my-trip-details',
  standalone: true,
  imports: [
    // Modules
    DynamicDialogModule,
    ReactiveFormsModule,
    RatingModule,
    PaginatorModule,
    TranslateModule,
    RouterModule,
    RatingModule,
    NgOptimizedImage,
    CommonModule,
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
    LazyLoadSectionDirective
  ],
  templateUrl: './my-trip-details.component.html',
  styleUrls: ['./my-trip-details.component.scss']
})
export class MyTripDetailsComponent {
  private unsubscribe: Subscription[] = [];
  currentLoginInformation: any;
  currentLanguage: string = '';
  isLoading: boolean = false;
  tripId: any;
  tripData: any = [];
  tripPlaces: any = [];
  finalPlaces: any;
  currentLang: any;
  allDates: any = [];
  mapLocations: any = [];
  collapsedMenu: boolean = false;
  fullUrl: any = null;
  notShowPage: boolean = false;

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    private tripsService: TripsService,
    private cdr: ChangeDetectorRef,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.initializeBrowserSpecificData();
    this.tripId = this.activatedRoute?.snapshot?.params?.['id'];
    // if (this.userIsNotLoggedIn()) {
    //   this.handleUserNotLoggedIn();
    // } else {
    //   this.getTripDetails(this.tripId);
    // }

    this.getTripDetails(this.tripId);
    this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
  }
  // Helper method to initialize browser-specific data
  private initializeBrowserSpecificData(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.currentLoginInformation = this.retrieveUserLoginData();
    }
  }
  // Helper method to retrieve user login data from local storage
  private retrieveUserLoginData(): any {
    if (isPlatformBrowser(this.platformId)) {
      return JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')?.user;
    }
  }
  // Helper method to check if the user is not logged in
  private userIsNotLoggedIn(): boolean {
    return this.currentLoginInformation === undefined;
  }
  // Helper method to handle the case when the user is not logged in
  private handleUserNotLoggedIn(): void {
    this.notShowPage = true;
    const loginDialog = this.openLoginDialog();
    loginDialog?.onClose?.subscribe((result: any) => {
      if (isPlatformBrowser(this.platformId) && result?.isLogin) {
        window.location.reload();
      }
    });
  }
  // Helper method to open the login dialog
  private openLoginDialog(): any {
    return this?.dialogService?.open(LoginPopupComponent, {
      width: '60%',
      height: '700px', styleClass: 'auth-dialog',
    });
  }

  addDaysToDate(date: any, days: any): any {
    const daysToAdd = parseInt(days, 10);
    const inputDate = new Date(date); // Convert the input string to a Date object
    inputDate.setDate(inputDate.getDate() + daysToAdd); // Add the specified number of days
    const newDateStr = inputDate.toISOString().split('T')[0]; // Format the new date as desired
    return newDateStr;
  }

  getTripDetails(id: any): void {
    this.isLoading = true;
    const handleError = (error: any) => {
      this.alertsService?.openToast('error', error || '');
      this.isLoading = false;
      this.handleNoTripData();
    };

    let tripSubscribe = this.tripsService?.getTripById(id)?.subscribe(
      (res: any) => {
        if (res?.code === 200) {
          if (isPlatformBrowser(this.platformId)) {
            this.handleSuccessfulResponse(res);
            this.cdr.detectChanges();
          }
          if (isPlatformServer(this.platformId)) {
            this.handleSuccessfulResponse(res);
            this.cdr.detectChanges();
          }
        } else {
          handleError(res?.message);
        }
      },
      (err: any) => {
        handleError(err);
      }
    );
    this.unsubscribe.push(tripSubscribe);
  }
  // Helper method to handle successful API response
  private handleSuccessfulResponse(response: any): void {
    this.tripData = response?.data;
    this.updateMetaTags();
    this.tripData['startTime'] = this.tripData?.date;
    this.tripData['endTime'] = this.addDaysToDate(this.tripData?.date, this.tripData?.days);
    this.tripData['startCity'] = this.tripData?.places[0][0]?.city?.name;
    this.tripData['endCity'] = this.tripData?.places[this.tripData?.places.length - 1][0]?.city?.name;
    this.processTripPlaces(response?.data?.places);
    this.setupDateRange();
    this.isLoading = false;
  }
  // Helper method to process and update trip places
  private processTripPlaces(places: any): void {
    places?.forEach((item: any) => {
      const placesItems = item?.map((onePlace: any) => {
        onePlace['address_name'] = this.getAddressName(onePlace);
        return onePlace;
      });
      this.tripPlaces?.push({ placesItems });
      if (item?.length > 0) {
        this.mapLocations?.push(...this.createMapLocations(item));
      }
    });
  }
  // Helper method to create map locations array
  private createMapLocations(item: any): any[] {
    return item?.map((el: any) => ({
      lat: el?.lat,
      lng: el?.long,
      name: el?.title,
      image: el?.image,
      address_name: el?.address_name,
      address: el?.address,
      review: el?.review,
      type: el?.type,
      rate: el?.rate ? el?.rate : 0,
      slug: el?.slug
    }));
  }
  // Helper method to set up date range and update trip places with dates
  private setupDateRange(): void {
    const dateRange = this.tripData.trip.date.replaceAll('-', '/') + ' - ' +
      this.publicService.addDays(this.tripData.trip.date, +this.tripData.trip.days).replaceAll('-', '/');
    this.tripData['daterange'] = dateRange;
    this.allDates = this.publicService.getDateArrayFromDateRange(this.tripData?.daterange);
    this.updateTripPlacesWithDates();
  }
  // Helper method to update trip places with dates
  private updateTripPlacesWithDates(): void {
    this.allDates?.forEach((date: any, dateIndex: any) => {
      this.tripPlaces?.forEach((place: any, placeIndex: any) => {
        if (dateIndex == placeIndex) {
          place['date'] = date;
        }
      });
    });
  }
  // Helper method to get the formatted address name
  private getAddressName(onePlace: any): string {
    if (onePlace?.region?.name && onePlace?.city?.name) {
      return onePlace?.region?.name + ', ' + onePlace?.city?.name;
    } else if (onePlace?.region?.name) {
      return onePlace?.region?.name;
    } else if (onePlace?.city?.name) {
      return onePlace?.city?.name;
    }
    return '';
  }
  // Helper method to handle scenarios when there is no trip data
  private handleNoTripData(): void {
    if (this.tripData?.length === 0) {
      this.notShowPage = true;
      const ref = this?.dialogService?.open(CreateTripModalComponent, {
        width: '35%',
        closable: false
      });
    } else {
      this.notShowPage = false;
    }
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.tripData?.trip?.name}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.tripData?.trip?.name}` },
      { name: 'description', content: this.tripData?.trip?.name },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stories/${this.tripData?.trip?.slug}` },
      { property: 'og:title', content: `${this.tripData?.trip?.name}` },
      { property: 'og:description', content: this.tripData?.trip?.name },
    ]);
    this.metadataService.setSharePreviewImage(`${environment.imageBaseUrl}/front_assets/imgs/logo.svg`);
  }

  // Save Trip
  saveTrip(): void {
    const ref = this?.dialogService?.open(SaveTripModalComponent, {
      width: '35%',
      styleClass: 'auth-dialog confirm-delete-trip',
      data: { id: this.tripId, finalPlaces: this.tripData }
    });
    ref?.onClose?.subscribe((res: any) => {
      if (isPlatformBrowser(this.platformId)) {
        this.publicService?.toggleBodyScroll(true);
      }
      if (res?.isSave) {
        this.router?.navigate(['/trips/list']);
      }
    });
  }
  // Open Map
  showMap(): void {
    const ref = this?.dialogService?.open(ShowTripMapModalComponent, {
      width: '100%',
      height: '85vh',
      styleClass: 'show-map',
      maskStyleClass: 'align-items-end',
      data: this.mapLocations
    });
    ref?.onClose?.subscribe((res: any) => {
      if (isPlatformBrowser(this.platformId)) {
        this.publicService?.toggleBodyScroll(true);
      }
    });
  }
  // Add Place
  addAnotherPlace(): void {
    const ref = this?.dialogService?.open(AddAnotherPlaceComponent, {
      width: '50%',
      data: { id: this.tripId, allDates: this.allDates },
      header: this.publicService?.translateTextFromJson('saveTrip.addPlaces')
    });
    ref?.onClose?.subscribe((res: any) => {
      if (isPlatformBrowser(this.platformId)) {
        this.publicService?.toggleBodyScroll(true);
      }
    });
  }

  // Delete Trip
  deleteTrip(): void {
    const confirmationDialog = this.openConfirmationDialog();
    confirmationDialog?.onClose?.subscribe((result: any) => {
      if (result?.isConfirmed) {
        this.handleDeleteConfirmation();
      }
    });
  }
  // Helper method to open the confirmation dialog
  private openConfirmationDialog(): any {
    return this?.dialogService?.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService?.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
    });
  }
  // Helper method to handle the delete confirmation
  private handleDeleteConfirmation(): void {
    let deleteTripSubscribe = this.tripsService?.deleteTrip(this.tripId)?.subscribe(
      (response: any) => this.handleDeleteSuccess(response),
      (error: any) => this.handleDeleteError(error)
    );
    this.unsubscribe.push(deleteTripSubscribe);
  }
  // Helper method to handle successful trip deletion
  private handleDeleteSuccess(response: any): void {
    if (response?.code === 200) {
      this.publicService?.show_loader?.next(false);
      this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.deleteTrip'));
      this.router?.navigate(['/trips/list']);
    } else {
      this.handleDeleteError(response);
    }
  }
  // Helper method to handle trip deletion errors
  private handleDeleteError(error: any): void {
    if (error) {
      this.alertsService?.openToast('error', error?.message || '');
    }
    this.publicService?.show_loader?.next(false);
  }

  // Share
  share(): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl
      },
      styleClass: 'rate'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    })
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
