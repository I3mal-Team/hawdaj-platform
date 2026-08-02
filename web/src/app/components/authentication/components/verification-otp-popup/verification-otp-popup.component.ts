import { DynamicDialogRef, DynamicDialogConfig, DialogService } from 'primeng/dynamicdialog';
import { ChangeDetectorRef, Component, inject, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { ForgetPasswordComponent } from '../forget-password/forget-password.component';
import { ResetPasswordComponent } from '../reset-password/reset-password.component';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { SharedModule } from '../../../../modules/shared/shared.module';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { AuthService } from '../../../../services/auth.service';
import { FormBuilder, Validators } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { Subscription, tap } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { CodeInputComponent } from 'src/app/shared/components/code-input/code-input.component';
import { TranslateModule } from '@ngx-translate/core';
import { CountdownComponent } from './countdown/countdown.component';
import { ForgetPasswordPopupComponent } from '../forget-password-popup/forget-password-popup.component';
import { ResetPasswordPopupComponent } from '../reset-password-popup/reset-password-popup.component';

@Component({
  standalone: true,
  imports: [TranslateModule, RouterModule, CommonModule, CodeInputComponent, CountdownComponent],
  selector: 'app-verification-otp-popup',
  templateUrl: './verification-otp-popup.component.html',
  styleUrls: ['./verification-otp-popup.component.scss']
})
export class VerificationOtpPopupComponent implements OnInit {
  private subscriptions: Subscription[] = [];

  time: any = Date.now() + ((60 * 1000) * 1);
  isLoadingAction: boolean = false;
  isLoadingBtn: boolean = false;
  isLoading: boolean = false;
  isWaiting: boolean = false;
  codeLength: any;
  urlData: any;
  minute: any;
  email: any;
  currentLang: string;

  platformId = inject(PLATFORM_ID);
  dialogConfig = inject(DynamicDialogConfig);
  publicService = inject(PublicService);
  dialogService = inject(DialogService);
  alertsService = inject(AlertsService);
  dialogRef = inject(DynamicDialogRef);
  authService = inject(AuthService);
  cdr = inject(ChangeDetectorRef);
  fb = inject(FormBuilder);

  otpForm = this.fb.group({
    code: ['', [Validators.required, Validators.minLength(6)]]
  });
  get formControls() {
    return this.otpForm.controls;
  }

  clearCodeInput: boolean = false;

  ngOnInit(): void {
    this.currentLang = this.publicService.getCurrentLanguage();
    this.minute = this.time;
    this.email = this.dialogConfig.data?.email;
  }

  // Start Code Input Functions
  handleCodeChange(event: number | string | null) {
    this.codeLength = event;
  }
  handleCodeCompletion(event: number | string | null) {
    this.codeLength = event;
  }
  printTimeEnd(event: any): void {
    if (event?.end) {
      this.isWaiting = true;
    }
  }
  // End Code Input Functions

  // Start Resend Code Again Functions
  resendCode(activeLoading?: boolean): void {
    if (activeLoading === true) {
      this.isLoadingAction = true;
    }
    const data = {
      emailAddress: this.email
    };
    this.publicService.show_loader.next(true);
    const resendSubscription = this.authService?.forgetPassword(data)?.subscribe({
      next: (res) => {
        this.handleResendCodeResponse(res);
      },
      error: (err) => this.handleError(err)
    });

    this.subscriptions.push(resendSubscription);

    this.cdr.detectChanges();
  }
  private handleResendCodeResponse(res: any): void {
    this.isLoadingBtn = false;
    this.publicService.show_loader.next(false);
    this.isLoadingAction = false;

    if (res?.code === 200) {
      this.codeLength = null;
      this.isWaiting = true;
      this.clearCodeInput = true;  // Set to true to clear inputs
      this.minute = Date.now() + (60 * 1000);
      this.isWaiting = false;
    } else {
      res?.error?.message ? this.alertsService?.openToast('error', 'error', res?.error?.message || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    }
  }
  // End Resend Code Again Functions

  // Start Verify Code Functions
  verify(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.publicService.show_loader.next(true);
      let data: any = {
        emailAddress: this.email,
        code: this.codeLength
      };
      const verifySubscription = this.authService?.validateResetCode(data)?.subscribe({
        next: (res) => {
          this.handleResponseVerify(res);
        },
        error: (err) => this.handleError(err)
      });
      this.subscriptions.push(verifySubscription);
    }
  }
  handleResponseVerify(res: any) {
    if (res?.code !== 200) {
      this.publicService.show_loader.next(false);
      this.handleError(res?.message);
      return;
    }
    this.clearCodeInput = true;  // Set to true to clear inputs
    this.goToResetPassword();
    this.publicService.show_loader.next(false);
  }
  // End Verify Code Functions

  goToResetPassword(): void {
    this.publicService?.toggleBodyScroll(false);
    const ref = this?.dialogService?.open(ResetPasswordPopupComponent, {
      width: '60%',
      height: '700px',
      styleClass: 'auth-dialog',
      data: {
        email: this.email,
        code: this.codeLength
      }
    });
    this.dialogRef?.close();
    ref?.onClose?.subscribe((res: any) => {
      this.publicService?.toggleBodyScroll(true);
    });
  }
  goToForgetPassword(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.dialogRef?.close();
    this.publicService?.toggleBodyScroll(false);

    const ref = this.dialogService?.open(ForgetPasswordPopupComponent, {
      width: '60%',
      height: '700px',
      styleClass: 'auth-dialog'
    });

    ref?.onClose?.subscribe(() => this.publicService?.toggleBodyScroll(true));
  }
  close(): void {
    this.dialogRef.close();
  }

  // Handle Api Errors
  private handleError(error: any): void {
    error ? this.alertsService?.openToast('error', error || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    this.isLoadingBtn = false;
    this.isLoadingAction = false;
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
