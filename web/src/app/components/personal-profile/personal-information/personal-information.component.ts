import { SkeletonComponent } from './../../../modules/shared/components/skeleton/skeleton.component';
import { ProfileService } from '../../../services/profile.service';
// Modules
import { ChangeDetectorRef, Component, ElementRef, Inject, PLATFORM_ID, ViewChild } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Subscription, catchError, finalize, tap } from 'rxjs';
import { IStory } from './../../../interfaces/profile';
import { DropdownModule } from 'primeng/dropdown';
import { CalendarModule } from 'primeng/calendar';
import { CarouselModule } from 'primeng/carousel';
import { RouterModule } from '@angular/router';
import { TabViewModule } from 'primeng/tabview';
import { ImageModule } from 'primeng/image';

// Components
import { DynamicSvgComponent } from './../../../modules/shared/components/icons/dynamic-svg/dynamic-svg.component';
import { StoriesSliderComponent } from './stories-slider/stories-slider.component';
import { ProfileImageComponent } from '../profile-image/profile-image.component';
import { AddLandmarkComponent } from '../add-landmark/add-landmark.component';

// Services
import { LocalizationLanguageService } from 'src/app/modules/shared/services/localization-language.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { AuthService } from 'src/app/services/auth.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { DialogService } from 'primeng/dynamicdialog';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-personal-information',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    CalendarModule,
    DropdownModule,
    CarouselModule,
    TabViewModule,
    RouterModule,
    CommonModule,
    ImageModule,
    FormsModule,

    // Components
    StoriesSliderComponent,
    ProfileImageComponent,
    DynamicSvgComponent,
    SkeletonComponent,
  ],
  templateUrl: './personal-information.component.html',
  styleUrls: ['./personal-information.component.scss']
})
export class PersonalInformationComponent {
  private subscriptions: Subscription[] = [];
  currentLanguage: string = '';

  currentProfileData: any;
  linksSocial: any = [];

  stories: IStory[] = [];
  isLoadingStories = false;

  @ViewChild('editInfo') editInfo!: ElementRef;
  dataStyleType: string = 'list';
  showDataStyleType: boolean = true;
  isFormReadOnly: boolean = true;

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private profileService: ProfileService,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private translate: TranslateService,
    private authService: AuthService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
      this.currentProfileData = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
    }
    this.getMyLastDayStories();
    this.profileService.recallGetCurrent14Stories.subscribe((res: any) => {
      this.getMyLastDayStories();
      this.publicService.recallProfileDataFuntion.next(true);
    });
  }

  // Start Add Landmark
  addLandmark(): void {
    const ref = this.dialogService?.open(AddLandmarkComponent, {
      dismissableMask: true,
      width: '100%',
      height: '100%',
      styleClass: 'add-landmark-modal',
    });
    ref.onClose.subscribe((res: any) => {

    });
  }
  // End Add Landmark

  addNewStory(event: any): void {
    if (event.isAddNewStory == true) {
      this.getMyLastDayStories();
      this.publicService.recallProfileDataFuntion.next(true);
    }
  }

  // Start Get myLastDayStories Functions
  getMyLastDayStories(): void {
    this.isLoadingStories = true;
    let getStoriesSubscribe: Subscription = this.profileService?.getMyLastDayStories().pipe(
      tap(res => this.handleGetMyLastDayStoriesSuccess(res)),
      catchError(err => this.handleError(err)),
      finalize(() => this.finalizeLoading())
    ).subscribe();
    this.subscriptions.push(getStoriesSubscribe);
  }
  private handleGetMyLastDayStoriesSuccess(response: any): void {
    if (response?.code == 200) {
      this.stories = response?.data?.items;
      // For Upload
      this.stories.push({
        "id": 2,
        "type": "file",
        "text": null,
        "file": `${environment.imageBaseUrl}/uploads/stories/dP9eupDYsEmUNACRTqf7dgR8epsmj8iQVuqhvOb1.jpg`,
        "status": "active",
        "total_views": 0,
        "total_likes": 0,
        "total_comments": 0,
        "total_shares": 0,
        isNew: true
      });
    } else {
      this.handleError(response?.message);
    }
  }
  private finalizeLoading(): void {
    this.isLoadingStories = false;
  }
  // End Get myLastDayStories Functions

  // Handle Api Errors Or Success
  private handleError(error: any): any {
    error ? this.alertsService?.openToast('error', error || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    this.publicService.show_loader.next(false);
  }
  private handleSuccess(msg: any): void {
    msg ? this.alertsService?.openToast('success', msg) : '';
    this.publicService.show_loader.next(false);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
