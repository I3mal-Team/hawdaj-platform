import { Component, Inject, inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser, isPlatformServer, NgOptimizedImage } from '@angular/common';
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { MetadataService } from 'src/app/modules/shared/services/metadata.service';
import { ActivatedRoute, Router } from '@angular/router';
import { MessageService } from 'primeng/api';
import { DialogService } from 'primeng/dynamicdialog';
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AuthService } from 'src/app/services/auth.service';
import { FormBuilder, Validators } from '@angular/forms';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { environment } from 'src/environments/environment';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { catchError, finalize, of, Subscription } from 'rxjs';
import { ToastModule } from 'primeng/toast';
import { NewFooterComponent } from 'src/app/modules/shared/components/new-footer/new-footer.component';
import { SkeletonComponent } from "../../../modules/shared/components/skeleton/skeleton.component";
import { HeaderComponent } from "../../../modules/shared/components/header/header.component";
import { ShareSocialComponent } from "../../../Common/component/share-social/share-social.component";
import { AllTabsTypes } from 'src/app/Common/enums/details-tabs.enum';
import { TourGuideTabsComponent } from "../tour-guide-tabs/tour-guide-tabs.component";
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { TranslateModule } from '@ngx-translate/core';
import { TourGuideCardComponent } from '../tour-guide-card/tour-guide-card.component';
import { CarouselModule } from 'primeng/carousel';
import { tourGuideService } from '../../services';
import { moduleTypeRating } from 'src/app/Common/enums/module-type-rating.enum';
import { RateSiteComponent } from "../../../components/home-page/components/rate-site/rate-site.component";
import { RateItemComponent } from 'src/app/Common/component/rate-place/rate-item.component';
import { MediaViewerComponent } from "../../../shared/components/media-viewer/media-viewer.component";

@Component({
  selector: 'app-tour-guide-details',
  standalone: true,
  imports: [
    CommonModule,
    ToastModule,
    TranslateModule,
    CarouselModule,
    NewFooterComponent,
    SkeletonComponent,
    HeaderComponent,
    ShareSocialComponent,
    TourGuideTabsComponent,
    TourGuideCardComponent,
    LazyLoadImageDirective,
    NgOptimizedImage,
    RateSiteComponent,
    RateItemComponent,
    MediaViewerComponent
  ],
  templateUrl: './tour-guide-details.component.html',
  styleUrls: ['./tour-guide-details.component.scss']
})
export class TourGuideDetailsComponent {
  private subscription: Subscription;
  currentLanguage: string;
  currentLoginInformation: Record<string, any>;
  fullUrl: string = null;
  isUserLoggedin: boolean = false;
  moduleTypeRating: string;

  isLoadingTourGuideDetails: boolean = false;
  isLoadingBtn: boolean = false;
  isLoadingTourGuidesList: boolean = false;

  tourGuideId: number;
  tourGuideDetails: any;

  //tabs
  tabs: Array<{ type: string; title: string; icon: string }> = [];

  //top
  itemsList: any[] = [];

  //p-caroucel
  responsiveOptions: Array<{ breakpoint: string; numVisible: number; numScroll: number }> = [];

  //rating
  ratingsPerPage = 3;
  currentRatingPage = 0;
  isLoadingReviews: boolean = false;
  private localizationLanguageService = inject(LocalizationLanguageService);
  private metadataService = inject(MetadataService);
  private activatedRoute = inject(ActivatedRoute);
  private messageService = inject(MessageService);
  private dialogService = inject(DialogService);
  private alertsService = inject(AlertsService);
  public publicService = inject(PublicService);
  private authService = inject(AuthService);
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private _TourGuidesService = inject(tourGuideService);


  rateForm = this.fb.group({
    status: [null, [Validators.required]],
    name: ['', {
      validators: [],
      updateOn: 'blur',
    },],
    email: ['', {
      validators: [Validators.pattern(patterns.email)],
      updateOn: 'blur',
    },
    ],
    massage: [
      '',
      {
        validators: [Validators.required, Validators.minLength(10), Validators.pattern('[a-zA-Z ]+')],
        updateOn: 'blur',
      },
    ],
  });

