import { PublicService } from './../../../../modules/shared/services/public.service';
import { Component, ElementRef, EventEmitter, inject, Inject, Output, PLATFORM_ID, ViewChild } from '@angular/core';
import { DateService } from '../../../../services/date.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { keys } from './../../../../modules/shared/configs/localstorage-key';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { CalendarModule } from 'primeng/calendar';
import { FormsModule } from '@angular/forms';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'datepicker-range',
  standalone: true,
  imports: [
    TranslateModule,
    CalendarModule,
    CommonModule,
    FormsModule,
  ],
  templateUrl: './datepicker-range.component.html',
  styleUrls: ['./datepicker-range.component.scss']
})
export class DatepickerRangeComponent {
  currentLanguage: string;
  @Output() nextStep = new EventEmitter();

  startDate: string;  // Change to string for date input
  endDate: string;    // Change to string for date input
  minDate: string;

  isAppleDevice = false;
  private platformId = inject(PLATFORM_ID);
  private messageService = inject(MessageService);
  private publicService = inject(PublicService);
  private alertsService = inject(AlertsService);
  private dateService = inject(DateService);

  constructor() {
    const today = new Date();
    this.minDate = today.toISOString().split('T')[0];  // Set the minimum date to today's date in 'yyyy-mm-dd' format
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }
    this.isAppleDevice = this.isAppleDeviceFun();

  }

  confirmDate(): void {
    this.messageService.clear();
    if (this.startDate && this.endDate) {
      this.next();
    } else if (!this.endDate && !this.startDate) {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('trip.selectRange'));
    } else if (!this.startDate) {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('trip.selectStartDate'));
    } else if (!this.endDate) {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('trip.selectEndDate'));
    }

  }
  onStartDateChange(): void {
    if (this.endDate && new Date(this.startDate) > new Date(this.endDate)) {
      this.endDate = '';
    }
  }

  onEndDateClick(event?: Event): void {
    if (!this.startDate) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('trip.selectStartDateFirst'));
    }
  }

  onEndDateChange(): void {
    // This method can be used for additional validation if needed
  }

  reset(): void {
    this.startDate = null;
    this.endDate = null;
  }

  next(): void {
    this.nextStep.emit({
      startDate: this.startDate,
      endDate: this.endDate,
      totalNumOfDays: this.dateService.calculateTotalNumberOfDays(this.startDate, this.endDate)
    });
  }

  isAppleDeviceFun() {
    return /iPhone|iPad|iPod/i.test(navigator.userAgent);
  }
}

