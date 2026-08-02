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
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { MultiSelectModule } from 'primeng/multiselect';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SidebarModule } from 'primeng/sidebar';
import { TourGuideCardComponent } from '../tour-guide-card/tour-guide-card.component';
import { TourGuidesService } from '../tour-guides.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { PlacesService } from 'src/app/services/places.service';

@Component({
  selector: 'app-tour-guides-list',
  standalone: true,
  imports: [
    CommonModule,
    PaginatorModule,
    MultiSelectModule,
    HeaderComponent,
    ScrollTopComponent,
    ToastModule,
    SidebarModule,
    OverlayLoadingComponent,
    TranslateModule,
    SkeletonComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterModule,
    TourGuideCardComponent,
    NewFooterComponent,
    // Directives
    LazyLoadSectionDirective,
    LazyLoadDirective,
    NgOptimizedImage
  ],
  templateUrl: './tour-guides-list.component.html',
  styleUrls: ['./tour-guides-list.component.scss']
})
export class TourGuidesListComponent {
  private unsubscribe: Subscription[] = [];

  itemsList: any[] = [];
  listTotalCount = 0;
  isLoadingList: boolean = false;

  pageNumber = 1;
  appsPerPageCount = 8;
  searchKeywords: string | null = null;

  isChangePage: boolean = false;

  // Start Hero Section Variables
  search: any = null;
  isLoadingFilteration: boolean = false;
  private searchSubject = new Subject<any>();
  // End Hero Section Variables

  // Start Regions Variables
  regionsItems: any = [];
  isLoadingRegions: boolean = false;
  selectedRegions: any = [];
  regionsItemsIds: any = [];
  // End Regions Variables

  // Start Languages Variables
  languagesItems: any = [];
  isLoadingLanguages: boolean = false;
  selectedLanguages: any = [];
  languagesItemsIds: any = [];
  // End Languages Variables

  experience: number | null = null;
  experienceValue: number | null = null;

  tourGuidesSection: boolean = false;
  homeShowFooter: boolean = false;
  searchValue: string | null = null;

  @ViewChild('paginatorList') paginatorList: Paginator | undefined;

  displaySearch: boolean = false;
  searchSection: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    private _PlacesService: PlacesService,
    private _TourGuidesService: TourGuidesService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private alertsService: AlertsService,
    private cdr: ChangeDetectorRef
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.metadataService.updateMetaAccordingCurrentLanguage('tourGuides');
      this.loadData();
      // Set up the debounce time for the search
      this.searchSubject
        .pipe(
          debounceTime(500), // Adjust debounce time if needed
          distinctUntilChanged() // Ensure the value has changed before emitting
        )
        .subscribe(event => {
          this.searchList(event); // Call the search method with the debounced value
        });
    }
    if (isPlatformServer(this.platformId)) {
      this.metadataService.updateMetaAccordingCurrentLanguage('tourGuides');
    }
  }
  loadData(): void {
    this.getTourGuidesList();
    this.getRegionsList();
    this.getLanguagesList();
  }

  getTourGuidesList(): void {
    this.isLoadingList = true;
    this._TourGuidesService.getAll(this.pageNumber, this.appsPerPageCount, this.searchKeywords, this.regionsItemsIds, this.languagesItemsIds, this.experienceValue)
      .pipe(
        finalize(() => {
          this.isLoadingList = false;
          this.isLoadingFilteration = false;
          this.cdr.detectChanges();
        }),
        catchError((error) => {
          this.alertsService.openToast('error', error?.message || 'Error fetching data');
          return of([]);
        })
      )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.itemsList = res?.data?.items || [];
          this.listTotalCount = res?.data?.total || 0;
        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching data');
        }
      });
  }

  onPageChangeList(event: any): void {
    this.pageNumber = event.page + 1;
    this.getTourGuidesList();
  }
  changePageActiveNumber(number: number): void {
    this.isChangePage = true;
    this.paginatorList?.changePage(number - 1);
  }

  // Start Filteration Section Functions
  handleListSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchList(event: any): void {
    this.search = event;
    this.searchKeywords = this.search;
    this.pageNumber = 1;
    this.isLoadingFilteration = true;
    this.getTourGuidesList();
  }
  clearListSearchValue(event: any): void {
    (event && event.value) ? event.value = '' : '';
    this.pageNumber = 1;
    this.search = null;
    this.searchValue = null;
    this.searchKeywords = this.search;
    this.isLoadingFilteration = true;
    this.getTourGuidesList();
  }
  // End Filteration Section Functions

  // Start Regions Functions
  getRegionsList(): void {
    this.isLoadingRegions = true;
    this._PlacesService.getRegions().pipe(
      finalize(() => {
        this.isLoadingRegions = false;
        this.isLoadingFilteration = false;
        this.cdr.detectChanges();
      }),
      catchError((error) => {
        this.alertsService.openToast('error', error?.message || 'Error fetching regions');
        return of([]);
      })
    )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.regionsItems = res?.data || [];
        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching regions');
        }
      });
  }
  onRegionChange(event: any): void {
    this.regionsItemsIds = [];
    this.selectedRegions?.forEach((item: any) => {
      this.regionsItemsIds.push(item?.id);
    });
    this.isLoadingFilteration = true;
    this.pageNumber = 1;
    this.getTourGuidesList();
  }
  resetRegions(): void {
    this.regionsItems?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.selectedRegions = [];
    this.regionsItemsIds = [];
    this.isLoadingFilteration = true;
    this.pageNumber = 1;
    this.getTourGuidesList();
  }
  // End Regions Functions

  // Start Languages Functions
  getLanguagesList(): void {
    this.isLoadingLanguages = true;
    this._PlacesService.getLanguages().pipe(
      finalize(() => {
        this.isLoadingLanguages = false;
        this.isLoadingFilteration = false;
        this.cdr.detectChanges();
      }),
      catchError((error) => {
        this.alertsService.openToast('error', error?.message || 'Error fetching Languages');
        return of([]);
      })
    )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.languagesItems = res?.data || [];
        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching Languages');
        }
      });
  }
  onLanguagesChange(event: any): void {
    this.languagesItemsIds = [];
    this.selectedLanguages?.forEach((item: any) => {
      this.languagesItemsIds.push(item?.id);
    });
    this.isLoadingFilteration = true;
    this.pageNumber = 1;
    this.getTourGuidesList();
  }
  resetLanguages(): void {
    this.regionsItems?.forEach((el: any) => {
      el.isSelected = false;
    });
    this.selectedLanguages = [];
    this.languagesItemsIds = [];
    this.isLoadingFilteration = true;
    this.pageNumber = 1;
    this.getTourGuidesList();
  }
  // End Languages Functions

  closeFilterItems(): void {
    this.displaySearch = false;
  }
  // Function to be called on ngModelChange
  onSearchInputChange(value: string): void {
    this.searchSubject.next(value);
  }

  // Handle Experience Input Change
  onExperienceChange(): void {
    this.isLoadingFilteration = true;
    console.log(this.experience);
    this.experienceValue = this.experience;
    this.pageNumber = 1;
    this.getTourGuidesList();
  }

  ngOnDestroy(): void {
    this.searchSubject.unsubscribe();
    this.unsubscribe.forEach((sb) => sb?.unsubscribe());
  }
}