  get formControls(): any {
    return this.rateForm?.controls;
  }

  constructor(@Inject(PLATFORM_ID) private platformId: Object) {
    this.localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.moduleTypeRating = moduleTypeRating?.GUIDE;
    this.tabs = [
      {
        type: AllTabsTypes.Description,
        title: 'places.description',
        icon: 'assets/images-v2/pages/tour-guide-details/tabs/description.svg'
      },
      {
        type: AllTabsTypes.Language,
        title: 'labels.languages',
        icon: 'assets/images-v2/pages/tour-guide-details/tabs/languages.svg'
      },
      {
        type: AllTabsTypes.Location,
        title: 'labels.regions',
        icon: 'assets/images-v2/pages/tour-guide-details/tabs/location.svg'
      },
      {
        type: AllTabsTypes.AI,
        title: 'general.chat',
        icon: 'assets/images-v2/pages/tour-guide-details/tabs/chat.svg'
      }
    ];

    this.responsiveOptions = [
      {
        breakpoint: '1240px',
        numVisible: 1,
        numScroll: 1
      },
      {
        breakpoint: '1024px',
        numVisible: 2,
        numScroll: 1
      },
      {
        breakpoint: '767px',
        numVisible: 1,
        numScroll: 1
      }
    ];

    this.isUserLoggedin = this.authService.isLoggedIn();
    this.initPageData();
  }

  // Start Remove query parameters
  removeQueryParams() {
    this.router?.navigate(['/tour-guides']);
  }
  // End Remove query parameters

  // Start Initialize page data
  private initPageData(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
      if (
        JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')
          ?.user
      ) {
        this.currentLoginInformation = JSON.parse(
          window?.localStorage?.getItem(keys?.userLoginData) || '{}'
        )?.user;
      }
    }

    this.subscription = this.activatedRoute.params.subscribe((params) => {
      this.tourGuideId = params['id'];
      if (this.tourGuideId) {
        this.getTourGuideById(this.tourGuideId, true);
        this.getTourGuidesList();
        this.fullUrl = environment.publicUrl + this.localizationLanguageService.getFullURL();
      }
    });

