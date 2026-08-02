import { ForgetPasswordComponent } from '../forget-password/forget-password.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { patterns } from '../../../../modules/shared/configs/patternValidation';
import { AuthFirebaseService } from '../../../../services/auth-firebase.service';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { SharedModule } from '../../../../modules/shared/shared.module';
import { RegisterComponent } from '../register/register.component';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { AuthService } from '../../../../services/auth.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Meta, Title } from '@angular/platform-browser';
import { InputTextModule } from 'primeng/inputtext';
import { AutoFocusModule } from 'primeng/autofocus';
import { PasswordModule } from 'primeng/password';
import { CheckboxModule } from 'primeng/checkbox';
import { RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { catchError, tap } from 'rxjs/operators';
import { AlertsService } from 'src/app/services/alerts.service';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  standalone: true,
  imports: [TranslateModule, FormsModule, ReactiveFormsModule, RouterModule, PasswordModule, InputTextModule, CommonModule, CheckboxModule, AutoFocusModule],
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  private subscriptions: Subscription[] = [];
  isLoadingBtn: boolean = false;

  loginForm = this.fb.group({
    email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    password: ['', [Validators.required]]
  }, { updateOn: 'blur' });

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
    if (isPlatformBrowser(this.platformId)) {
      this.initializePasswordAutocomplete();
    }
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
  }
  private initializePasswordAutocomplete(): void {
    if (isPlatformBrowser(this.platformId)) {
    setTimeout(() => {
      const passwordInput: any = document.querySelector('#p-password input');
      if (passwordInput) {
        passwordInput.setAttribute('autocomplete', 'current-password');
      }
    }, 0);
  }
}
  setSEO(): void {
    // this.titleService.setTitle('Login');
    if (isPlatformBrowser(this.platformId)) {
    this.metaService.updateTag({ name: 'description', content: 'Login to your account' });
  }
}
  get formControls(): any {
    return this.loginForm?.controls;
  }

  loginNow(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (!this.loginForm?.valid) {
        this.publicService.validateAllFormFields(this.loginForm);
        return;
      }

      this.publicService.show_loader.next(true);
      this.isLoadingBtn = true;
      let formData: any = new FormData();
      formData.append('email', this.loginForm?.value?.email);
      formData.append('password', this.loginForm?.value?.password);

      this.authService?.login(formData)?.subscribe(
        res => this.handleResponseLogin(res),
        err => this.handleErrorLogin(err)
      );
    }
  }
  handleResponseLogin(res: any) {
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
  handleErrorLogin(err: any) {
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
    this.loginForm?.reset();
    this.ref?.close({ isLogin: true });
    this.isLoadingBtn = false;
    this.publicService.show_loader.next(false);
  }
  // End Profile Data Functions

  registerNow(): void {
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);
    const ref = this?.dialogService?.open(RegisterComponent, {
      width: '80%',
      styleClass: 'auth-dialog',
    });
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  forgetPassword(): void {
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);
    const ref = this?.dialogService?.open(ForgetPasswordComponent, {
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
