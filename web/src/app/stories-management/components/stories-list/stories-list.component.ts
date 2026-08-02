// Modules
import { Component, inject, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { TranslateModule } from '@ngx-translate/core';
import { PaginatorModule } from 'primeng/paginator';
import { Subject } from 'rxjs/internal/Subject';
import { RouterModule } from '@angular/router';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
import { InputTextModule } from 'primeng/inputtext';
import { Subscription } from 'rxjs';
import { MessageService } from 'primeng/api';
import { catchError, tap } from 'rxjs/operators';
import { throwError } from 'rxjs';

// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { HomeService } from 'src/app/services/home.service';
import { environment } from 'src/environments/environment';
import { StoriesService } from '../../services'; // تأكد من المسار الصحيح

// Components
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { ListSliderComponent } from 'src/app/Common/component/list-card/list-slider/list-slider.component';
import { BannerComponent } from 'src/app/Common/layout/banner/banner.component';
import { NewsLetterComponent } from "../news-letter/news-letter.component";
import { SliderComponent } from '../slider/slider.component';
import { SearchListComponent } from 'src/app/Common/layout/search-list/search-list.component';
import { SearchListSmComponent } from 'src/app/Common/layout/search-list-sm/search-list-sm.component';
import { AllInputTypes } from 'src/app/Common/enums/all-input-types.enum';
import { NoResultComponent } from "../../../Common/layout/no-result/no-result.component";
import { SharedPaginationComponent } from "../../../Common/layout/shared-pagination/shared-pagination.component";

// Directives
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';


@Component({
  selector: 'app-stories-list',
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
    InputTextModule,
    // Components
    OverlayLoadingComponent,
    SkeletonComponent,
    HeaderComponent,
    NewFooterComponent,
    ListSliderComponent,
    BannerComponent,
    SliderComponent,
    NewsLetterComponent,
    SearchListComponent,
    SearchListSmComponent,
    NoResultComponent,
    SharedPaginationComponent,
    // Directives
    LazyLoadSectionDirective
  ],
  templateUrl: './stories-list.component.html',
  styleUrls: ['./stories-list.component.scss']
})
export class StoriesListComponent {

  private localizationLanguageService = inject(LocalizationLanguageService);
  private metadataService = inject(MetadataService);
  private storiesService = inject(StoriesService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private homeService = inject(HomeService);
  private fb = inject(FormBuilder);
  private platformId = inject(PLATFORM_ID);
  private messageService = inject(MessageService);

  private subscriptions: Subscription[] = []; // Changed from 'unsubscribe' to 'subscriptions' for consistency
  private searchSubject = new Subject<void>();
  currentLanguage: any;

  heroStories: any = [];

  recentStories: any = [];
  page: any = 1;
  perPage: any = 6;
  storiesTotalCount: any;
  isLoadingRecentStories: boolean = false;
  isLoadingMoreStories: boolean = false;

  featuredStories: any = [];
  featuredPage: any = 1;
  featuredPerPage: any = 3;
  isLoadingFeaturedStories: boolean = false;

  emailForm = this.fb.group(
    {
      email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    },
    { updateOn: "blur" }
  );
  get emailFormControls(): any {
    return this.emailForm?.controls;
  }
  isLoadingBtn: boolean = false;
  isLoadingSearch: boolean = false;
  keyword: any;
  storyTypeId: any;

  homeShowFooter: boolean = false;

  searchFields: any;
  defaultSelectedType: any;
  storyCategories: any[] = [];

  constructor() {
    this.localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    // Populate searchFields with options for storyType from storyCategories
    this.searchFields = [
      {
        type: AllInputTypes.Text,
        name: 'storyName',
        label: 'stories.form.storyName',
        placeholder: 'stories.form.enterStoryName',
        icon: 'assets/images-v2/pages/Home/quick-search/new-search-icon.webp',
        smIcon: 'assets/images/icons/placeName.svg',
        widthClass: 'col-6 px-3 input-search-result border-end',
        validation: []
      },
      {
        type: AllInputTypes.MultiSelect, // Assuming it's MultiSelect based on your search function
        name: 'storyType',
        label: 'stories.form.storyType',
        placeholder: 'stories.form.enterStoryType',
        icon: 'assets/images-v2/pages/Home/quick-search/type.svg',
        smIcon: 'assets/images/icons/city.svg',
        widthClass: 'col-6 ps-4 input-search-result',
        validation: [],
        options: this.storyCategories, // This will be updated after fetching categories
        isLoading: true // Added loading state for the select
      }
    ];

    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('storiesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storiesList');
    }
    this.getStoryCategories(); // Call this first to ensure categories are loaded before initial search
    this.getRecentStories();
    this.getFeaturedStories();

    this.searchSubject.pipe(debounceTime(750)).subscribe(() => {
      this.page = 1;
      this.isLoadingSearch = true;
      this.getRecentStories();
    });
  }

