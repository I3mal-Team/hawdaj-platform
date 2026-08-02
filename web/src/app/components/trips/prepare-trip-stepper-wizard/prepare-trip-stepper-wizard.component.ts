import { Component, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MenuItem } from 'primeng/api';
import { DialogModule } from 'primeng/dialog';
import { StepsModule } from 'primeng/steps';
import { ButtonModule } from 'primeng/button';
import { PublicService } from '../../../modules/shared/services/public.service';

@Component({
  selector: 'app-prepare-trip-stepper-wizard',
  standalone: true,
  imports: [CommonModule, DialogModule, StepsModule, ButtonModule],
  templateUrl: './prepare-trip-stepper-wizard.component.html',
  styleUrls: ['./prepare-trip-stepper-wizard.component.scss']
})
export class PrepareTripStepperWizardComponent {
  steps: MenuItem[] = [];
  activeStepIndex: number = 0;
  isMobileView: boolean = false;
  tripDates: Date[] | undefined;

  constructor(
    private _PublicService: PublicService
  ) { }

  ngOnInit(): void {
    this.steps = [
      { label: this._PublicService.translateTextFromJson('trip.startEndDate') },
      { label: this._PublicService.translateTextFromJson('trip.startArea') },
      { label: this._PublicService.translateTextFromJson('trip.endArea') },
      { label: this._PublicService.translateTextFromJson('trip.tripDetails') },
      { label: this._PublicService.translateTextFromJson('trip.tripCategories') },
      { label: this._PublicService.translateTextFromJson('trip.tripIsReady') }
    ];
    this.checkViewMode();
  }

  // Listen to window resize
  @HostListener('window:resize', [])
  checkViewMode() {
    this.isMobileView = window.innerWidth < 768;
    console.log(this.isMobileView);

  }

  nextStep() {
    if (this.activeStepIndex < this.steps.length - 1) {
      this.activeStepIndex++;
    }
  }

  prevStep() {
    if (this.activeStepIndex > 0) {
      this.activeStepIndex--;
    }
  }
}
