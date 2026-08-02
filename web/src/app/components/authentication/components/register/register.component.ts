import { Component, OnInit, Inject, PLATFORM_ID } from '@angular/core';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { isPlatformBrowser } from '@angular/common';
import { Subscription } from 'rxjs';

import { PublicService } from '../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from '../../../../modules/shared/configs/patternValidation';
import { AuthFirebaseService } from '../../../../services/auth-firebase.service';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { AuthService } from '../../../../services/auth.service';
import { LoginComponent } from '../login/login.component';
import { Meta, Title } from '@angular/platform-browser';

import { SharedModule } from '../../../../modules/shared/shared.module';
import { InputTextModule } from 'primeng/inputtext';
import { PasswordModule } from 'primeng/password';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { catchError, tap } from 'rxjs/operators';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    InputTextModule,
    PasswordModule,
    RouterModule,
    FormsModule,
    CommonModule
  ],
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  private subscriptions: Subscription[] = [];

  isLoadingBtn: boolean = false;
  registerForm = this.fb.group({
    firstName: ['', [Validators.required, Validators.minLength(3), this.publicService.charactersOnlyValidator()]],
    lastName: ['', [Validators.required, Validators.minLength(3), this.publicService.charactersOnlyValidator()]],
    email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    password: ['', [Validators.required, Validators.pattern(patterns?.password)]]
  }, { updateOn: "blur" });

  constructor(
    public authFirebaseService: AuthFirebaseService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    public publicService: PublicService,
    public authService: AuthService,
    private ref: DynamicDialogRef,
    private titleService: Title,
    private metaService: Meta,
    private fb: FormBuilder
  ) { }


  ngOnInit(): void {
    // this.setSEO();
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    if (isPlatformBrowser(this.platformId)) {
      var pPasswordElement: any = document.querySelector('#p-password input');
      pPasswordElement.setAttribute('autocomplete', 'current-password');
    }
  }
  setSEO(): void {
    // this.titleService.setTitle('Register');
    if (isPlatformBrowser(this.platformId)) {
    this.metaService.updateTag({ name: 'description', content: 'Register for a new account' });
  }
}
  get formControls(): any {
    return this.registerForm?.controls;
  }

  RegisterNow(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (!this.registerForm?.valid) {
        this.publicService.validateAllFormFields(this.registerForm);
        return;
      }

      this.publicService.show_loader.next(true);
      this.isLoadingBtn = true;
      let formData: any = new FormData();
      formData.append('first_name', this.registerForm?.value?.firstName);
      formData.append('last_name', this.registerForm?.value?.lastName);
      formData.append('email', this.registerForm?.value?.email);
      formData.append('password', this.registerForm?.value?.password);

      this.authService?.register(formData)?.subscribe(
        res => this.handleResponseRegister(res),
        err => this.handleErrorRegister(err)
      );
    }
  }
  handleResponseRegister(res: any) {
    if (res?.code !== 200) {
      res?.message ? this.alertsService?.openToast('error', res?.message) : '';
      this.isLoadingBtn = false;
      this.publicService.show_loader.next(false);
      return;
    }
    if (isPlatformBrowser(this.platformId)) {
    window.localStorage.setItem(keys?.userLoginData, JSON.stringify(res?.data));
    }
    this.getProfileData();
  }
  handleErrorRegister(err: any) {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isLoadingBtn = false;
    this.publicService.show_loader.next(false);
  }

  // Start Profile Data Functions
  getProfileData(): void {
    if (isPlatformBrowser(this.platformId)) {
      const resetSubscription: any = this.authService?.profileData()?.pipe(
        tap(res => this.handleProfileDataResponse(res)),
        catchError(async (err) => this.handleError(err))
      ).subscribe();
      this.subscriptions.push(resetSubscription);
    }
  }
  handleProfileDataResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      return;
    }
    if (isPlatformBrowser(this.platformId)) {
    localStorage.setItem(keys.profileData, JSON.stringify(res.data));
    }
    this.publicService.recallProfileDataLocalStorage.next(true);
    this.registerForm?.reset();
    this.ref?.close();
    this.isLoadingBtn = false;
    this.publicService.show_loader.next(false);
  }
  // End Profile Data Functions

  login(): void {
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);
    const ref = this?.dialogService?.open(LoginComponent, {
      width: '80%',
      styleClass: 'auth-dialog',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  close(): void {
    this.ref.close();
  }

  // Handle Api Errors Or Success
  private handleError(error: any): void {
    error ? this.alertsService?.openToast('error', error || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    this.isLoadingBtn = false;
    this.publicService.show_loader.next(false);
  }
  private handleSuccess(msg: any): void {
    msg ? this.alertsService?.openToast('success', msg) : '';
    this.isLoadingBtn = false;
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
