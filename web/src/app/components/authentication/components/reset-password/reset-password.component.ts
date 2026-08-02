import { ConfirmPasswordValidator } from '../../../../modules/shared/configs/confirmPasswordValidator';
import { DialogService, DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from '../../../../modules/shared/configs/patternValidation';
import { SharedModule } from '../../../../modules/shared/shared.module';
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { LoginComponent } from '../login/login.component';
import { AuthService } from '../../../../services/auth.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { PasswordModule } from 'primeng/password';
import { catchError, tap } from 'rxjs/operators';
import { RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  standalone: true,
  imports: [TranslateModule, FormsModule, ReactiveFormsModule, RouterModule, PasswordModule, InputTextModule, CommonModule],
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit {
  private subscriptions: Subscription[] = [];
  isLoadingBtn: boolean = false;
  isPasswordChange: boolean = false;

  emaillAddress: string;
  code: string | number;

  resetPasswordForm = this.fb.group(
    {
      password: ['', {
        validators: [Validators.required, Validators.pattern(patterns?.password)],
        updateOn: 'blur'
      }],
      confirmPassword: ['', {
        validators: [Validators.required, Validators.pattern(patterns?.password)],
        updateOn: 'blur'
      }]
    },
    {
      validator: ConfirmPasswordValidator.MatchPassword
    }
  );
  get formControls(): any {
    return this.resetPasswordForm?.controls;
  }

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogConfig: DynamicDialogConfig,
    private dialogService: DialogService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    public authService: AuthService,
    private ref: DynamicDialogRef,
    private fb: FormBuilder
  ) { }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    if (isPlatformBrowser(this.platformId)) {
      this.initializePasswordAutocomplete();
    }

    this.code = this.dialogConfig?.data?.code;
    this.emaillAddress = this.dialogConfig?.data?.email;
  }
  private initializePasswordAutocomplete(): void {
    setTimeout(() => {
      const passwordInput: any = document.querySelector('#p-password input');
      if (passwordInput) {
        passwordInput.setAttribute('autocomplete', 'current-password');
      }
    }, 0);
  }
  onFocusConfirmPassword(): void {
    this.isPasswordChange = false;
  }

  // Start Reset Password Functions
  submit(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (!this.resetPasswordForm?.valid) {
        this.publicService.validateAllFormFields(this.resetPasswordForm);
        return;
      }

      this.isLoadingBtn = true;
      let data = {
        emailAddress: this.emaillAddress,
        code: this.code,
        password: this.resetPasswordForm?.value?.password,
        password_confirmation: this.resetPasswordForm?.value?.confirmPassword
      };

      const resetSubscription: any = this.authService?.resetPassword(data)?.pipe(
        tap(res => this.handleResetPasswordResponse(res)),
        catchError(async (err) => this.handleError(err))
      ).subscribe();
      this.subscriptions.push(resetSubscription);
    }
  }
  handleResetPasswordResponse(res: any) {
    if (res?.code !== 200) {
      this.handleError(res?.message);
      this.isLoadingBtn = false;
      this.publicService.show_loader.next(false);
      return;
    }

    this.handleSuccess(res?.message);
    this.resetPasswordForm?.reset();
    this.ref?.close();
    this.goToLogin();
    this.isLoadingBtn = false;
  }
  goToLogin(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);

    const ref = this.dialogService?.open(LoginComponent, {
      width: '80%',
      styleClass: 'auth-dialog'
    });

    ref?.onClose?.subscribe(() => this.publicService?.toggleBodyScroll(true));
  }
  // End Reset Password Functions

  close(): void {
    this.ref.close();
  }

  // Handle Api Errors Or Success
  private handleError(error: any): void {
    if (isPlatformBrowser(this.platformId)) {
    }
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
