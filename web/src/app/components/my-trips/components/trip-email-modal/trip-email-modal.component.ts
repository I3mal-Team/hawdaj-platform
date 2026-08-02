import { patterns } from '../../../../modules/shared/configs/patternValidation';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { DynamicDialogRef, DynamicDialogConfig } from 'primeng/dynamicdialog';
import { keys } from '../../../../modules/shared/configs/localstorage-key';
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { TripsService } from '../../../../services/trips.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { InputTextModule } from 'primeng/inputtext';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    InputTextModule,
    RouterModule,
    SkeletonComponent
  ],
  selector: 'app-trip-email-modal',
  templateUrl: './trip-email-modal.component.html',
  styleUrls: ['./trip-email-modal.component.scss']
})
export class TripEmailModalComponent {
  private unsubscribe: Subscription[] = [];

  tripData: any;
  finalPlaces: any = [];

  emailForm = this.fb.group(
    {
      name: ['', {
        validators: [Validators.required, Validators.minLength(3), Validators.pattern('[a-zA-Z\u0600-\u06FF ]+')],
        updateOn: 'blur'
      }],
      title: ['', {
        validators: [Validators.required, Validators.minLength(3), Validators.pattern('[a-zA-Z\u0600-\u06FF ]+')],
        updateOn: 'blur'
      }],
      email: ['', {
        validators: [Validators.required, Validators.pattern(patterns?.email)],
        updateOn: 'blur'
      }],
    },
  );

  get formControls(): any {
    return this.emailForm?.controls;
  }

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private alertsService: AlertsService,
    private config: DynamicDialogConfig,
    public publicService: PublicService,
    private tripsService: TripsService,
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
    this.finalPlaces = this.config?.data?.tripPlaces;
    this.tripData = this.config?.data?.tripData;
  }

  save(): void {
    if (isPlatformBrowser(this.platformId)) {
      let allItemsIds;
      if (typeof this.tripData?.items === 'string') {
        allItemsIds = JSON.parse(this.tripData.items);
      } else {
        allItemsIds = this.tripData?.items;
      }

      if (this.emailForm?.valid) {
        this.publicService?.show_loader?.next(true);
        let data = {
          user_name: this.emailForm?.value?.name,
          name: this.emailForm?.value?.title,
          email: this.emailForm?.value?.email,
          item_per_day: this.tripData?.funny_place_per_day.toString(),
          days: this.tripData?.days?.toString(),
          items: JSON.stringify(allItemsIds),
          date: this.tripData?.start_date,
          region1: this.tripData?.region1,
          region2: this.tripData?.region2,
          start_date: this.tripData?.start_date,
          end_date: this.tripData?.end_date,
        };
        this.tripsService?.sendTripEmail(data)?.subscribe(
          (res: any) => {
            if (res?.code == 200) {
              this.emailForm?.reset();
              this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.sentSuccessfully'));
              this.ref?.close({});
              this.publicService?.show_loader?.next(false);
            } else {
              this.publicService?.show_loader?.next(false);
              res?.message ? this.alertsService?.openToast('error', res?.message) : '';
            }
          },
          (err: any) => {
            err ? this.alertsService?.openToast('error', err) : '';
            this.publicService?.show_loader?.next(false);
          }
        );
      } else {
        this.publicService.validateAllFormFields(this.emailForm);
      }
    }
  }

  close() {
    this.ref?.close();
  }

  ngOnDestroy(): void {
    this.unsubscribe?.forEach((sb) => sb?.unsubscribe());
  }
}
