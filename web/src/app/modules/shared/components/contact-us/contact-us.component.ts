import { environment } from '../../../../../environments/environment';
import { TranslationChildModule } from '../../../../services/translation-child.module';
import { MetadataService } from '../../services/metadata.service';
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID } from '@angular/core';
import { PublicService } from '../../services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from '../../configs/patternValidation';
import { HomeService } from '../../../../services/home.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { Subscription } from 'rxjs';
import { RouterModule } from '@angular/router';
import { SharedModule } from 'primeng/api';
import { CheckboxModule } from 'primeng/checkbox';
import { InputTextModule } from 'primeng/inputtext';
import { PasswordModule } from 'primeng/password';
import { StoriesService } from '../../../../services/stories.service';
import { RatingModule } from 'primeng/rating';

@Component({
  standalone: true,
  imports: [
    CommonModule, RouterModule, SharedModule, RatingModule,
    PasswordModule, InputTextModule, CheckboxModule, FormsModule, ReactiveFormsModule, TranslationChildModule
  ],

  selector: 'app-contact-us',
  templateUrl: './contact-us.component.html',
  styleUrls: ['./contact-us.component.scss']
})
export class ContactUsComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  heroStories: any = [];

  recentStories: any = [];
  page: any = 1;
  perPage: any = 10;
  storiesTotalCount: any;
  isLoadingRecentStories: boolean = false;
  isLoadingMoreStories: boolean = false;
  currentPage: any = 1;
  paginatedStoriesList: any = [];

  featuredStories: any = [];
  featuredPage: any = 1;
  featuredPerPage: any = 3;
  isLoadingFeaturedStories: boolean = false;
  isPlatformBrowser: boolean;

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
  data: any = {
    title: 'title',
    content: "description"
  }
  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private homeService: HomeService,
    private cdr: ChangeDetectorRef,
    private storiesService: StoriesService,
    private fb: FormBuilder
  ) {
    this.isPlatformBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit(): void {
    this.getRecentStories();
    this.getFeaturedStories();
  }
  // ngAfterViewInit(): void {
  //   if (isPlatformBrowser(this.platformId)) {
  //     this.updateMetaTags(this.heroStories[0]);
  //   }
  //   if (isPlatformServer(this.platformId)) {
  //     this.updateMetaTags(this.heroStories[0]);
  //   }
  // }

  getRecentStories(loadingMore?: any): void {
    loadingMore ? this.isLoadingMoreStories = true : this.isLoadingRecentStories = true;
    this.storiesService?.getRecentStories(this.page, this.perPage)?.subscribe(
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
    if (this.page == 1) {
      this.recentStories = res?.data?.items ? res?.data?.items : [];
      this.getPaginatedData();
    } else {
      this.recentStories?.push(...(res?.data?.items ? res?.data?.items : []));
    }
    if (this.isPlatformBrowser) {
      this.updateMetaTags();
    }
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
    this.currentPage = event.page + 1;
    this.getPaginatedData();
  }
  getPaginatedData(): any {
    const startIndex: any = (this.currentPage - 1) * 6;
    const endIndex: any = startIndex + 6;
    this.paginatedStoriesList = this.recentStories?.slice(startIndex, endIndex);
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
  private updateMetaTags(): void {

    this.metadataService.updateTitle(this.recentStories[0].title);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: this.recentStories[0].title },
      { name: 'description', content: this.recentStories[0]?.content },
      { name: 'date', content: '2023-10-29T09:28:59+00:00' },

      { name: 'twitter:url', content: 'https://hawdaj.net/' },

      { name: 'twitter:title', content: this.recentStories[0].title },
      { name: 'twitter:description', content: this.recentStories[0]?.content },
    ]);
    this.metadataService.updateMetaTagsProperty([


      { property: 'article:modified_time', content: '2023-10-29T09:28:59+00:00' },

      { property: 'og:type', content: 'website' },
      { property: 'og:url', content: 'https://hawdaj.net/' },
      { property: 'og:title', content: this.recentStories[0]?.title },
      { property: 'og:description', content: this.recentStories[0]?.content },
      { property: 'og:site_name', content: 'Hawdaj' }
    ]);
    this.metadataService.setSharePreviewImage(`${environment.imageBaseUrl}/front_assets/imgs/logo.svg`);
  }
  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
