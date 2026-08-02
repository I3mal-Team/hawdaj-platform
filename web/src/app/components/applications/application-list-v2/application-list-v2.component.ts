import { ChangeDetectorRef, Component, Inject, OnDestroy, OnInit, PLATFORM_ID, ViewChild } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer, NgOptimizedImage } from '@angular/common';
import { Paginator, PaginatorModule } from 'primeng/paginator';
import { finalize, catchError, debounceTime, distinctUntilChanged } from 'rxjs/operators';
import { of, Subject, Subscription } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AlertsService } from 'src/app/services/alerts.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { ToastModule } from 'primeng/toast';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { TranslateModule } from '@ngx-translate/core';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { RouterModule } from '@angular/router';
import { LazyLoadDirective } from 'src/app/shared/directives/lazy-load.directive';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { ApplicationCardComponent } from '../application-card/application-card.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ApplicationsService } from '../applications.service';
import { MultiSelectModule } from 'primeng/multiselect';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SidebarModule } from 'primeng/sidebar';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { TabsComponent } from 'src/app/Common/layout/tabs/tabs.component';
import { NoResultComponent } from 'src/app/Common/layout/no-result/no-result.component';
import { ApplicationCardV2Component } from "../application-card-v2/application-card-v2.component";
import { SharedPaginationComponent } from 'src/app/Common/layout/shared-pagination/shared-pagination.component';

@Component({
  selector: 'app-application-list-v2',
  standalone: true,
  imports: [
    CommonModule,
    PaginatorModule,
    MultiSelectModule,
    HeaderComponent,
    ToastModule,
    SidebarModule,
    TranslateModule,
    SkeletonComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterModule,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    BannerComponent,
    TabsComponent,
    NoResultComponent,
    ApplicationCardV2Component,
    SharedPaginationComponent
  ],
  templateUrl: './application-list-v2.component.html',
  styleUrls: ['./application-list-v2.component.scss']
})
export class ApplicationListV2Component implements OnInit, OnDestroy {
  private unsubscribe: Subscription[] = [];

  applicationsList: any[] = [];
  applicationsTotalCount = 0;
  isLoadingApplicationsList: boolean = false;

  appsPageNumber = 1;
  appsPerPageCount = 6;
  searchKeywords: string | null = null;

  isChangePage: boolean = false;

  // Start Hero Section Variables
  search: any = null;
  isLoadingFilteration: boolean = false;
  private searchSubject = new Subject<any>();
  // End Hero Section Variables

  // Start Applications Categories Variables
  applicationsCategories: any = [];
  isLoadingApplicationsCategories: boolean = false;
  selectedCategories: any = [];
  applicationsCategoriesIds: any = [];
  // End Applications Categories Variables

  applicationsSection: boolean = false;
  homeShowFooter: boolean = false;
  searchValue: string | null = null;

  @ViewChild('paginatorApplicationsList') paginatorApplicationsList: Paginator | undefined;

