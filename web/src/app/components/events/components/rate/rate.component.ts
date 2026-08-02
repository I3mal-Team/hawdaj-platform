import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { EventsService } from 'src/app/services/events.service';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { AlertsService } from 'src/app/services/alerts.service';
import { patterns } from 'src/app/modules/shared/configs/patternValidation';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { RatingModule } from 'primeng/rating';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { Subscription } from 'rxjs';

@Component({
  standalone: true,
  imports: [
    ReactiveFormsModule,
    TranslateModule,
    RatingModule,
    CommonModule,
    FormsModule
  ],
  selector: 'app-rate',
  templateUrl: './rate.component.html',
  styleUrls: ['./rate.component.scss']
})
export class RateComponent {
  private unsubscribe: Subscription[] = [];

  rateForm = this.fb.group(
    {
      rate: [null, [Validators.required]],
      name: ['', { validators: [Validators.required, Validators.minLength(3), Validators.pattern('[a-zA-Z ]+')], updateOn: "blur" }],
      email: ['', { validators: [Validators.required, Validators.pattern(patterns?.email)], updateOn: "blur" }],
      rateText: ['', { validators: [Validators.required, Validators.minLength(10), Validators.pattern('[a-zA-Z ]+')], updateOn: "blur" }],
    },
  );
  get formControls(): any {
    return this.rateForm?.controls;
  }
  isLoadingBtn: boolean = false;
  isBrowser: boolean;

  constructor(
    private alertsService: AlertsService,
    private eventsService: EventsService,
    public publicService: PublicService,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef,
    private fb: FormBuilder,
    @Inject(PLATFORM_ID) private platformId: any
  ) { 
    this.isBrowser = isPlatformBrowser(this.platformId);
  }


  submit(): void {
    if (this.isBrowser) {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
  }
    if (this.rateForm?.valid) {
      this.publicService.show_loader.next(true);
      let formData: any = new FormData();
      formData.append('email', this.rateForm?.value?.email);
      formData.append('rate', this.rateForm?.value?.rate);
      formData.append('name', this.rateForm?.value?.name);
      formData.append('rateText', this.rateForm?.value?.rateText);
      formData.append('type', this.config?.data?.type);
      formData.append('parent_id', this.config?.data?.parentId);
      this.eventsService?.rateEvent(formData)?.subscribe(
        (res: any) => {
          if (res?.code == 200) {
            this.rateForm?.reset();
            this.ref?.close({ isAddReview: true });
            this.publicService.show_loader.next(false);
          } else {
            res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            this.publicService.show_loader.next(false);
          }
        },
        (err: any) => {
          err ? this.alertsService?.openToast('error', err) : ''
          this.publicService.show_loader.next(false);
        }
      );
    } else {
      this.publicService.validateAllFormFields(this.rateForm);
    }
  }

  close(): void {
    this.ref?.close();
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
