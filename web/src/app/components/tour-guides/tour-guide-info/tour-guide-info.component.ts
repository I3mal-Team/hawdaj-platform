import { SocialLinksComponent } from '../../personal-profile/basic-info-details/social-links/social-links.component';
import { OverlayLoadingComponent } from 'src/app/modules/shared/components/overlay-loading/overlay-loading.component';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { TourGuideImageComponent } from './tour-guide-image/tour-guide-image.component';
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID } from '@angular/core';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { AlertsService } from 'src/app/services/alerts.service';
import { PlacesService } from 'src/app/services/places.service';
import { AuthService } from 'src/app/services/auth.service';
import { TourGuidesService } from '../tour-guides.service';
import { tap, catchError, finalize } from 'rxjs/operators';
import { MultiSelectModule } from 'primeng/multiselect';
import { DropdownModule } from 'primeng/dropdown';
import { of } from 'rxjs/internal/observable/of';
import { ToastModule } from 'primeng/toast';
import { Subscription } from 'rxjs';
import { environment } from 'src/environments/environment';
import { IGuideItem, TourGuideFormDataType } from 'src/app/interfaces/home';

@Component({
  selector: 'app-tour-guide-info',
  standalone: true,
  imports: [CommonModule, TourGuideImageComponent, OverlayLoadingComponent, ToastModule, TranslateModule, MultiSelectModule, SocialLinksComponent, NgOptimizedImage, DropdownModule, ReactiveFormsModule, FormsModule],
  templateUrl: './tour-guide-info.component.html',
  styleUrls: ['./tour-guide-info.component.scss']
})
export class TourGuideInfoComponent {
  private subscriptions: Subscription[] = [];

  isLoadingData: boolean = false;
  isRsest: boolean = false;
  tourGuideData: IGuideItem;

  currentLanguage: string = '';
  currentProfileData: any;
  isFormReadOnly = false;

  linksSocial: any = [];
  socialList: any = [];

  // Start Regions Variables
  regionsItems: any = [];
  isLoadingRegions: boolean = false;
  // End Regions Variables

  // Start Languages Variables
  languagesItems: any = [];
  isLoadingLanguages: boolean = false;
  // End Languages Variables


  // FormGroup for the form controls
  infoForm = this.fb.group({
    name: ['', [Validators.required, Validators.minLength(3)]],
    nickName: ['', Validators.minLength(3)],
    description: ['', [Validators.required, Validators.minLength(50), Validators.maxLength(1000)]],
    // Modify regions and languages to accept array or null
    regions: [null as any | any[], Validators.required], // Multi-select for Regions, accepts array or null
    languages: [null as any | any[], Validators.required], // Multi-select for Languages, accepts array or null
    experience: ['', [Validators.required, Validators.min(0)]], // Number for Experience
    // phoneNumber: ['', {
    //   validators: [Validators.required, Validators.pattern(patterns?.phone)], updateOn: "blur"
    // }],
  });
  get formControls(): any {
    return this.infoForm.controls;
  }

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private _TourGuidesService: TourGuidesService,
    private _PlacesService: PlacesService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private authService: AuthService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    this.getTourGuideProfile();
    if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
      this.currentProfileData = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
    }
  }

  getTourGuideProfile(): void {
    this.isLoadingData = true;
    this._TourGuidesService.getTourGuideProfile()
      .pipe(
        finalize(() => {
          this.isLoadingData = false;
          this.cdr.detectChanges();
        }),
        catchError((error) => {
          this.alertsService.openToast('error', error?.message || 'Error fetching data');
          return of([]);
        })
      )
      .subscribe((res: any) => {
        this.handleSuccessGuideData(res);
      });
  }
  private handleSuccessGuideData(res: any): void {
    if (res?.code === 200) {
      this.tourGuideData = res?.data?.userGuide || null;
      this.socialList = [];
      this.socialList.push(this.tourGuideData?.social);
      this.patchValues();
      this.getRegionsList();
      this.getLanguagesList();
    } else {
      this.alertsService.openToast('error', res?.message || 'Error fetching data');
    }
  }
  patchValues(): void {
    if (this.currentProfileData) {
      this.infoForm.patchValue({
        name: this.tourGuideData?.name,
        nickName: this.tourGuideData?.nickName,
        description: this.tourGuideData?.description,
        experience: this.tourGuideData?.experience || 0,
        // phoneNumber: this.tourGuideData?.phone
      });
    }
  }

  // Start Regions Functions
  getRegionsList(): void {
    this.isLoadingRegions = true;
    this._PlacesService.getRegions().pipe(
      finalize(() => {
        this.isLoadingRegions = false;
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
          this.infoForm.patchValue({
            regions: this.tourGuideData?.regions?.length ? this.tourGuideData?.regions : [],
          });

        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching regions');
        }
      });
  }
  // End Regions Functions

  // Start Languages Functions
  getLanguagesList(): void {
    this.isLoadingLanguages = true;
    this._PlacesService.getLanguages().pipe(
      finalize(() => {
        this.isLoadingLanguages = false;
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
          this.infoForm.patchValue({
            languages: this.tourGuideData?.languages?.length ? this.tourGuideData?.languages : []
          });
        } else {
          this.alertsService.openToast('error', res?.message || 'Error fetching Languages');
        }
      });
  }
  // End Languages Functions

  cancel(): void {
    this.isRsest = true;
    this.patchValues();
    this.socialList = [];
    this.socialList.push(this.tourGuideData?.social);
    this.infoForm.patchValue({
      regions: this.tourGuideData?.regions?.length ? this.tourGuideData?.regions : [],
    });
    this.infoForm.patchValue({
      languages: this.tourGuideData?.languages?.length ? this.tourGuideData?.languages : []
    });
    setTimeout(() => {
      this.isRsest = false;
    }, 0);
  }
  submit(): void {
    if (this.infoForm?.invalid) {
      this.publicService.validateAllFormFields(this.infoForm);
      return;
    }
    let formControlsValues: any = this.infoForm.value;
    const formData: TourGuideFormDataType = {
      name: formControlsValues?.name || '',
      nickName: formControlsValues?.nickName || '',
      description: formControlsValues?.description || '',
      experience: formControlsValues?.experience || 0,
      languages: formControlsValues?.languages?.map(item => item.id) || [],
      regions: formControlsValues?.regions?.map(item => item.id) || []
    };

    console.log(this.socialList);

    // Handle social links mapping
    if (this.socialList?.length > 0) {
      this.socialList?.forEach((element: any) => {
        Object.keys(element).forEach((key: string) => {
          formData[key] = element[key];
        });
      });
    }
    this.publicService.show_loader.next(true);
    const updateDataSubscription: any = this._TourGuidesService?.updateTourGuideProfile(formData)?.pipe(
      tap(res => this.handleUpdateDataResponse(res)),
      catchError(async (err) => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(updateDataSubscription);

  }
  handleUpdateDataResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      this.handleSuccessGuideData(res);
      this.publicService.show_loader.next(false);
      return;
    }
    // this.isFormReadOnly = true;
    // this.publicService.recallProfileDataFuntion.next(true);
    this.handleSuccess(res?.message);
  }
  getSocialLinks(event: any): void {
    console.log(event);

    let arr = event;
    arr?.forEach((element: any) => {
      element['title'] = element?.name?.value;
    });
    this.linksSocial = arr;
    const formData = arr?.reduce((acc: any, item: any) => {
      acc[item.name.title] = item.link;
      return acc;
    }, {});
    this.socialList = [];
    this.socialList.push(formData);
    console.log(this.socialList);
  }

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
