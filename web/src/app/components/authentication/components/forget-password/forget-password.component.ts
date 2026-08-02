import { VerificationOtpComponent } from '../verification-otp/verification-otp.component';
import { Component, OnInit, OnDestroy, Inject, PLATFORM_ID } from '@angular/core';
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


@Component({
  selector: 'app-forget-password',
  templateUrl: './forget-password.component.html',
  styleUrls: ['./forget-password.component.scss'],
  standalone: true,
  imports: [
    CommonModule, RouterModule, TranslateModule, FormsModule, ReactiveFormsModule,
    PasswordModule, InputTextModule, CheckboxModule
  ]
})
export class ForgetPasswordComponent implements OnInit, OnDestroy {
  private subscriptions: Subscription[] = [];

  emailAddress: string;
  isLoadingBtn: boolean = false;

  forgetPasswordForm = this.fb.group({
    email: ['', [Validators.required, Validators.pattern(patterns?.email)]]
  }, { updateOn: "blur" });
  get formControls(): any {
    return this.forgetPasswordForm?.controls;
  }

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private messageService: MessageService,
    private alertsService: AlertsService,
    private dialogService: DialogService,
    public publicService: PublicService,
    private authService: AuthService,
    private ref: DynamicDialogRef,
    private fb: FormBuilder
  ) { }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
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

      const forgetSubscription: any = this.authService?.forgetPassword(data)?.pipe(
        tap(res => this.handleForgetPasswordResponse(res)),
        catchError(async (err) => this.handleError(err))
      ).subscribe();
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

    const ref = this.dialogService?.open(VerificationOtpComponent, {
      width: '80%',
      data: { email: this.emailAddress },
      styleClass: 'auth-dialog'
    });

    ref?.onClose?.subscribe(() => this.publicService?.toggleBodyScroll(true));
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
