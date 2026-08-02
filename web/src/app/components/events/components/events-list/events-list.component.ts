// Modules
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { CalendarModule } from 'primeng/calendar';
import { Subject } from 'rxjs/internal/Subject';
import { SidebarModule } from 'primeng/sidebar';
import { Router, RouterModule } from '@angular/router';
import { RatingModule } from 'primeng/rating';
import { MessageService } from 'primeng/api';
import { ToastModule } from 'primeng/toast';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { EventsService } from 'src/app/services/events.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { environment } from 'src/environments/environment';
// Components
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
// Pipes
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-events-list',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    CalendarModule,
    SidebarModule,
    RouterModule,
    RatingModule,
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
    LazyLoadSectionDirective,
    // Pipes
    StripHtmlPipe
  ],
  templateUrl: './events-list.component.html',
  styleUrls: ['./events-list.component.scss']
})
export class EventsListComponent {
  private unsubscribe: Subscription[] = [];
  private searchSubject = new Subject<any>();
  isBrowser: boolean = false;
  currentLanguage: any;

  mapLocations: any = [];

  allEventsSection = false;
  homeShowFooter = false;


  filterForm: any = this.fb.group(
    {
      eventName: [null, { validators: [Validators.required], updateOn: 'blur' }],
      date: [null, { validators: [Validators.required], updateOn: 'blur' }],
      // startDate: [null, { validators: [Validators.required], updateOn: 'change' }],
      // endDate: [null, { validators: [Validators.required], updateOn: 'change' }],
      location: [null, { validators: [Validators.required] }],
    },
  );
  get filterFormControls(): any {
    return this.filterForm?.controls;
  }
  displayFilter: boolean = false;

  addressesType: any = [];
  isLoadingAddresses: boolean = false;

  displayMonths = 2;
  navigation = 'select';
  showWeekNumbers = false;
  outsideDays = 'visible';
  hoveredDate: any | null = null;
  fromDate: any | null;
  toDate: any | null;
  formattedFromDate: any;
  formattedToDate: any;
  startDate: any;
  endDate: any;

  eventsList: any = [];
  isLoadingEvents: boolean = false;
  eventsTotalCount: number = 0;
  page: any = 1;
  perPage: any = 8;
  eventsKeyword: any = null;
  dateRange: any = null;
  addressType: any = null;
  isLoadingSearch: boolean = false;

  currentDate: string = '';
  soon: boolean = false;

