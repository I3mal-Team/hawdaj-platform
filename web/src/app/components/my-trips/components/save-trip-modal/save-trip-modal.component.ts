import { AbstractControl, FormBuilder, FormsModule, ReactiveFormsModule, ValidationErrors, Validators } from '@angular/forms';
import { DynamicDialogRef, DynamicDialogConfig } from 'primeng/dynamicdialog';
import { Component, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { Subscription } from 'rxjs';
import { AlertsService } from 'src/app/services/alerts.service';
import { TripsService } from 'src/app/domains/trip/services/trips.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { PublicService } from 'src/app/modules/shared/services/public.service';
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
  selector: 'app-save-trip-modal',
  templateUrl: './save-trip-modal.component.html',
  styleUrls: ['./save-trip-modal.component.scss']
})
export class SaveTripModalComponent implements OnInit {
  private unsubscribe: Subscription[] = [];

  currentLoginInformation: any;

  tripData: any;
  finalPlaces: any = [];
  isLoading: boolean = false;

  noLeadingSpaceValidator(control: AbstractControl): ValidationErrors | null {
    const value = control.value;
    if (value && value.startsWith(' ')) {
      return { noLeadingSpace: true };
    }
    return null;
  }

  saveTripForm = this.fb.group(
    {
      title: ['', [Validators.required,
      Validators.minLength(3), Validators.pattern('[a-zA-Z\u0600-\u06FF ]+'), this.noLeadingSpaceValidator]],
    },
    { updateOn: "blur" }
  );
  get formControls(): any {
    return this.saveTripForm?.controls;
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
    if (isPlatformBrowser(this.platformId)) {
      if (JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')?.user) {
        this.currentLoginInformation = JSON.parse(window?.localStorage?.getItem(keys?.userLoginData) || '{}')?.user;
      }
    }
    this.finalPlaces = this.config?.data?.tripPlaces;
    this.tripData = this.config?.data?.tripData;
  }

  save(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.saveTripForm?.valid) {
        this.isLoading = true;
        this.publicService?.show_loader?.next(true);

        // Build items array from tripPlaces
        const items: number[][] = [];
        if (this.finalPlaces && Array.isArray(this.finalPlaces)) {
          this.finalPlaces.forEach((dayPlaces: any) => {
            if (dayPlaces?.placesItems && Array.isArray(dayPlaces.placesItems)) {
              const dayItems: number[] = dayPlaces.placesItems.map((place: any) => place.id);
              if (dayItems.length > 0) {
                items.push(dayItems);
              }
            }
          });
        }

        // Create FormData
        const formData = new FormData();
        formData.append('name', this.saveTripForm?.value?.title || '');
        formData.append('prepare_token', this.config?.data?.token || '');
        formData.append('items', JSON.stringify(items));

        this.tripsService.saveTrip(formData).subscribe({
          next: (res: any) => {
            this.isLoading = false;
            this.publicService?.show_loader?.next(false);

            if (res?.code === 200 || res?.code === 201) {
              this.saveTripForm?.reset();
              this.ref?.close({ isSave: true });
              this.alertsService?.openToast('success', this.publicService?.translateTextFromJson('general.tripCreated'));

              if (isPlatformBrowser(this.platformId)) {
                this.router?.navigate(['/trips/list']);
                localStorage?.removeItem(keys?.prepareStepData);
                localStorage?.removeItem(keys?.saveTripData);
              }
            } else {
              this.alertsService?.openToast('error', res || 'Error saving trip');
            }
          },
          error: (err: any) => {
            this.isLoading = false;
            this.publicService?.show_loader?.next(false);
            this.alertsService?.openToast('error', err || 'Error saving trip');
          }
        });
      } else {
        this.publicService.validateAllFormFields(this.saveTripForm);
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
