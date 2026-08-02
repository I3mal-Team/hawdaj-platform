// Modules
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { Component, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { Subscription } from 'rxjs/internal/Subscription';
import { Router, RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { Subject } from 'rxjs/internal/Subject';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
import { MultiSelectModule } from 'primeng/multiselect';


// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { TripsService } from 'src/app/services/trips.service';
import { AuthService } from 'src/app/services/auth.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { environment } from 'src/environments/environment';
// Components
import { ConfirmDeleteTripComponent } from 'src/app/components/my-trips/components/confirm-delete-trip/confirm-delete-trip.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-my-trips-list',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    RouterModule,
    RatingModule,
    CommonModule,
    FormsModule,
    ToastModule,
    MultiSelectModule,
    // Components
    OverlayLoadingComponent,
    ScrollTopComponent,
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective

  ],
  templateUrl: './my-trips-list.component.html',
  styleUrls: ['./my-trips-list.component.scss']
})
export class MyTripsListComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;
  private searchSubject = new Subject<any>();

  tripsList: any = [];
  isLoadingTrips: boolean = false;
  totalTripsCount: number = 0;
  page: any = 1;
  perPage: any = 8;
  keyword: any = null;
  isLoadingSearch: boolean = false;
  @ViewChild('paginator') paginator: Paginator | undefined;

  selectedType!: any;

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private tripsService: TripsService,
    private authService: AuthService,
    private router: Router
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
    }
    this.getAllTrips();

    this.searchSubject.pipe(debounceTime(750)).subscribe(event => {
      this.searchService(event);
    });
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | الرحلات`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | الرحلات` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/places` },
      { property: 'og:title', content: `هودج | الرحلات` },
    ]);
    this.metadataService.setSharePreviewImage(null);
  }
  addDaysToDate(date: any, days: any): any {
    const daysToAdd = parseInt(days, 10);
    const inputDate = new Date(date); // Convert the input string to a Date object
    inputDate.setDate(inputDate.getDate() + daysToAdd); // Add the specified number of days

    const newDateStr = inputDate.toISOString().split('T')[0]; // Format the new date as desired
    return newDateStr;
  }

  getAllTrips(hideFullLoading?: boolean): void {
    if (isPlatformBrowser(this.platformId)) {
      hideFullLoading ? '' : this.isLoadingTrips = true;
      this.tripsService?.getAllTrips(this.page, this.perPage, this.keyword)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.tripsList = res?.data?.trips?.items;
            this.tripsList.forEach(element => {
              if (element?.date && element?.days) {
                element['startTime'] = element?.date;
                element['endTime'] = this.addDaysToDate(element?.date, element?.days);
              }
            });
            this.totalTripsCount = res?.data?.trips?.total;
          } else {
            this.isLoadingTrips = false;
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
          }
          this.isLoadingTrips = false;
          this.isLoadingSearch = false;
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err?.message) : '';
          this.isLoadingTrips = false;
          this.isLoadingSearch = false;
        }
      );
    }
  }
  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchService(event: any): void {
    this.keyword = event;
    this.page = 1;
    this.isLoadingSearch = true;
    this.getAllTrips();
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.page = 1;
    this.keyword = null;
    this.isLoadingSearch = true;
    this.getAllTrips();
  }
  onPageChange(event: any): void {
    this.page = event?.page + 1;
    this.getAllTrips();
  }
  changePageActiveNumber(number: number): void {
    this.paginator?.changePage(number - 1);
  }
  exploreTrip(item?: any): void {
    if (item?.token) {
      this.router?.navigate(['/trips/trip-details/' + item?.token]);
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('general.invalidTripData'));
    }
  }
  deleteTrip(item?: any): void {
    const ref = this?.dialogService?.open(ConfirmDeleteTripComponent, {
      width: '35%',
      header: this.publicService?.translateTextFromJson('general.confirmDelete'),
      styleClass: 'auth-dialog confirm-delete-trip',
    });
    ref?.onClose?.subscribe((res: any) => {
      if (res.isConfirmed) {
        if (isPlatformBrowser(this.platformId)) {
          this.publicService?.show_loader?.next(true);
          this.tripsService?.deleteTrip(item?.id || item?.token)?.subscribe(
            (res: any) => {
              if (res?.code == 200) {
                this.page = 1;
                this.changePageActiveNumber(this.page);
                this.getAllTrips(true);
                this.publicService?.show_loader?.next(false);
                res?.message ? this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.deleteTrip')) : '';
              } else {
                res?.message ? this.alertsService?.openToast('error', res?.message) : '';
                this.publicService?.show_loader?.next(false);
              }
            },
            (err: any) => {
              err ? this.alertsService?.openToast('error', err) : '';
              this.publicService?.show_loader?.next(false);
            }
          );
        }
      }
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

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