  @ViewChild('paginator') paginator: Paginator | undefined;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private messageService: MessageService,
    private alertsService: AlertsService,
    private eventsService: EventsService,
    public publicService: PublicService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router,
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('eventsList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
    }
    if (isPlatformBrowser(this.platformId)) {
      this.getAllEvents();
      this.getLocationsTypes();

      this.searchSubject.pipe(debounceTime(500)).subscribe(event => {
        this.searchService(event);
      });
    } else if (isPlatformServer(this.platformId)) {
      this.metadataService.updateMetaAccordingCurrentLanguage('eventsList');
    }
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | الفعاليات `);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | الفعاليات ` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/events/list` },
      { property: 'og:title', content: `هودج | الفعاليات ` },
    ]);
  }

  getLocationsTypes(): void {
    this.addressesType = [
      { id: 1, name: 'events.online', value: 'map' },
      { id: 2, name: 'events.location', value: 'link' }
    ];
  }
  clearLocation(): void {
    this.filterForm.controls['location'].setValue(null);
    this.onChangeControl('location');
  }

  filter(): void {
    this.messageService?.clear();
    let dateRange = this.filterForm?.value?.date;
    let date: any = dateRange ? dateRange[0] && dateRange[1] ? this.publicService?.convertTimeOrDate(dateRange[0], 'date3') + '-' + this.publicService?.convertTimeOrDate(dateRange[1], 'date3') : null : '';
    this.displayFilter = false;
    let formInfo: any = this.filterForm?.value;
    if (this.filterForm?.valid) {
      this.eventsKeyword = formInfo?.eventName;
      this.addressType = formInfo?.location?.value;
      this.dateRange = date;
      this.getAllEvents();
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('validations.enterEventNameOrDateOrAddress'));
    }
  }
  onChangeControl(type?: any, geEvents?: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      if (type == 'eventName') {
        if (this.filterFormControls?.eventName?.valid) {
          this.publicService?.removeValidators(this.filterForm, ['date']);
          this.publicService?.removeValidators(this.filterForm, ['location']);
        } else {
          this.publicService?.addValidators(this.filterForm, ['date']);
          this.publicService?.addValidators(this.filterForm, ['location']);
        }
      }
      if (type == 'date') {
        let rangeDate = this.filterForm?.value?.date;
        if (this.filterFormControls?.date?.valid && rangeDate[0] && rangeDate[1]) {
          this.publicService?.removeValidators(this.filterForm, ['eventName']);
          this.publicService?.removeValidators(this.filterForm, ['location']);
        } else {
          this.publicService?.addValidators(this.filterForm, ['eventName']);
          this.publicService?.addValidators(this.filterForm, ['location']);
        }
      }
      if (type == 'location') {
        if (this.filterFormControls?.location?.valid) {
          this.publicService?.removeValidators(this.filterForm, ['eventName']);
          this.publicService?.removeValidators(this.filterForm, ['date']);
        } else {
          this.publicService?.addValidators(this.filterForm, ['eventName']);
          this.publicService?.addValidators(this.filterForm, ['date']);
        }
      }
    }

    let formInfo: any = this.filterForm?.value;
    this.eventsKeyword = formInfo?.eventName;
    this.addressType = formInfo?.location?.value;
    geEvents == false ? "" : this.getAllEvents();
  }
  onChangeDate(geEvents?: boolean): void {
    let rangeDate = this.filterForm?.value?.date;
    let date: any = rangeDate[0] && rangeDate[1] ? this.publicService?.convertTimeOrDate(rangeDate[0], 'date3') + '-' + this.publicService?.convertTimeOrDate(rangeDate[1], 'date3') : null;
    this.dateRange = date;
    this.onChangeControl('date', geEvents);
  }
  resetDate(): void {
    this.filterFormControls?.date?.reset();
    this.dateRange = null;
    this.getAllEvents();
  }
  resetForm(): void {
    this.filterForm?.reset();
    this.eventsKeyword = null;
    this.addressType = null;
    this.dateRange = null;
    this.getAllEvents();
  }

  getAllEvents(hideLoading?: boolean): void {
    this.setLoadingState(!hideLoading);
    this.eventsService?.getAllEvents(this.page, this.perPage, this.eventsKeyword, this.dateRange, this.addressType)
      .subscribe(
        res => this.handleResponse(res),
        err => this.handleError(err)
      );
  }
  private setLoadingState(isLoading: boolean): void {
    this.isLoadingEvents = isLoading;
    this.isLoadingSearch = isLoading;
  }
  private handleResponse(res: any): void {
    if (res?.code === 200) {
      this.processEvents(res?.data?.items);
      this.eventsTotalCount = res?.data?.total;
      this.cdr.detectChanges();
    } else {
      this.alertsService?.openToast('error', res?.message || 'Error fetching events');
    }
    this.setLoadingState(false);
  }
  private handleError(err: any): void {
    this.alertsService?.openToast('error', err || 'An error occurred');
    this.setLoadingState(false);
  }
  private processEvents(events: any[]): void {
    this.eventsList = events;

    if (events?.length > 0) {
      events.forEach(event => {
        this.processEvent(event);
        this.mapLocations.push(this.createMapLocation(event));
      });
    }
  }
  private processEvent(event: any): void {
    if (event?.lat && event?.long && event?.address_type === 'map') {
      event['address'] = this.publicService.createGoogleMapsLink(event.lat, event.long);
    }
    event['address_name'] = this.constructAddressName(event);
  }
  private createMapLocation(event: any): any {
    return {
      lat: event?.lat,
      lng: event?.long,
      name: event?.title,
      image: event?.image,
      address_name: event?.address_name,
      review: event?.review,
      rate: event?.rate ? event?.rate : 0
    };
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

  handleSearch(event: any): void {
    this.searchSubject.next(event);
    this.filterForm?.get('eventName')?.setValue(event);
  }
  searchService(event: any): void {
    this.eventsKeyword = event;
    this.page = 1;
    this.isLoadingSearch = true;
    this.changePageActiveNumber(this.page);
    // this.getAllEvents();
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.filterForm?.get('eventName')?.reset();
    this.page = 1;
    this.eventsKeyword = null;
    this.isLoadingSearch = true;
    this.changePageActiveNumber(this.page);
    // this.getAllEvents();
  }
  onPageChange(event: any): void {
    this.page = event?.page + 1;
    this.getAllEvents();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 730, behavior: 'smooth' });
    }
  }
  changePageActiveNumber(number: number): void {
    this.paginator?.changePage(number - 1);
  }

  showDetails(item: any): void {
    if (item?.slug) {
      this.router.navigate(['/events/event-details/' + item?.slug])
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

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
