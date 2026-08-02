import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { keys } from '../../../modules/shared/configs/localstorage-key';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { TranslateModule } from '@ngx-translate/core';
import { staticPlacesDataAr, staticPlacesDataRu, staticPlacesDataZh, staticPlacesDataEn } from 'src/app/components/places/store/staticData';
import { PublicService } from '../../../modules/shared/services/public.service';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-start-trip-steps',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './start-trip-steps.component.html',
  styleUrls: ['./start-trip-steps.component.scss']
})
export class StartTripStepsComponent {
  currentLanguage: any;
  tripSteps: any = [];
  currentStep: number = 1;

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private dialogService: DialogService,
    private publicService: PublicService,
    private ref: DynamicDialogRef
  ) { }
  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.getTripSteps();
  }

  getTripSteps(): void {
    this.tripSteps = this.currentLanguage == 'ar' ? staticPlacesDataAr?.tripSteps : this.currentLanguage == 'ru' ? staticPlacesDataRu?.tripSteps : this.currentLanguage == 'zh' ? staticPlacesDataZh?.tripSteps : staticPlacesDataEn?.tripSteps;
  }

  next(): void {
    if (this.currentStep <= 5) {
      this.currentStep += 1;
    }
  }
  back(): void {
    if (this.currentStep >= 1) {
      this.currentStep -= 1;
    }
  }
  startTrip(): void {
    this.ref.close();
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      // height: '87vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }
}