    // Subscribe to placeCategoryDetails with a proper unsubscribe mechanism
    this.publicService.placeCategoryDetails.subscribe((res) => {
      if (res?.id) {
        this.getTourGuideById(res.id, true);
        // this.getRelatedStores(res.id);
        if (isPlatformBrowser(this.platformId)) {
          window.scrollTo(0, 0); // Move this to a browser-specific function
        }
      }
    });
  }
  // End Initialize page data

  // Start Get tour guide by ID
  getTourGuideById(id: any, preventLoading: boolean = false, reviewLoading?: boolean): void {
    if (!id) return;
    preventLoading ? this.isLoadingTourGuideDetails = true : '';
    reviewLoading ? this.isLoadingReviews = true : '';
    this._TourGuidesService.getTourGuideById(id).pipe(
      finalize(() => {
        this.isLoadingTourGuideDetails = false;
        this.isLoadingReviews = false;
      })
    ).subscribe({
      next: (res) => this.handleTourGuideResponse(res),
      error: (err) => this.handleErrorTourGuide(err)
    });
  }
  // End Get tour guide by ID

  // Start Handle tour guide API response
  handleTourGuideResponse(res: any): void {
    if (res.code !== 200) {
      this.alertsService.openToast(
        'error',
        res.message || 'Error loading tour guide data'
      );
      this.isLoadingTourGuideDetails = false;
      return;
    }

    this.processTourGuideDetails(res.data);
    this.isLoadingTourGuideDetails = false;
  }
  // End Handle tour guide API response

  // Start Process tour guide details
  processTourGuideDetails(data: any): void {
    this.tourGuideDetails = { ...data, rating: Math.ceil(data.rating || 0) };
    this.tourGuideId = this.tourGuideDetails?.id;
    if (!this.tourGuideDetails.image) {
      this.tourGuideDetails.image = 'assets/images/default-avatar.png';
    }

    if (isPlatformBrowser(this.platformId)) {
      this.updateTourGuideMetaTags();
    }
    if (isPlatformServer(this.platformId)) {
      this.updateTourGuideMetaTags();
    }
    this.handleTourGuideGalleries();
    // this.setupAdditionalTourGuideDetails();
    this.isLoadingTourGuideDetails = false;
  }
  // End Process tour guide details

  // Start Get tour guides list
  getTourGuidesList(): void {
    this.isLoadingTourGuidesList = true;
    this._TourGuidesService.getAll({ page: 1, per_page: 4, excludedId: this.tourGuideId, top_rated: true })
      .pipe(
        finalize(() => {
          this.isLoadingTourGuidesList = false;
        }),
        catchError((error) => {
          this.alertsService.openToast('error', error?.message || 'Error fetching data');
          return of([]);
        })
      )
      .subscribe((res: any) => {
        if (res?.code === 200) {
          this.itemsList = res?.data?.items || [];
        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching data');
        }
      });
  }
  // End Get tour guides list

  // Start Handle Error tour guide
  handleErrorTourGuide(error: any): void {
    this.alertsService.openToast('error', 'Failed to load tour guide details');
    this.isLoadingTourGuideDetails = false;
  }
  // End Handle Error tour guide

  // Start Update tour guide meta tags
  private updateTourGuideMetaTags(): void {
    this.metadataService.updateTitle(`${this.tourGuideDetails.name} - Best Tour Guide`);

    this.metadataService.updateMetaTagsName([
      { name: 'title', content: `${this.tourGuideDetails.name} - Expert Tour Guide` },
      { name: 'description', content: this.tourGuideDetails.description || 'Discover the best travel experiences with our expert tour guide.' },
    ]);

    this.metadataService.updateMetaTagsProperty([
      {
        property: 'og:url',
        content: `${environment.publicUrl}/${this.localizationLanguageService.getCurrentLanguage()}/tour-guides/${this.tourGuideDetails.id}`,
      },
      { property: 'og:title', content: `${this.tourGuideDetails.name} - Explore with Confidence` },
      { property: 'og:description', content: this.tourGuideDetails.bio || 'Personalized tours and unforgettable experiences await you.' },
    ]);
    this.metadataService.setSharePreviewImage(
      this.tourGuideDetails.image || 'assets/images/default-avatar.png'
    );
  }
  // End Update tour guide meta tags

  // Start Handle tour guide galleries
  private handleTourGuideGalleries(): void {
    if (!this.tourGuideDetails?.galleries?.length) {
      this.tourGuideDetails.galleries = [
        {
          id: this.tourGuideDetails?.id || null,
          file: this.tourGuideDetails?.image || 'assets/images/default-avatar.png',
          type: 'tour-guide',
        },
      ];
    }
  }
  // End Handle tour guide galleries

  // Start Handle image load error
  onImageError(event: Event) {
    const imgElement = event.target as HTMLImageElement;
    imgElement.src = 'assets/images-v2/pages/place-details/arab-man.jpg';
  }
  // End Handle image load error

  // End Send feedback from tourGuide
  sendFeedbackFromTourGuide(feedbackData: any): void {
    this.messageService.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (feedbackData) {
        this.isLoadingBtn = true;
        this._TourGuidesService.sendFeedbackFromTourGuide(feedbackData).subscribe({
          next: (res: any) => {
            this.isLoadingBtn = false;
            if (res?.code === 200) {
              this.getTourGuideById(this.tourGuideId, false, true);
              this.alertsService?.openToast(
                'success',
                this.publicService?.translateTextFromJson('general.successRate')
              );
              if (isPlatformBrowser(this.platformId)) {
                window.scrollTo({ top: 200, behavior: 'smooth' });
              }
            } else {
              res?.message && this.alertsService?.openToast('error', res.message);
            }
          },
          error: (err: any) => {
            this.isLoadingBtn = false;
            err && this.alertsService?.openToast('error', err);
          }
        });
      } else {
        this.publicService.validateAllFormFields(this.rateForm);
      }
    }
  }
  // End Send feedback from tourGuide

  // Start Cancel feedback form
  cancel(): void {
    this.rateForm?.reset();
  }
  // End Cancel feedback form

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }
}
