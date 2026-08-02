// Modules
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID, QueryList, ViewChild, ViewChildren } from '@angular/core';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { environment } from 'src/environments/environment';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { PaginatorModule } from 'primeng/paginator';
import { CarouselModule } from 'primeng/carousel';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { StoriesService } from 'src/app/services/stories.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
// Components
import { ReviewEventSliderComponent } from 'src/app/shared/components/review-event-slider/review-event-slider.component';
import { VideoModalComponent } from 'src/app/components/home-page/components/videos-slider/video-modal/video-modal.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { shotsOptions } from 'src/app/components/places/store/places-configrations';
import { GalleriaModule } from 'primeng/galleria';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { AuthService } from 'src/app/services/auth.service';
import { MessageService } from 'primeng/api';
import { catchError, finalize, tap } from 'rxjs';
import { ShareSocialComponent } from "../../../Common/component/share-social/share-social.component";
import { RateSiteComponent } from "../../../components/home-page/components/rate-site/rate-site.component";
import { EventsService } from '../../services';
import { moduleTypeRating } from 'src/app/Common/enums/module-type-rating.enum';
import { RateItemComponent } from 'src/app/Common/component/rate-place/rate-item.component';
import { Tabs2Component } from "../../../Common/layout/tabs2/tabs2.component";
import { AllTabsTypes } from 'src/app/Common/enums/details-tabs.enum';
import { MediaViewerComponent } from "../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-event-details',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    CarouselModule,
    GalleriaModule,
    RouterModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    // Components
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    ShareSocialComponent,
    RateItemComponent,
    RateSiteComponent,
    // Directives
    LazyLoadSectionDirective,
    Tabs2Component,
    MediaViewerComponent
  ],
  templateUrl: './event-details.component.html',
  styleUrls: ['./event-details.component.scss']
})
export class EventDetailsComponent {
  @ViewChildren(Tabs2Component) tabsComponents!: QueryList<Tabs2Component>;
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  currentLoginInformation: any;
  moduleTypeRating: string;

  eventDetails: any;
  isLoadingEventDetails: boolean = false;
  eventId: any;
  isLoadingTestimonialsData: boolean = false;
  testimonialsData: any = [];

  eventsList: any = [];
  isLoadingEvents: boolean = false;

  contentSection: boolean = false;
  reviewSliderSection: boolean = false;

  rate: any = 0;
  shots: any = [];
  shotsOptions: any = shotsOptions;
  isUserLoggedin: boolean = false;
  isLoadingfavouritePlace: boolean = false;
  isLoadingSaveEvent: boolean = false;
  isLoadingBtn: boolean = false;
  isLoadingReviews: boolean = false;

  markerPositions: any = []

  tabs: any;

