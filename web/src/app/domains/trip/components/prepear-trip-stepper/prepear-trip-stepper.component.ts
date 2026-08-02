import { CommonModule, isPlatformBrowser } from "@angular/common";
import { Component, ChangeDetectionStrategy, ChangeDetectorRef, inject, PLATFORM_ID } from "@angular/core";
import { FormBuilder, FormsModule, Validators } from "@angular/forms";
import { MatStepper, MatStepperModule } from "@angular/material/stepper";
import { TranslateModule } from "@ngx-translate/core";
import { MessageService } from "primeng/api";
import { DynamicDialogRef } from "primeng/dynamicdialog";
import { Subscription, tap, catchError } from "rxjs";
import { keys } from "src/app/modules/shared/configs/localstorage-key";
import { PublicService } from "src/app/modules/shared/services/public.service";
import { AlertsService } from "src/app/services/alerts.service";
import { Router } from "@angular/router";
import { TripsFacade } from "../../facades";
import { TripRoutesEnum } from "../../constants";
import { CreateTripDetailsComponent } from "../create-trip-details";
import { GeoSaudiRegionMapComponent } from "../geo-saudi-region-map";
import { DatepickerRangeComponent } from "../datepicker-range";
import { TripCategoriesComponent } from "../trip-categories";
import { getKeyFromValue } from "../../enums";
import * as moment from 'moment';


