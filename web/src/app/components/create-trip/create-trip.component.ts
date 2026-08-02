import { GeoSaudiRegionMapComponent } from './components/geo-saudi-region-map/geo-saudi-region-map.component';
import { CreateTripDetailsComponent } from './components/create-trip-details/create-trip-details.component';

import { TripCategoriesComponent } from './components/trip-categories/trip-categories.component';
import { PublicService } from '../../modules/shared/services/public.service';
import { TripsService } from '../../services/trips.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from '../../modules/shared/configs/localstorage-key';
import { Component, inject, Inject, OnInit, PLATFORM_ID } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { MatStepperModule } from '@angular/material/stepper';
import { FormBuilder, Validators } from '@angular/forms';
import { DynamicDialogRef } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { MessageService } from 'primeng/api';
import { Router } from '@angular/router';
import { DatepickerRangeComponent } from './components/datepicker-range/datepicker-range.component';
import { getKeyFromValue } from 'src/app/Common/enums/map.enum';

@Component({
  selector: 'create-trip',
  standalone: true,
  imports: [
    CreateTripDetailsComponent,
    GeoSaudiRegionMapComponent,
    DatepickerRangeComponent,
    TripCategoriesComponent,
    MatStepperModule,
    TranslateModule,
    CommonModule
  ],
  templateUrl: './create-trip.component.html',
  styleUrls: ['./create-trip.component.scss'],
})
export class CreateTripComponent implements OnInit {
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
  private tripsService = inject(TripsService);
  private ref = inject(DynamicDialogRef);
  private fb = inject(FormBuilder);
  private router = inject(Router);
  private platformId = inject(PLATFORM_ID);

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
    this.isTripInProceedStage = true;
    const dataObj: any = this.prepareDataObject();
    this.sendTripData(dataObj);
  }
  private prepareDataObject(): any {
    // const visitMultiplier = this.tripDetails?.visitPlace === 'trip' ? 1 : this.totalNumOfDays;
    return {
      daterange: `${this.publicService?.convertTimeOrDate(this.toDate, 'date5')}-${this.publicService?.convertTimeOrDate(this.fromDate, 'date5')}`,
      region1: this.startArea?.id,
      lat1: this.startArea?.lat,
      long1: this.startArea?.long,
      region2: this.endArea?.id,
      lat2: this.endArea?.lat,
      long2: this.endArea?.long,
      funny_place_per_day: this.tripDetails?.placesNum,
      type: this.tripDetails?.type,
      vehicleType: this.tripDetails?.vehicleType,
      price: this.tripDetails?.price,
      categories: this.categoriesIds
    };
  }
  private sendTripData(dataObj: any): void {
    const submitSubscription: any = this.tripsService?.prepareTrip(dataObj)?.pipe(
      tap(res => this.handleSuccessSubmitted(res, dataObj)),
      catchError(async (err) => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(submitSubscription);
  }
  private handleSuccessSubmitted(res: any, dataObj: any): void {
    if (res?.code === 200) {
      this.handleSuccess(res, dataObj);
    } else {
      this.handleFailure(res);
    }
  }
  token: any;
  private handleSuccess(res: any, dataObj: any): void {
    this.token = res?.data?.token;
    if (isPlatformBrowser(this.platformId)) {
      // window.localStorage?.setItem(keys?.saveTripData, JSON.stringify(res?.data));
      window.localStorage?.setItem(keys?.prepareStepData, JSON.stringify(dataObj));
    }
    this.alertsService?.openToast('success', this.publicService.translateTextFromJson('general.tripPrepared'))
    this.isTripReady = true;
  }
  private handleFailure(res: any): void {
    this.isTripInProceedStage = false;
    res?.message ? this.alertsService?.openToast('error', res) : '';
  }
  private handleError(err: any): void {
    err ? this.alertsService?.openToast('error', err) : '';
    this.isTripInProceedStage = false;
  }

  startNow(): void {
    this.ref.close();
    if (isPlatformBrowser(this.platformId) && this.token) {
      this.router.navigate(['/trips/save-trip', this.token]);
    } else {
      console.error('Token is missing or invalid');
    }
  }

  ngOnDestroy(): void {
    this.subscriptions?.forEach((sb) => sb?.unsubscribe());
  }
}