// Modules
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { CommonModule, isPlatformBrowser } from '@angular/common';
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
import { EventsService } from 'src/app/services/events.service';
import { GalleriaModule } from 'primeng/galleria';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { stripHtmlAndClamp } from 'src/app/Common/functions/html.util';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';
import { SafeHtmlPipe } from 'src/app/Common/pipes/safe-html.pipe';

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
    ReviewEventSliderComponent,
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    FooterComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    // Pipes
    StripHtmlPipe,
    SafeHtmlPipe
  ],
  templateUrl: './event-details.component.html',
  styleUrls: ['./event-details.component.scss']
})
export class EventDetailsComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

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
    private router: Router
  ) {
    this.localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }

    this.getAllEvents();
    this.eventId = this.activatedRoute?.snapshot?.params?.['id'];
    if (this.eventId) {
      this.getEventDataById(this.eventId);
    }

    this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();

    this.publicService?.placeCategoryDetails?.subscribe((res: any) => {
      if (res?.id) {
        this.getEventDataById(res?.id);
        if (isPlatformBrowser(this.platformId)) {
          window.scrollTo(0, 0);
        }
      }
    });
  }

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
      this.alertsService?.openToast('error', res?.message || 'Error fetching events');
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
      event['address'] = this.publicService.createGoogleMapsLink(event.lat, event.long);
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

  getEventDataById(id: any, hideLoading: boolean = false): void {
    if (!id) return;
    this.setLoadingState(!hideLoading);
    this.eventsService.getEventById(id).subscribe(
      (res: any) => this.handleResponse(res),
      (err: any) => this.handleError(err)
    );
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

    // ✅ Safe Meta Update for both SSR and Browser
    this.updateMetaTags();

    this.processEventDetails();
    this.setLoadingState(false);
  }

  private updateMetaTags(): void {
    if (!this.eventDetails) return;

    const description = stripHtmlAndClamp(this.eventDetails.description);

    this.metadataService.updateTitle(`${this.eventDetails.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.eventDetails.title}` },
      { name: 'description', content: description },
    ]);
    this.metadataService.updateMetaTagsProperty([
      {
        property: 'og:url',
        content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/events/event-details/${this.eventDetails.slug}`,
      },
      { property: 'og:title', content: `${this.eventDetails.title}` },
      { property: 'og:description', content: description },
    ]);
    this.metadataService.setSharePreviewImage(this.eventDetails.image);
  }

  private handleError(err: any): void {
    this.showAlert(err);
    this.setLoadingState(false);
  }

  private processEventDetails(): void {
    if (!this.eventDetails) return;
    this.updateAddressDetails();
    this.rate = this.eventDetails?.rate ? Math.round(this.eventDetails?.rate) : 0;
    this.shots = this.eventDetails?.galleries || [];
    this.testimonialsData = this.eventDetails?.ratings || [];
  }

  private updateAddressDetails(): void {
    if (this.eventDetails?.lat && this.eventDetails?.long && this.eventDetails?.address_type === 'map') {
      this.eventDetails['address'] = this.publicService.createGoogleMapsLink(
        this.eventDetails?.lat,
        this.eventDetails?.long
      );
    }

    if (this.eventDetails?.region?.name && this.eventDetails?.city?.name) {
      this.eventDetails['address_name'] = `${this.eventDetails.region.name}, ${this.eventDetails.city.name}`;
    } else if (this.eventDetails?.region?.name) {
      this.eventDetails['address_name'] = this.eventDetails.region.name;
    } else if (this.eventDetails?.city?.name) {
      this.eventDetails['address_name'] = this.eventDetails.city.name;
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

    if (today < eventStartDate) return 'soon';
    if (today >= eventStartDate && today <= eventEndDate) return 'open';
    return 'closed';
  }

  share(link: any): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: { link: this.fullUrl },
      styleClass: 'rate',
    });
    ref.onClose.subscribe();
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
        url_video: this.eventDetails?.video_url || '',
        image_video: `${this.eventDetails.image}`,
      },
      styleClass: 'video-modal',
    });
    ref.onClose.subscribe();
  }

  newRatingAdded(event: any): void {
    if (event === true) {
      this.isLoadingTestimonialsData = true;
      this.getEventDataById(this.eventId, true);
    }
  }

  hide(): void {
    this.dropdown?.accessibleViewChild?.nativeElement?.blur();
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