@Component({
  selector: 'app-prepear-trip-stepper',
  standalone: true,
  imports: [
    CreateTripDetailsComponent,
    GeoSaudiRegionMapComponent,
    FormsModule,
    DatepickerRangeComponent,
    TripCategoriesComponent,
    MatStepperModule,
    TranslateModule,
    CommonModule
  ],
  templateUrl: './prepear-trip-stepper.component.html',
  styleUrls: ['./prepear-trip-stepper.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class PrepearTripStepperComponent {
  private subscriptions: Subscription[] = [];
  currentLanguage: string;

  isStartNowTrip: boolean = false;
  region1: string;
  region2: string;

  // Step 1
  fromDate: any;
  toDate: any;
  totalNumOfDays: any;


  private messageService = inject(MessageService);
  private publicService = inject(PublicService);
  private alertsService = inject(AlertsService);
  private facade = inject(TripsFacade);
  private ref = inject(DynamicDialogRef);
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private platformId = inject(PLATFORM_ID);
  private cdr = inject(ChangeDetectorRef);

  dateForm = this.fb?.group({
    date: [null, Validators.required]
  });

  // Step 2
  startAreaForm = this.fb?.group({
    startArea: [null, Validators.required]
  });
  startArea: any;

  // Step 3
  endAreaForm = this.fb?.group({
    endArea: [null, Validators.required]
  });
  endArea: any = null;

  tripDetails: any;
  categoriesIds: any;
  isTripInProceedStage: boolean = false;
  isTripReady: boolean = false;
  errorMessage: string | null = null;
  private errorCheckInterval: any;

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
  }

  startNowTrip(): void {
    this.isStartNowTrip = true;
    this.isTripInProceedStage = true;
  }

  close(): void {
    this.ref.close();
  }

  nextStep(stepper: any, event: any): void {
    this.isTripInProceedStage = false;
    this.fromDate = event?.startDate;
    this.toDate = event?.endDate;
    this.totalNumOfDays = event?.totalNumOfDays;
    this.dateForm?.controls?.date?.setValue(event);
    stepper.next();
  }

  back(stepper: any): void {
    stepper.previous();
  }
  next(stepper: any): void {
    this.messageService.clear();
    switch (stepper?.selectedIndex) {
      case 1:
        this.handleStepOne(stepper);
        break;
      case 2:
        this.handleStepTwo(stepper);
        break;
      case 3:
        this.handleStepThree(stepper);
        break;
      default:
        stepper?.next();
        break;
    }
  }
  private handleStepOne(stepper: any): void {
    if (this.startArea) {
      stepper?.next();
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.chooseStartArea'));
    }
  }
  private handleStepTwo(stepper: any): void {
    if (this.endArea) {
      stepper?.next();
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.chooseEndArea'));
    }
  }
  private handleStepThree(stepper: any): void {
    if (this.tripDetails?.price?.length > 0 && this.tripDetails?.type && this.tripDetails.vehicleType) {
      stepper?.next();
    } else {
      if (this.tripDetails?.price?.length == 0) {
        this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.pleaseSelectCostType'));
      }
      if (!this.tripDetails?.vehicleType) {
        this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.pleaseSelectVehicleType'));
      }
    }
  }

  startRegionAreaData(data: any): void {
    this.startArea = null;
    if (data?.selectedRegionInfo?.id != null) {
      this.startArea = data?.selectedRegionInfo;
      this.startAreaForm?.controls?.startArea?.setValue(data?.selectedRegionInfo);
    }
    this.region1 = getKeyFromValue(+this.startArea?.id);
  }

  endRegionAreaData(data: any): void {
    this.endArea = null;
    if (data?.selectedRegionInfo?.id != null) {
      this.endArea = data?.selectedRegionInfo;
      this.endAreaForm?.controls?.endArea?.setValue(data?.selectedRegionInfo);
    }
    this.region2 = getKeyFromValue(+this.endArea.id);
  }

  getTripDetails(event: any, stepper: any): void {
    this.tripDetails = event
    if (this.tripDetails?.vehicleType) {
      stepper.next();
    }
  }
  selectedCategory(event: any): void {
    this.categoriesIds = event?.categoriesIds;
  }

  confirm(): void {
    this.messageService.clear();
    if (this.categoriesIds?.length > 0) {
      this.submit()
    } else {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('createTrip.selectCategory'));
    }
  }
  submit(): void {
    this.errorMessage = null;
    this.isTripInProceedStage = true;
    const request = this.prepareDataObject();
    this.sendTripData(request);
  }

  private prepareDataObject() {
    const placesPerDay = this.tripDetails?.placesNum && this.totalNumOfDays
      ? Math.round(this.tripDetails.placesNum / this.totalNumOfDays)
      : (this.tripDetails?.placesNum || 4);

    return {
      start_date: moment(this.fromDate)?.format('YYYY-MM-DD'),
      end_date: moment(this.toDate)?.format('YYYY-MM-DD'),
      start_region_id: this.startArea?.id,
      end_region_id: this.endArea?.id,
      places_per_day: placesPerDay,
      vehicleType: this.tripDetails?.vehicleType,
      price_range: this.tripDetails?.price || [],
      categories: this.categoriesIds || []
    };
  }

  token: any;
  private sendTripData(request: any): void {
    // Clear any existing error check interval
    if (this.errorCheckInterval) {
      clearInterval(this.errorCheckInterval);
    }
    this.errorMessage = null;

    // Check for errors periodically
    this.errorCheckInterval = setInterval(() => {
      const errorMsg = this.facade.prepareTripErrorMessage();
      if (errorMsg) {
        this.handleError({ message: errorMsg });
        clearInterval(this.errorCheckInterval);
      }
    }, 100);

    this.facade.prepareTrip(request, (data) => {
      if (this.errorCheckInterval) {
        clearInterval(this.errorCheckInterval);
      }
      this.handleSuccess(data, request);
    });
  }

  private handleSuccess(data: any, dataObj: any): void {
    this.errorMessage = null;
    this.token = data?.token;
    if (isPlatformBrowser(this.platformId)) {
      window.localStorage?.setItem(keys?.prepareStepData, JSON.stringify(dataObj));
    }
    // this.alertsService?.openToast('success', this.publicService.translateTextFromJson('general.tripPrepared'));
    this.isTripReady = true;
    this.isTripInProceedStage = false;
    this.cdr.detectChanges(); // Force change detection
  }

  private handleError(err: any): void {
    if (this.errorCheckInterval) {
      clearInterval(this.errorCheckInterval);
    }
    const fallbackMsg = this.publicService?.translateTextFromJson('general.errorOccurred') || 'An error has occurred';
    console.log('err', err);
    this.errorMessage = err?.message || fallbackMsg;
    this.isTripReady = false;
    this.isTripInProceedStage = true;
    this.cdr.detectChanges(); // Force change detection
  }

  restartPreparation(stepper: MatStepper): void {
    this.errorMessage = null;
    this.isTripInProceedStage = false;
    this.isTripReady = false;
    this.isStartNowTrip = false;
    this.token = null;
    this.fromDate = null;
    this.toDate = null;
    this.totalNumOfDays = null;
    this.startArea = null;
    this.endArea = null;
    this.region1 = '';
    this.region2 = '';
    this.tripDetails = null;
    this.categoriesIds = [];

    this.dateForm.reset();
    this.startAreaForm.reset();
    this.endAreaForm.reset();

    stepper?.reset();
  }

  startNow(): void {
    this.ref.close();
    if (isPlatformBrowser(this.platformId) && this.token) {
      this.router.navigate([`/${TripRoutesEnum.TRIP1}/${TripRoutesEnum.SAVE_TRIP_DETAILS}`, this.token]);
    } else {
      console.error('Token is missing or invalid');
    }
  }

  ngOnDestroy(): void {
    this.subscriptions?.forEach((sb) => sb?.unsubscribe());
    if (this.errorCheckInterval) {
      clearInterval(this.errorCheckInterval);
    }
  }
}