  displaySearch: boolean = false;
  searchSection: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    private _ApplicationsService: ApplicationsService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private alertsService: AlertsService,
    private cdr: ChangeDetectorRef
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.metadataService.updateMetaAccordingCurrentLanguage('appsList');
      this.loadData();
      // Set up the debounce time for the search
      this.searchSubject
        .pipe(
          debounceTime(500), // Adjust debounce time if needed
          distinctUntilChanged() // Ensure the value has changed before emitting
        )
        .subscribe(event => {
          this.searchAppsList(event); // Call the search method with the debounced value
        });
    }
    if (isPlatformServer(this.platformId)) {
      this.metadataService.updateMetaAccordingCurrentLanguage('appsList');
    }
  }
  loadData(): void {
    this.getPopularApplicationsList();
    this.getApplicationsCategoriesList();
  }

  getPopularApplicationsList(): void {
    this.isLoadingApplicationsList = true;
    this._ApplicationsService.getAll(this.appsPageNumber, this.appsPerPageCount, this.searchKeywords, this.applicationsCategoriesIds)
      .pipe(
        finalize(() => {
          this.isLoadingApplicationsList = false;
          this.isLoadingFilteration = false;
          if (isPlatformBrowser(this.platformId)) {
            this.cdr.detectChanges();
          }
        }),
        catchError((error) => {
          if (isPlatformBrowser(this.platformId)) {
            this.alertsService.openToast('error', error?.message || 'Error fetching applications');
          }
          return of([]);
        })
      )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.applicationsList = res?.data?.items || [];
          this.applicationsTotalCount = res?.data?.total || 0;
        } else {
          if (isPlatformBrowser(this.platformId)) {
            this.alertsService.openToast('error', res?.message || 'Error fetching applications');
          }
        }
      });
  }

  onPageChangePopularApps(event: any): void {
    this.appsPageNumber = event.page + 1;
    this.getPopularApplicationsList();
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 730, behavior: 'smooth' });
    }
  }
  changePageActiveNumber(number: number): void {
    this.isChangePage = true;
    this.paginatorApplicationsList?.changePage(number - 1);
  }

  // Start Filteration Section Functions
  handleAppsListSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchAppsList(event: any): void {
    this.search = event;
    this.searchKeywords = this.search;
    this.appsPageNumber = 1;
    this.isLoadingFilteration = true;
    this.getPopularApplicationsList();
  }
  clearAppsListSearchValue(event: any): void {
    (event && event.value) ? event.value = '' : '';
    this.appsPageNumber = 1;
    this.search = null;
    this.searchValue = null;
    this.searchKeywords = this.search;
    this.isLoadingFilteration = true;
    this.getPopularApplicationsList();
  }
  // End Filteration Section Functions

  // Start Applications Categories Functions
  getApplicationsCategoriesList(): void {
    this.isLoadingApplicationsCategories = true;

    this._ApplicationsService.getCategories()
      .pipe(
        catchError((error) => {
          this.showToast('error', error?.message || 'Error fetching applications');
          return of([]);
        }),
        finalize(() => {
          this.isLoadingApplicationsCategories = false;
          this.isLoadingFilteration = false;
          this.detectChangesIfBrowser();
        })
      )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.applicationsCategories = res?.data || [];
          console.log(this.applicationsCategories);
        } else {
          this.showToast('error', res?.message || 'Error fetching applications');
        }
      });
  }

  private showToast(type: 'error' | 'success', message: string): void {
    if (isPlatformBrowser(this.platformId)) {
      this.alertsService.openToast(type, message);
    }
  }

  private detectChangesIfBrowser(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.cdr.detectChanges();
    }
  }

  changePage(direction: number) {
    if (direction === -1 && this.appsPageNumber > 1) {
      this.appsPageNumber--;
    } else if (direction === 1 && this.appsPageNumber < Math.ceil(this.applicationsTotalCount / this.appsPerPageCount)) {
      this.appsPageNumber++;

    }
    this.isChangePage ? '' : this.getPopularApplicationsList();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }
  onSelectedItemsChange(selectedItems: any[]): void {
    let arr: any[] = [];
    selectedItems.forEach(item => {
      arr.push(item.id);
    });
    this.applicationsCategoriesIds = arr;
    this.isLoadingFilteration = true;
    this.appsPageNumber = 1;
    this.getPopularApplicationsList();

  }
  onCategoryChange(event: any): void {
    console.log(event)

  }
  resetCategories(): void {
    this.applicationsCategories?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.selectedCategories = [];
    this.applicationsCategoriesIds = [];
    this.isLoadingFilteration = true;
    this.appsPageNumber = 1;
    this.getPopularApplicationsList();
  }
  // End Applications Categories Functions

  closeFilterItems(): void {
    this.displaySearch = false;
  }
  // Function to be called on ngModelChange
  onSearchInputChange(value: string): void {
    this.searchSubject.next(value);
  }

  ngOnDestroy(): void {
    this.searchSubject.unsubscribe();
    this.unsubscribe.forEach((sb) => sb?.unsubscribe());
  }
}
