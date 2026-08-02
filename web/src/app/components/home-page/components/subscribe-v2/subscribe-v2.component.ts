import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { MessageService } from 'primeng/api';
import { AlertsService } from 'src/app/services/alerts.service';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { HomeService } from 'src/app/services/home.service';
import { catchError, Subscription, tap } from 'rxjs';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';

@Component({
  selector: 'app-subscribe-v2',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    CommonModule,
    FormsModule,
  ],
  templateUrl: './subscribe-v2.component.html',
  styleUrls: ['./subscribe-v2.component.scss']
})
export class SubscribeV2Component {
  private subscriptions: Subscription[] = [];

  isLoadingBtn: boolean = false;

  constructor(
    private messageService: MessageService,
    private alertsService: AlertsService,
    public publicService: PublicService,
    private homeService: HomeService,
    private fb: FormBuilder,
    @Inject(PLATFORM_ID) private platformId: Object
  ) { }

  emailForm = this.fb.group(
    {
      email: ['', [Validators.required, Validators.pattern(patterns?.email)]],
    },
    { updateOn: "blur" }
  );
  get emailFormControls(): any {
    return this.emailForm?.controls;
  }

  // Start Send Email
  submit(): void {
    this.messageService.clear();
    if (this.emailForm?.valid) {
      this.isLoadingBtn = true;
      const data = {
        email: this.emailForm?.value?.email
      };
      this.subscribeEmail(data);
    } else {
      let error = '';
      if (this.emailFormControls?.email?.errors?.required) {
        error = 'validations.emailRequired';
      }
      if (this.emailFormControls?.email?.errors?.pattern) {
        error = 'validations.emailNotValid';
      }
      this.alertsService?.openToast('error', this.publicService.translateTextFromJson(error));
    }
  }
  private subscribeEmail(data: any): void {
    let subscribeSendEmail: Subscription = this.homeService?.subscribe(data).pipe(
      tap(res => this.handleAddEditBankSuccess(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(subscribeSendEmail);
  }
  private handleAddEditBankSuccess(response: any): void {
    if (response?.code == 200) {
      this.emailForm.reset();
      this.handleSuccess(response?.message);
    } else {
      this.handleError(response?.message);
    }
    this.isLoadingBtn = false;
  }
  // End Send Email

  /* --- Handle api requests messages --- */
  private handleSuccess(msg: any): any {
    this.setMessage(msg || this.publicService.translateTextFromJson('general.successRequest'), 'success');
  }
  private handleError(err: any): any {
    this.setMessage(err || this.publicService.translateTextFromJson('general.errorOccur'), 'error');
  }
  private setMessage(message: string, type: string): void {
    if (isPlatformBrowser(this.platformId)) {
    this.alertsService.openToast(type, message);
  }
  }
  ngOnDestroy(): void {
    this.subscriptions.forEach((subscription: Subscription) => {
      if (subscription && subscription.closed) {
        subscription.unsubscribe();
      }
    });
  }
}