  private updateMetaTags(): void {
    this.metadataService.updateTitle(`هودج | سوالف`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `هودج | سوالف` },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stories/list` },
      { property: 'og:title', content: `هودج | سوالف` },
    ]);
  }

  getRecentStories(loadingMore?: any): void {
    loadingMore ? this.isLoadingMoreStories = true : this.isLoadingRecentStories = true;
    let combinedSearch = [];
    if (this.keyword) {
      combinedSearch.push(this.keyword);
    }
    const getRecentStoriesSubscription: Subscription = this.storiesService?.getRecentStories({
      page: this.page,
      per_page: this.perPage,
      top_featured: null,
      search: combinedSearch.join(' ').trim() || null,
      category_id: this.storyTypeId || null
    })?.pipe(
      tap((res: any) => this.handleRecentStoriesResponse(res)),
      catchError((err: any) => {
        this.handleRecentStoriesError(err);
        return throwError(() => err);
      })
    ).subscribe();
    this.subscriptions.push(getRecentStoriesSubscription);
  }

  private handleRecentStoriesResponse(res: any): void {
    if (res?.code === 200) {
      this.processRecentStoriesData(res);
    } else {
      this.alertsService?.openToast('error', res?.message || 'خطأ في جلب القصص');
    }
    this.isLoadingRecentStories = false;
    this.isLoadingMoreStories = false;
    this.isLoadingSearch = false;
  }

  private processRecentStoriesData(res: any): void {
    res?.data?.items?.forEach((element: any) => {
      element['rate'] = element?.rate ? Math.round(element?.rate) : 0;
    });
    this.recentStories = res?.data?.items ? res?.data?.items : [];
    this.heroStories = this.recentStories ? this.publicService?.slicedData(this.recentStories, 3) : [];
    // Ensure storiesTotalCount is a valid number to prevent NaN
    this.storiesTotalCount = res?.data?.total ? Number(res.data.total) : 0;
  }

  private handleRecentStoriesError(err: any): void {
    this.alertsService?.openToast('error', err?.message || 'حدث خطأ أثناء جلب القصص');
    this.isLoadingRecentStories = false;
    this.isLoadingMoreStories = false;
    this.isLoadingSearch = false;
  }

  // onPageChange(event: any) {
  //   console.log(event)
  //   this.page = event + 1;
  //   this.getRecentStories();
  //   if (isPlatformBrowser(this.platformId)) {
  //     window.scrollTo({ top: 730, behavior: 'smooth' });
  //   }
  // }
  changePage(direction: number) {
    if (direction === -1 && this.page > 1) {
      this.page--;
    } else if (direction === 1 && this.page < Math.ceil(this.storiesTotalCount / this.perPage)) {
      this.page++;

    }
    this.getRecentStories();

    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }

  search(formValues: any): void {
    if (formValues?.valid) {
      this.keyword = formValues?.value?.storyName || null;
      // Extract the category ID from the storyType selection
      this.storyTypeId = formValues?.value?.storyType?.[0]?.id || null; // Use safe navigation and check for [0] as it's MultiSelect
      this.page = 1;
      this.isLoadingSearch = true;
      this.getRecentStories();
      if (isPlatformBrowser(this.platformId)) {
        window.scrollTo({ top: 300, behavior: 'smooth' });
      }
    } else {
      this.alertsService?.openToast('info', 'stories.form.pleaseEnterStoryNameOrTypeForSearch', 'search');
      this.publicService?.validateAllFormFields(formValues);
    }
  }

  onFieldChanged(event: any): void {
    if (event && event.form && event.form.value) {
      this.keyword = event.form.value.storyName || null;
      // Extract the category ID from the storyType selection
      this.storyTypeId = event.form.value.storyType?.[0]?.id || null; // Use safe navigation and check for [0]
      this.searchSubject.next();
    }
  }

  clearSearch(event?: any): void {
    this.messageService?.clear();
    this.keyword = null;
    this.storyTypeId = null; // Clear the storyTypeId as well
    this.page = 1;
    this.isLoadingSearch = true;
    this.getRecentStories();
  }

  getFeaturedStories(): void {
    this.isLoadingFeaturedStories = true;
    const getFeaturedStoriesSubscription: Subscription = this.storiesService?.getRecentStories({
      page: this.featuredPage,
      per_page: this.featuredPerPage,
      top_featured: true
    })?.pipe(
      tap((res: any) => this.handleFeaturedStoriesResponse(res)),
      catchError((err: any) => {
        this.handleFeaturedStoriesError(err);
        return throwError(() => err);
      })
    ).subscribe();
    this.subscriptions.push(getFeaturedStoriesSubscription);
  }

  private handleFeaturedStoriesResponse(res: any): void {
    if (res?.code === 200) {
      this.featuredStories = res?.data?.items;
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingFeaturedStories = false;
  }

  private handleFeaturedStoriesError(err: any): void {
    this.alertsService?.openToast('error', err?.message || 'حدث خطأ أثناء جلب القصص المميزة');
    this.isLoadingFeaturedStories = false;
  }

  // New method to fetch story categories
  getStoryCategories(): void {
    this.searchFields[1].isLoading = true; // Set loading state for the search field

    const getCategoriesSubscription: Subscription = this.storiesService?.getCategories().pipe(
      tap((res: any) => {
        if (res?.code === 200) {
          // Assuming the categories are in res.data and have 'id' and 'name' properties
          this.storyCategories = res?.data;
          // Update the listValues for the MultiSelect component.
          this.searchFields[1].listValues = this.storyCategories.map(category => ({
            id: category.id,
            name: category.name
          }));
          this.searchFields[1].options = this.storyCategories; // Update options property if used directly
          this.searchFields[1].isLoading = false;
        } else {
          this.alertsService?.openToast('error', res?.message || 'خطأ في جلب تصنيفات القصص');
        }
      }),
      catchError((err: any) => {
        this.alertsService?.openToast('error', err?.message || 'حدث خطأ أثناء جلب تصنيفات القصص');
        this.searchFields[1].isLoading = false; // Ensure loading state is reset on error
        return throwError(() => err);
      })
    ).subscribe();
    this.subscriptions.push(getCategoriesSubscription);
  }

  submit(): void {
    if (this.emailForm?.valid) {
      this.handleValidEmailForm();
    } else {
      this.handleInvalidEmailForm();
    }
  }

  private handleValidEmailForm(): void {
    this.isLoadingBtn = true;
    const data = {
      email: this.emailForm?.value?.email
    };
    const subscribeSubscription: Subscription = this.homeService?.subscribe(data)?.pipe(
      tap((res: any) => this.handleSubscribeResponse(res)),
      catchError((err: any) => {
        this.handleSubscribeError(err);
        return throwError(() => err);
      })
    ).subscribe();
    this.subscriptions.push(subscribeSubscription);
  }

  private handleInvalidEmailForm(): void {
    if (this.emailFormControls?.email?.errors?.['required']) {
      this.alertsService?.openToast('error', this.publicService?.translateTextFromJson('validations.emailRequired'));
    }
    if (this.emailFormControls?.email?.errors?.['pattern']) {
      this.alertsService?.openToast('error', this.publicService?.translateTextFromJson('validations.emailNotValid'));
    }
  }

  handleViewAll() {
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 500, behavior: 'smooth' });
    }
  }

  private handleSubscribeResponse(res: any): void {
    if (res?.code == 200) {
      this.emailForm.reset();
      res?.message ? this.alertsService?.openToast('success', res?.message) : '';
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingBtn = false;
  }

  private handleSubscribeError(err: any): void {
    this.alertsService?.openToast('error', err?.message || 'حدث خطأ في عملية الاشتراك');
    this.isLoadingBtn = false;
  }

  ngOnDestroy(): void {
    this.subscriptions?.forEach((sb) => sb?.unsubscribe());
  }
}
