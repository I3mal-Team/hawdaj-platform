// Modules
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { TranslateModule, TranslateService } from '@ngx-translate/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { DropdownModule } from 'primeng/dropdown';

// Components
import { DynamicSvgComponent } from './../../../modules/shared/components/icons/dynamic-svg/dynamic-svg.component';
import { SocialLinksComponent } from './social-links/social-links.component';

// Services
import { LocalizationLanguageService } from './../../../modules/shared/services/localization-language.service';
import { ChangeDetectorRef, Component, Inject, PLATFORM_ID } from '@angular/core';
import { PublicService } from './../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { AuthService } from '../../../services/auth.service';
import { patterns } from './../../../modules/shared/configs/patternValidation';
import { keys } from './../../../modules/shared/configs/localstorage-key';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription, catchError, tap } from 'rxjs';

@Component({
  selector: 'app-basic-info-details',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    DropdownModule,
    CommonModule,
    FormsModule,

    // Components
    SocialLinksComponent,
    DynamicSvgComponent,
  ],
  templateUrl: './basic-info-details.component.html',
  styleUrls: ['./basic-info-details.component.scss']
})
export class BasicInfoDetailsComponent {
  private subscriptions: Subscription[] = [];
  currentLanguage: string = '';
  currentProfileData: any;
  personalInfo: any;

  dataStyleType: string = 'list';
  showDataStyleType: boolean = true;
  isFormReadOnly: boolean = true;

  genders: any[] = [];
  isLoadingGenders: boolean = false; // This should be controlled based on actual data loading status

  linksSocial: any = [];

  personalInfoForm = this.fb?.group(
    {
      firstName: ['', {
        validators: [
          Validators.required,
          Validators.minLength(3),
          Validators.pattern('^(?!\\s).*')
        ],
        updateOn: "blur"
      }],
      lastName: ['', {
        validators: [
          Validators.required,
          Validators.minLength(3),
          Validators.pattern('^(?!\\s).*')],
        updateOn: "blur"
      }],
      email: ['', {
        validators: [
          Validators.required, Validators.pattern(patterns?.email)], updateOn: "blur"
      }],
      gender: ['', {
        validators: [
          Validators.required], updateOn: "blur"
      }],
      phoneNumber: ['', {
        validators: [Validators.pattern(patterns?.phone)], updateOn: "blur"
      }],
    }
  );
  get formControls(): any {
    return this.personalInfoForm?.controls;
  }

  constructor(
    private localizationLanguageService: LocalizationLanguageService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private activatedRoute: ActivatedRoute,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private translate: TranslateService,
    private authService: AuthService,
    private cdr: ChangeDetectorRef,
    private fb: FormBuilder,
    private router: Router,
  ) {
    localizationLanguageService.updatePathAccordingLang();
  }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
    this.activatedRoute.params.subscribe((params) => {
      let isEdit: boolean;
      isEdit = params['isEdit'];
      isEdit ? this.edit() : '';
    });
    this.loadGenders();
    this.translate?.onLangChange?.subscribe(() => {
      this.loadGenders();  // Reload genders on language change
    });
  }
  clearGender(event: Event): void {
    event.stopPropagation();
    this.personalInfoForm.controls['gender'].setValue(null);
  }
  private loadGenders() {
    this.genders = [
      { label: this.publicService.translateTextFromJson('general.male'), gender: 'male' },
      { label: this.publicService.translateTextFromJson('general.female'), gender: 'female' }
    ];
    this.loadPageData();
    this.cdr.markForCheck();  // Trigger change detection to update the view
  }
  private loadPageData(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}')) {
        this.currentProfileData = JSON.parse(window?.localStorage?.getItem(keys?.profileData) || '{}');
        this.personalInfo = this.currentProfileData?.PersonalData;
        this.patchValues();
      }
    }
  }

  // Toggle data style table or card
  changeDateStyle(type: string): void {
    this.dataStyleType = type;
  }
  checkIfNumber(event: KeyboardEvent): void {
    const allowedKeys = ['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight'];
    if (allowedKeys.includes(event.key) || (event.key >= '0' && event.key <= '9')) {
      return;
    }
    else {
      event.preventDefault();
    }
  }

  patchValues(): void {
    this.personalInfoForm.patchValue({
      firstName: this.personalInfo.first_name,
      lastName: this.personalInfo.last_name,
      email: this.personalInfo.email,
      gender: this.personalInfo.gender == 'male' ? this.genders[0] : this.personalInfo.gender == 'female' ? this.genders[1] : null,
      phoneNumber: this.personalInfo.phone
    });
  }

  edit(): void {
    this.dataStyleType = 'grid';
    this.showDataStyleType = false;
    this.isFormReadOnly = false;
  }

  cancel(): void {
    // this.showDataStyleType = true;
    // this.isFormReadOnly = true;
    // this.dataStyleType = 'list';
    this.router.navigate(['/Profile/Information']);
  }

  submit(): void {
    this.publicService.show_loader.next(true);
    if (!this.personalInfoForm?.valid) {
      this.publicService.validateAllFormFields(this.personalInfoForm);
      return;
    }
    let genderValue: any = this.personalInfoForm?.value?.gender;
    let data = {
      first_name: this.personalInfoForm?.value?.firstName,
      last_name: this.personalInfoForm?.value?.lastName,
      email: this.personalInfoForm?.value?.email,
      phone: this.personalInfoForm?.value?.phoneNumber,
      gender: genderValue?.gender
    };
    if (this.linksSocial?.length > 0) {
      this.linksSocial.forEach((element: any) => {
        data[element.name.title] = element.link
      });
    }
    const updateProfileSubscription: any = this.authService?.updateProfile(data)?.pipe(
      tap(res => this.handleUpdateProfileResponse(res)),
      catchError(async (err) => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(updateProfileSubscription);

  }
  handleUpdateProfileResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      this.publicService.show_loader.next(false);
      return;
    }
    // this.isFormReadOnly = true;
    this.publicService.recallProfileDataFuntion.next(true);
    this.handleSuccess(res?.message);
    this.cancel();
  }

  getSocialLinks(event: any): void {
    let arr = event;
    arr.forEach((element: any) => {
      element['title'] = element?.name?.value;
    });
    this.linksSocial = arr;
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
