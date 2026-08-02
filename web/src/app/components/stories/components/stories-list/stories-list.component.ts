// Modules
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { debounceTime } from 'rxjs/internal/operators/debounceTime';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { Subscription } from 'rxjs/internal/Subscription';
import { TranslateModule } from '@ngx-translate/core';
import { PaginatorModule } from 'primeng/paginator';
import { Subject } from 'rxjs/internal/Subject';
import { RouterModule } from '@angular/router';
import { RatingModule } from 'primeng/rating';
import { ToastModule } from 'primeng/toast';
// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { StoriesService } from 'src/app/services/stories.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { HomeService } from 'src/app/services/home.service';
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
  templateUrl: './stories-list.component.html',
  styleUrls: ['./stories-list.component.scss']
})
export class StoriesListComponent {
  private unsubscribe: Subscription[] = [];
  private searchSubject = new Subject<any>();
  currentLanguage: any;

  heroStories: any = [];

  recentStories: any = [];
  page: any = 1;
  perPage: any = 6;
  storiesTotalCount: any;
  isLoadingRecentStories: boolean = false;
  isLoadingMoreStories: boolean = false;
  currentPage: any = 1;
  paginatedStoriesList: any = [];

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

  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private storiesService: StoriesService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private homeService: HomeService,
    private fb: FormBuilder
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.updateMetaTags();
      this.currentLanguage = this.publicService.getCurrentLanguage();
      this.metadataService.updateMetaAccordingCurrentLanguage('storiesList');
    }
    if (isPlatformServer(this.platformId)) {
      this.updateMetaTags();
      this.metadataService.updateMetaAccordingCurrentLanguage('storiesList');
    }
    this.getRecentStories();
    this.getFeaturedStories();
    this.searchSubject.pipe(debounceTime(750)).subscribe(event => {
      this.searchService(event);
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
    this.storiesService?.getRecentStories(this.page, this.perPage, null, this.keyword)?.subscribe(
      (res: any) => this.handleRecentStoriesResponse(res),
      (err: any) => this.handleRecentStoriesError(err)
    );
  }
  private handleRecentStoriesResponse(res: any): void {
    if (res?.code === 200) {
      this.processRecentStoriesData(res);
    } else {
      this.alertsService?.openToast('error', res?.message || 'Error fetching stories');
    }
    this.isLoadingRecentStories = false;
    this.isLoadingMoreStories = false;
  }
  private processRecentStoriesData(res: any): void {
    res?.data?.items?.forEach((element: any) => {
      element['rate'] = element?.rate ? Math.round(element?.rate) : 0;
    });
    // if (this.page == 1) {
    this.recentStories = res?.data?.items ? res?.data?.items : [];
    // this.getPaginatedData();
    // } else {
    //   this.recentStories.push(...(res?.data?.items ? res?.data?.items : []));
    // }
    this.heroStories = this.recentStories ? this.publicService?.slicedData(this.recentStories, 3) : [];
    this.storiesTotalCount = res?.data?.total;
  }
  private handleRecentStoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingRecentStories = false;
    this.isLoadingMoreStories = false;
  }
  loadMoreStories(): void {
    this.page++;
    this.getRecentStories(true);
  }
  onPageChange(event: any) {
    this.page = event.page + 1;
    this.getRecentStories(true);
    if (isPlatformBrowser(this.platformId)) {
      window.scrollTo({ top: 730, behavior: 'smooth' });
    }
    // this.getPaginatedData();
  }
  getPaginatedData(): any {
    const startIndex: any = (this.currentPage - 1) * 6;
    const endIndex: any = startIndex + 6;
    this.paginatedStoriesList = this.recentStories?.slice(startIndex, endIndex);
  }
  handleSearch(event: any): void {
    this.searchSubject.next(event);
  }
  searchService(event: any): void {
    this.keyword = event;
    this.page = 1;
    this.isLoadingSearch = true;
    this.getRecentStories();
  }
  clearSearchValue(event: any): void {
    event.value = '';
    this.page = 1;
    this.keyword = null;
    this.isLoadingSearch = true;
    this.getRecentStories();
  }
  getFeaturedStories(): void {
    this.isLoadingFeaturedStories = true;
    this.storiesService?.getRecentStories(this.featuredPage, this.featuredPerPage, true)?.subscribe(
      (res: any) => this.handleFeaturedStoriesResponse(res),
      (err: any) => this.handleFeaturedStoriesError(err)
    );
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
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingFeaturedStories = false;
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
    this.homeService?.subscribe(data)?.subscribe(
      (res: any) => this.handleSubscribeResponse(res),
      (err: any) => this.handleSubscribeError(err)
    );
  }
  private handleInvalidEmailForm(): void {
    if (this.emailFormControls?.email?.errors?.['required']) {
      this.alertsService?.openToast('error', this.publicService?.translateTextFromJson('validations.emailRequired'));
    }
    if (this.emailFormControls?.email?.errors?.['pattern']) {
      this.alertsService?.openToast('error', this.publicService?.translateTextFromJson('validations.emailNotValid'));
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
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingBtn = false;
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
