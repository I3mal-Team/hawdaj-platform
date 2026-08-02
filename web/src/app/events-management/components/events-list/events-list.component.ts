// Modules
import { ChangeDetectorRef, Component, inject, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
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
// import { EventsService } from 'src/app/services/events.service';
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
import { SearchListComponent } from 'src/app/Common/layout/search-list/search-list.component';
import { SearchListSmComponent } from 'src/app/Common/layout/search-list-sm/search-list-sm.component';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { SharedPaginationComponent } from 'src/app/Common/layout/shared-pagination/shared-pagination.component';
import { NoResultComponent } from 'src/app/Common/layout/no-result/no-result.component';
import { ListCardComponent } from 'src/app/Common/component/list-card/list-card.component';
import { ListSliderComponent } from 'src/app/Common/component/list-card/list-slider/list-slider.component';
import { AllInputTypes } from 'src/app/Common/enums/all-input-types.enum';
import { EventsService } from '../../services';

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
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    SearchListComponent,
    BannerComponent,
    SharedPaginationComponent,
    NoResultComponent,
    ListCardComponent,
    ListSliderComponent,
    // Directives
    LazyLoadSectionDirective,
    SearchListSmComponent
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

  private localizationLanguageService = inject(LocalizationLanguageService);
  private metadataService = inject(MetadataService);
  private messageService = inject(MessageService);
  private alertsService = inject(AlertsService);
  private eventsService = inject(EventsService);
  public publicService = inject(PublicService);
  private cdr = inject(ChangeDetectorRef);
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private platformId = inject(PLATFORM_ID);

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
  perPage: any = 12;
  eventsKeyword: any = null;
  dateRange: any = null;
  addressType: any = null;
  isLoadingSearch: boolean = false;

  currentDate: string = '';
  soon: boolean = false;
  searchFields: any;

  @ViewChild('paginator') paginator: Paginator | undefined;


  ngOnInit(): void {
    this.searchFields = [
      {
        type: AllInputTypes.Text,
        name: 'placeName',
        label: 'placeholder.eventName',
        placeholder: 'events.whichEvent',
        icon: 'assets/images-v2/pages/Home/quick-search/new-search-icon.webp',
        smIcon: 'assets/images/icons/placeName.svg',
        widthClass: 'col-4 px-3 input-search-result border-end',
        validation: []
      },
      {
        type: AllInputTypes.DateRange,
        name: 'date',
        label: 'placeholder.selectDate',
        placeholder: 'events.whenYou',
        icon: 'assets/images-v2/pages/Home/quick-search/calender.svg',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: false,
        widthClass: 'col-4 ps-4 input-search-result border-end',
        validation: []
      },
      {
        type: AllInputTypes.Select,
        name: 'type',
        label: 'placeholder.locType',
        placeholder: 'events.howYou',
        listValues: this.addressesType,
        icon: 'assets/images-v2/pages/Home/quick-search/new-location-icon.webp',
        smIcon: 'assets/images/icons/city.svg',
        isLoading: false,
        widthClass: 'col-4 ps-4 input-search-result',
        validation: []
      }
    ];
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
      { id: 1, name: this.publicService?.translateTextFromJson('events.online'), value: 'map' },
      { id: 2, name: this.publicService?.translateTextFromJson('events.location'), value: 'link' }
    ];
    this.searchFields[2].listValues = this.addressesType;
    this.searchFields[2].isLoading = false;
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

    this.eventsService?.getAllEvents({
      page: this.page,
      per_page: this.perPage,
      search: this.eventsKeyword,
      daterange: this.dateRange,
      address_type: this.addressType
    }).subscribe({
      next: res => this.handleResponse(res),
      error: err => this.handleError(err),
    });

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
  search(event?: any): void {
    this.messageService?.clear();
    this.eventsKeyword = event?.value?.placeName;
    this.addressType = event?.value?.type?.value;

    if (event?.value?.date && Array.isArray(event?.value?.date) && event.value.date.length >= 2) {
      this.dateRange = this.publicService?.convertTimeOrDate(event.value.date[0], 'date3') +
        '-' +
        this.publicService?.convertTimeOrDate(event.value.date[1], 'date3');
    } else {
      this.dateRange = null;
    }

    this.getAllEvents(false);

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 300, behavior: 'smooth' });
    } else {
      this.alertsService?.openToast(
        'info',
        this.publicService?.translateTextFromJson('validations.enterPlaceNameOrRegionOrCategory'),
        'search'
      );
      this.publicService?.validateAllFormFields(event);
    }
  }


  searchService(event: any): void {
    this.eventsKeyword = event;
    this.page = 1;
    this.isLoadingSearch = true;
    this.changePageActiveNumber(this.page);
    // this.getAllEvents();
  }
  clearSearch(event: any): void {
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
  changePage(direction: number) {
    if (direction === -1 && this.page > 1) {
      this.page--;
    } else if (direction === 1 && this.page < Math.ceil(this.eventsTotalCount / this.perPage)) {
      this.page++;

    }
    this.getAllEvents();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }

  handleViewAll() {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
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
