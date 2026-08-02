import { ConfirmPasswordValidator } from 'src/app/modules/shared/configs/confirmPasswordValidator';
import { AuthFirebaseService } from 'src/app/services/auth-firebase.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { AuthService } from 'src/app/services/auth.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { DynamicDialogRef } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { PasswordModule } from 'primeng/password';
import { finalize } from 'rxjs/operators';
import { Router } from '@angular/router';

@Component({
  selector: 'app-change-password',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    PasswordModule,
    CommonModule,
    FormsModule
  ],
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.scss']
})
export class ChangePasswordComponent {
  private subscriptions: Subscription[] = [];
  isPasswordChange: boolean = false;

  changePasswordForm = this.fb?.group({
    currentPassword: ['', { validators: [Validators.required, Validators.pattern(patterns?.password)], updateOn: 'blur' }],
    password: ['', { validators: [Validators.required, Validators.pattern(patterns?.password)], updateOn: 'blur' }],
    confirmPassword: ['', { validators: [Validators.required, Validators.pattern(patterns?.password)], updateOn: 'blur' }],
  },
    {
      validator: ConfirmPasswordValidator.MatchPassword
    });
  get formControls(): any {
    return this.changePasswordForm?.controls;
  }

  constructor(
    private authFirebaseService: AuthFirebaseService,
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private alertsService: AlertsService,
    private authService: AuthService,
    private ref: DynamicDialogRef,
    private fb: FormBuilder,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    this.changePasswordForm.get('password')?.valueChanges?.subscribe((res: any) => {
      this.changePasswordForm?.get('confirmPassword')?.setErrors(null);
      this.isPasswordChange = true;
    });
    this.changePasswordForm?.get('confirmPassword')?.valueChanges.subscribe(() => {
      this.isPasswordChange = false;
    });
  }

  onFocusConfirmPassword(): void {
    this.isPasswordChange = false;
  }

  submit(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (!this.changePasswordForm?.valid) {
        this.publicService.validateAllFormFields(this.changePasswordForm);
        return;
      }
      let data = {
        currentPassword: this.changePasswordForm?.value?.currentPassword,
        password: this.changePasswordForm?.value?.password,
        password_confirmation: this.changePasswordForm?.value?.confirmPassword
      };
      this.publicService.show_loader.next(true);
      const changePasswordSubscription: any = this.authService?.changePassword(data)?.pipe(
        tap(res => this.handleChangePasswordResponse(res)),
        catchError(async (err) => this.handleError(err))
      ).subscribe();
      this.subscriptions.push(changePasswordSubscription);
    }
  }
  handleChangePasswordResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      this.publicService.show_loader.next(false);
      return;
    }
    this.ref?.close();
    this.logOut();
    this.handleSuccess(res?.message);
  }

  logOut(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.executeLogout();
    }
  }
  private executeLogout(): void {
    this.publicService.show_loader.next(true);
    const logout$ = this.authService.signOut().pipe(
      tap(res => this.handleLogoutResponse(res)),
      finalize(() => this.publicService.show_loader.next(false))
    );
    logout$.subscribe({
      error: (err: any) => this.alertsService.openToast('error', err)
    });
  }
  private handleLogoutResponse(res: any): void {
    if (res?.code == 200) {
      this.handleSuccess(res?.message);
      this.performLocalLogout();
      this.publicService.recallProfileDataLocalStorage.next(true);
      this.router.navigate(['/home']);
    } else {
      this.handleError(res?.message);
    }
  }
  private performLocalLogout(): void {
    if (isPlatformBrowser(this.platformId)) {
      localStorage.removeItem(keys.prepareStepData);
      localStorage.removeItem(keys.saveTripData);
      localStorage.removeItem(keys.logged);
      localStorage.removeItem(keys.token);
      localStorage.removeItem(keys.userData);
      localStorage.removeItem(keys.profileData);
      localStorage.removeItem(keys.userLoginData);
      this.publicService.recallProfileDataLocalStorage.next(true);
      this.authFirebaseService.logout();
    }
  }

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
  }
  private setMessage(message: string, type: string): void {
    this.alertsService.openToast(type, message);
    this.publicService?.show_loader?.next(false);
  }

  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && !subscription?.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
