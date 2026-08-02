import { VerificationOtpComponent } from '../verification-otp/verification-otp.component';
import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID, inject } from '@angular/core';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { patterns } from '../../../../modules/shared/configs/patternValidation';
import { DynamicDialogRef, DialogService } from 'primeng/dynamicdialog';
import { SharedModule } from '../../../../modules/shared/shared.module';
import { LoginComponent } from '../login/login.component';
import { AuthService } from '../../../../services/auth.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { isPlatformBrowser } from '@angular/common';
import { PasswordModule } from 'primeng/password';
import { CheckboxModule } from 'primeng/checkbox';
import { catchError, tap } from 'rxjs/operators';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { MessageService } from 'primeng/api';
import { Subscription } from 'rxjs';
import { AlertsService } from 'src/app/services/alerts.service';
import { TranslateModule } from '@ngx-translate/core';
import { LoginPopupComponent } from '../login-popup/login-popup.component';
import { VerificationOtpPopupComponent } from '../verification-otp-popup/verification-otp-popup.component';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';


@Component({
  selector: 'app-forget-password-popup',
  templateUrl: './forget-password-popup.component.html',
  styleUrls: ['./forget-password-popup.component.scss'],
  standalone: true,
  imports: [
    CommonModule, RouterModule, TranslateModule, FormsModule, ReactiveFormsModule,
    PasswordModule, InputTextModule, CheckboxModule, LazyLoadImageDirective
  ]
})
export class ForgetPasswordPopupComponent implements OnInit, OnDestroy {
  private subscriptions: Subscription[] = [];

  emailAddress: string;
  isLoadingBtn: boolean = false;
  currentLang: string;

  platformId = inject(PLATFORM_ID);
  messageService = inject(MessageService);
  alertsService = inject(AlertsService);
  dialogService = inject(DialogService);
  publicService = inject(PublicService);
  authService = inject(AuthService);
  ref = inject(DynamicDialogRef);
  fb = inject(FormBuilder);

  forgetPasswordForm = this.fb.group({
    email: ['', [Validators.required, Validators.pattern(patterns?.email)]]
  }, { updateOn: "blur" });
  get formControls(): any {
    return this.forgetPasswordForm?.controls;
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLang = this.publicService.getCurrentLanguage();
      this.publicService.closeModal.subscribe((res: boolean) => {
        if (res == true) {
          this.ref.close();
          this.publicService.closeModal.next(false);
        }
      });
    }
  }
  // Start Forget Password Functions
  submit(): void {
    this.messageService?.clear();
    if (isPlatformBrowser(this.platformId)) {
      if (!this.forgetPasswordForm?.valid) {
        this.publicService.validateAllFormFields(this.forgetPasswordForm);
        return;
      }
      this.isLoadingBtn = true;
      const data = { emailAddress: this.forgetPasswordForm?.value?.email };
      this.emailAddress = data.emailAddress;

      const forgetSubscription = this.authService?.forgetPassword(data)?.subscribe({
        next: (res) => {
          this.handleForgetPasswordResponse(res);
        },
        error: (err) => this.handleError(err)
      });

      this.subscriptions.push(forgetSubscription);
    }
  }
  handleForgetPasswordResponse(res: any) {
    if (res?.code !== 200) {
      this.alertsService?.openToast('error', res?.message || '');
      this.isLoadingBtn = false;
      return;
    }
    this.forgetPasswordForm?.reset();
    this.verificationOtp();
  }
  // End Forget Password Functions

  verificationOtp(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);

    const ref = this.dialogService?.open(VerificationOtpPopupComponent, {
      width: '65%',
      height: '700px',
      data: { email: this.emailAddress },
      styleClass: 'auth-dialog'
    });

    ref?.onClose?.subscribe(() => this.publicService?.toggleBodyScroll(true));
  }
  goToLogin(): void {
    if (!isPlatformBrowser(this.platformId)) return;
    this.ref?.close();
    this.publicService?.toggleBodyScroll(false);

    const ref = this.dialogService?.open(LoginPopupComponent, {
      width: '60%',
      height: '700px',
      styleClass: 'auth-dialog'
    });

    ref?.onClose?.subscribe(() => this.publicService?.toggleBodyScroll(true));
  }
  close(): void {
    this.ref.close();
  }

  // Handle Api Errors
  private handleError(error: any): void {
    if (isPlatformBrowser(this.platformId)) {
      error ? this.alertsService?.openToast('error', error || this.publicService.translateTextFromJson('general.errorOccur')) : '';
    }
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