  @ViewChild('dropdown') dropdown: any;
  fullUrl: any = null;

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private eventsService: EventsService,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    public publicService: PublicService,
    private cdr: ChangeDetectorRef,
    private router: Router,
    private authService: AuthService,
    private messageService: MessageService
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.tabs = [
      {
        type: AllTabsTypes.Description,
        title: 'places.description',
        icon: 'assets/images-v2/pages/place-details/tabs/description.svg'
      },
      {
        type: AllTabsTypes.Location,
        title: 'places.location',
        icon: `assets/images-v2/pages/place-details/tabs/location.svg`
      }
    ];
    this.isUserLoggedin = this.authService.isLoggedIn();
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.getAllEvents();
    }
    this.moduleTypeRating = moduleTypeRating?.EVENT;

    this.initPageData()
    this.eventId = this.activatedRoute?.snapshot?.params?.['id'];
    this.eventId ? this.getEventDataById(this.eventId) : '';
    // this.fullUrl = environment.publicUrl + '/events/event-details/' + this.eventId;
    this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();

    if (isPlatformBrowser(this.platformId)) {
      this.publicService?.placeCategoryDetails?.subscribe((res: any) => {
        if (res?.id) {
          this.getEventDataById(res?.id);
          window.scrollTo(0, 0);
        }
      });
    }
  }
  // Start initPageData Function
  private initPageData(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (
        JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')
          ?.user
      ) {
        this.currentLoginInformation = JSON.parse(
          window?.localStorage?.getItem(keys?.userLoginData) || '{}'
        )?.user;
      }
    }
  }
  // End initPageData Function

  getAllEvents(): void {
    this.setLoadingStateEvents(true);
    this.eventsService?.getAllEvents().subscribe(
      (res) => this.handleResponseEvents(res),
      (err) => this.handleErrorEvents(err)
    );
  }
  private setLoadingStateEvents(isLoading: boolean): void {
    this.isLoadingEvents = isLoading;
  }
  private handleResponseEvents(res: any): void {
    if (res?.code === 200) {
      this.processEvents(res?.data?.items);
      this.cdr.detectChanges();
    } else {
      this.alertsService?.openToast(
        'error',
        res?.message || 'Error fetching events'
      );
    }
    this.setLoadingStateEvents(false);
  }
  clearEvent(dropdown: any): void {
    dropdown.clear();
    this.recallNewEventDetails(null);
  }

  private handleErrorEvents(err: any): void {
    this.alertsService?.openToast('error', err || 'An error occurred');
    this.setLoadingStateEvents(false);
  }
  private processEvents(events: any[]): void {
    this.eventsList = events;
    this.setLoadingStateEvents(false);
    if (events?.length > 0) {
      events.forEach((event) => {
        this.processEvent(event);
      });
    }
  }
  private processEvent(event: any): void {
    if (event?.lat && event?.long && event?.address_type === 'map') {
      if (isPlatformBrowser(this.platformId)) {
        event['address'] = this.publicService.createGoogleMapsLink(
          event.lat,
          event.long
        );
      }
    }
    event['address_name'] = this.constructAddressName(event);
  }
  private constructAddressName(event: any): string {
    if (event?.region?.name && event?.city?.name) {
      return `${event.region.name}, ${event.city.name}`;
    } else if (event?.region?.name) {
      return event.region.name;
    } else if (event?.city?.name) {
      return event.city.name;
    }
    return '';
  }
  getEventDataById(id: any, hideLoading: boolean = false, reviewsLoading?: boolean): void {
    reviewsLoading ? this.isLoadingReviews = true : '';
    if (!id) return;
    this.setLoadingState(!hideLoading);
    this.eventsService.getEventById(id).subscribe({
      next: (res: any) => this.handleResponse(res),
      error: (err: any) => this.handleError(err),
      complete: () => console.log('Fetching event details completed.')
    });

  }
  private setLoadingState(isLoading: boolean): void {
    this.isLoadingEventDetails = isLoading;
    this.isLoadingTestimonialsData = isLoading;
  }
  private handleResponse(res: any): void {

    if (res?.code !== 200) {
      this.showAlert(res?.message);
      return;
    }

    this.eventDetails = res?.data;
    this.isLoadingReviews = false;
    if (isPlatformBrowser(this.platformId)) {
      this.markerPositions = [
        {
          lat: this.eventDetails?.lat,
          lng: this.eventDetails?.long,
          place_icon: 'assets/images/icons/location2.svg',
          icon: {
            url: this.eventDetails?.place_icon
              ? this.eventDetails?.place_icon
              : 'assets/images/icons/location2.svg',
            size: this.eventDetails?.place_icon
              ? new google.maps.Size(30, 30)
              : new google.maps.Size(50, 50),
          },
          content: {
            id: this.eventDetails?.id,
            title: this.eventDetails.title,
            location_name: this.eventDetails.address_name,
            address: this.eventDetails.address,
            rate: this.eventDetails.rate ? this.eventDetails.rate : 0,
            reviews: this.eventDetails?.review,
            icon: this.eventDetails.place_icon,
            thumbil_image: this.eventDetails.image
              ? this.eventDetails?.image
              : 'assets/images/icons/location2.svg',
          },
        },
      ];
    }
    this.cdr.detectChanges();
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.cdr.detectChanges();
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.cdr.detectChanges();
    }
    this.processEventDetails();
    this.setLoadingState(false);
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.eventDetails.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.eventDetails.title}` },
      { name: 'description', content: this.eventDetails.description },
    ]);
    this.metadataService.updateMetaTagsProperty([
      {
        property: 'og:url',
        content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/events/event-details/${this.eventDetails.slug}`,
      },
      { property: 'og:title', content: `${this.eventDetails.title}` },
      { property: 'og:description', content: this.eventDetails.description },
    ]);
    this.metadataService.setSharePreviewImage(this.eventDetails.image);
  }
  // private handleError(err: any): void {
  //   this.showAlert(err);
  //   this.setLoadingState(false);
  // }
  private processEventDetails(): void {
    if (!this.eventDetails) return;
    this.updateAddressDetails();
    this.rate = this.eventDetails?.rate ? Math.round(this.eventDetails?.rate) : 0;
    this.shots = this.eventDetails?.galleries || [];
    this.testimonialsData = this.eventDetails?.ratings || [];
    this.cdr.detectChanges();
  }
  private updateAddressDetails(): void {
    if (
      this.eventDetails?.lat &&
      this.eventDetails?.lat &&
      this.eventDetails?.address_type == 'map'
    ) {
      if (isPlatformBrowser(this.platformId)) {
        this.eventDetails['address'] = this.publicService.createGoogleMapsLink(
          this.eventDetails?.lat,
          this.eventDetails?.long
        );
      }
    }
    if (this.eventDetails?.region?.name && this.eventDetails?.city?.name) {
      this.eventDetails['address_name'] =
        this.eventDetails?.region?.name + ', ' + this.eventDetails?.city?.name;
    } else if (this.eventDetails?.region?.name) {
      this.eventDetails['address_name'] = this.eventDetails?.region?.name;
    } else if (this.eventDetails?.city?.name) {
      this.eventDetails['address_name'] = this.eventDetails?.city?.name;
    }
  }
  private showAlert(message?: string): void {
    if (message) {
      this.alertsService.openToast('error', message);
    }
  }
  checkEventStatus(dateFrom: string, dateTo: string): string {
    const today = new Date();
    const eventStartDate = new Date(dateFrom);
    const eventEndDate = new Date(dateTo);

    if (today < eventStartDate) {
      return 'soon'; // Coming Soon
    } else if (today >= eventStartDate && today <= eventEndDate) {
      return 'open'; // Open
    } else {
      return 'closed'; // Closed
    }
  }


  share(link: any): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl,
      },
      styleClass: 'rate',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    });
  }
  recallNewEventDetails(event: any): void {
    this.eventId = event?.value?.slug;
    this.router.navigate(['/events/event-details/' + this.eventId]);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo(0, 0);
    }
    this.getEventDataById(this.eventId);
  }
  openVideo(): void {
    const ref = this.dialogService.open(VideoModalComponent, {
      header: '',
      width: '90%',
      baseZIndex: 10000,
      data: {
        url_video: this.eventDetails?.video_url
          ? this.eventDetails?.video_url
          : '',
        image_video: `${this.eventDetails.image}`,
      },
      styleClass: 'video-modal',
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    });
  }
  newRatingAdded(event: any): void {
    if (event == true) {
      this.isLoadingTestimonialsData = true;
      this.getEventDataById(this.eventId, true);
    }
  }
  hide(): void {
    this.dropdown?.accessibleViewChild?.nativeElement?.blur();
  }
  // Start SendFeedbackFromEvent
  sendFeedbackFromStore(feedbackData): void {
    this.messageService.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (feedbackData) {
        this.isLoadingBtn = true;
        this.eventsService.sendFeedbackFromEvents(feedbackData).subscribe(
          (res: any) => {
            if (res?.code == 200) {
              this.isLoadingBtn = false;
              this.getEventDataById(this.eventId, true, true);
              this.alertsService?.openToast(
                'success',
                this.publicService?.translateTextFromJson('general.successRate')
              );
              if (isPlatformBrowser(this.platformId)) {
                window.scrollTo({ top: 200, behavior: 'smooth' });
              }
            } else {
              this.isLoadingBtn = false;
              res?.message
                ? this.alertsService?.openToast('error', res?.message)
                : '';
            }
          },
          (err: any) => {
            err ? this.alertsService?.openToast('error', err) : '';
            this.isLoadingBtn = false;
          }
        );
      } else {
        // this.publicService.validateAllFormFields(this.rateForm);
      }
    }
  }
  /* --- Start Save Functions --- */

  saveStore(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.isLoadingSaveEvent = true;
      this.publicService
        .isSaved(this.eventDetails?.type, this.eventDetails?.id)
        .pipe(finalize(() => (this.isLoadingSaveEvent = false)))
        .subscribe(
          (res: any) => {
            if (res.code == 200) {
              this.getEventDataById(this.eventId, true);
              const messageKey = this.eventDetails.is_saved ? 'general.removeSaved' : 'general.addSaved';
              this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
              this.eventDetails.is_saved = true;
            } else {
              this.alertsService?.openToast(
                'error',
                res?.message || 'Error occurred'
              );
            }
          },
          (err: any) => {
            this.alertsService?.openToast('error', err?.message || 'An error occurred');
          }
        );
    }
  }
  /* --- End Save Functions --- */
  onLocationTabClick(): void {
    setTimeout(() => {
      this.tabsComponents.forEach((tabs) => {
        tabs.locationTabClick(1);
        this.smoothScroll();
      });
    }, 200);
  }
  private smoothScroll() {
    if (isPlatformBrowser(this.platformId)) {
      const topValue = window.innerWidth < 990 ? 720 : 135;
      window.scrollTo({ top: topValue, behavior: 'smooth' });
    }
  }
  /* --- Start Add To Favorite Functions --- */
  addToFavorite(item: any): void {
    this.isLoadingfavouritePlace = true;
    this.messageService?.clear();
    let addToFavoriteSubscription: Subscription = this.publicService.isFavorite(item?.type, item?.id, item.is_favorite).pipe(
      tap((res: any) => {
        if (res.code == 200) {
          this.getEventDataById(this.eventId, true);
          // this.getRelatedStores(this.storeId, true);
          const messageKey = item.is_favorite ? 'general.removeFavorites' : 'general.addFavorites';
          this.alertsService?.openToast('success', this.publicService?.translateTextFromJson(messageKey));
        } else {
          this.handleError(res?.message);
        }
      }),
      catchError(err => this.handleError(err)),
      finalize(() => {
        this.isLoadingfavouritePlace = false;

      })
    ).subscribe();

    this.unsubscribe.push(addToFavoriteSubscription);
  }
  /* --- End Add To Favorite Functions --- */

  /* --- Handle api requests error messages --- */
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
    this.isLoadingEvents = false;
  }
  private setErrorMessage(message: string): void {
    // Implementation for displaying the error message, e.g., using a sweetalert
    this.alertsService?.openToast('error', message);
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
