// Modules
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { CommonModule, isPlatformBrowser, isPlatformServer } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { ActivatedRoute, RouterModule } from '@angular/router';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
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
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { ScrollTopComponent } from 'src/app/modules/shared/components/scroll-top/scroll-top.component';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { LazyLoadSectionDirective } from 'src/app/shared/directives/lazyLoad-section.directive';
import { FooterComponent } from 'src/app/modules/shared/components/footer/footer.component';
import { HeaderComponent } from 'src/app/modules/shared/components/header/header.component';
import { RateComponent } from 'src/app/components/events/components/rate/rate.component';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
// Pipes
import { SafeHtmlPipe } from 'src/app/Common/pipes/safe-html.pipe';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';
// Functions
import { stripHtmlAndClamp } from 'src/app/Common/functions/html.util';

@Component({
  selector: 'app-story-details',
  standalone: true,
  imports: [
    // Modules
    ReactiveFormsModule,
    PaginatorModule,
    TranslateModule,
    CarouselModule,
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
    SafeHtmlPipe,
    StripHtmlPipe
  ],
  templateUrl: './story-details.component.html',
  styleUrls: ['./story-details.component.scss']
})
export class StoryDetailsComponent {
  private unsubscribe: Subscription[] = [];
  currentLanguage: any;

  isLoading: boolean = false;
  storyId: any;
  storyDetails: any;

  featuredStories: any = [];
  isLoadingFeaturedStories: boolean = false;
  page: any = 1;
  perPage: any = 12;

  reviews: any = [];
  isLoadingReviews: boolean = false;

  storiesResponsiveOptions: any = [
    {
      breakpoint: '1800px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '1024px',
      numVisible: 3,
      numScroll: 1,
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 1,
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1,
    },
  ];

  responsiveOptions = [
    {
      breakpoint: '1024px',
      numVisible: 3,
      numScroll: 3
    },
    {
      breakpoint: '768px',
      numVisible: 2,
      numScroll: 2
    },
    {
      breakpoint: '560px',
      numVisible: 1,
      numScroll: 1
    }
  ];

  fullUrl: any = null;
  homeShowFooter: boolean = false;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private metadataService: MetadataService,
    private activatedRoute: ActivatedRoute,
    private storiesService: StoriesService,
    private dialogService: DialogService,
    private publicService: PublicService,
    private alertsService: AlertsService
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.activatedRoute.params.subscribe(params => {
      this.storyId = params['id'];
      if (this.storyId) {
        this.getStoryDetails();
        // this.fullUrl = environment.publicUrl + '/stories/' + this.storyId;
        this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
      }
    });
    this.getFeaturedStories();
  }

  getStoryDetails(): void {
    this.isLoading = true;
    this.storiesService?.getStoryById(this.storyId)?.subscribe(
      (res: any) => this.handleStoryDetailsResponse(res),
      (err: any) => this.handleStoryDetailsError(err)
    );
  }
  private handleStoryDetailsResponse(res: any): void {
    if (res?.code === 200) {
      this.storyDetails = res?.data;
      // this.storyDetails['reviews'] = this.generateDummyReviews();
      if (isPlatformBrowser(this.platformId)) {
        this.updateMetaTags();
      }
      if (isPlatformServer(this.platformId)) {
        this.updateMetaTags();
      }
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoading = false;
  }
  private updateMetaTags(): void {
    this.metadataService.updateTitle(`${this.storyDetails.title}`);
    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.storyDetails.title}` },
      { name: 'description', content: stripHtmlAndClamp(this.storyDetails.description) },
    ]);
    this.metadataService.updateMetaTagsProperty([
      { property: 'og:url', content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/stories/${this.storyDetails.slug}` },
      { property: 'og:title', content: `${this.storyDetails.title}` },
      { property: 'og:description', content: stripHtmlAndClamp(this.storyDetails.description) },
    ]);
    this.metadataService.setSharePreviewImage(this.storyDetails.image);
  }
  private handleStoryDetailsError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoading = false;
  }
  private generateDummyReviews(): any[] {
    return [
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
      {
        title: 'Saudi Arabia Leading',
        description: 'Saudi Arabias commitment to environmental sustainability goes beyond',
        rate: 4
      },
    ];
  }

  getFeaturedStories(): void {
    this.isLoadingFeaturedStories = true;
    this.storiesService?.getRecentStories(this.page, this.perPage, true)?.subscribe(
      (res: any) => this.handleFeaturedStoriesResponse(res),
      (err: any) => this.handleFeaturedStoriesError(err)
    );
  }
  private handleFeaturedStoriesResponse(res: any): void {
    if (res?.code === 200) {
      this.processFeaturedStoriesData(res);
    } else {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
    }
    this.isLoadingFeaturedStories = false;
  }
  private processFeaturedStoriesData(res: any): void {
    if (res?.data?.items) {
      res.data.items.forEach((element: any) => {
        element['rate'] = element?.rate ? Math.round(element?.rate) : 0;
      });
      this.featuredStories = res.data.items;
    }
  }
  private handleFeaturedStoriesError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingFeaturedStories = false;
  }

  leaveReview(): void {
    const ref = this.dialogService.open(RateComponent, {
      header: this.publicService?.translateTextFromJson('general.rate'),
      width: '45%',
      baseZIndex: 10000,
      data: {
        type: 'swalefs',
        parentId: this.storyId
      },
      styleClass: 'rate'
    });
  }
  share(): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl
      },
      styleClass: 'rate'
    });
    ref.onClose.subscribe((res: any) => {
      if (res) {
      }
    })
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
