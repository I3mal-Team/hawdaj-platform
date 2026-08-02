import { MessageService } from 'primeng/api';
import { PublicService } from '../../../../modules/shared/services/public.service';
import { FormBuilder, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ChangeDetectionStrategy, Component, EventEmitter, inject, Input, Output, PLATFORM_ID, SimpleChanges } from '@angular/core';
import { PlacesService } from '../../../../services/places.service';
import { AlertsService } from 'src/app/services/alerts.service';
import { MatStepperModule } from '@angular/material/stepper';
import { MatSliderModule } from '@angular/material/slider';
import { TranslateModule } from '@ngx-translate/core';
import { Subscription, catchError, tap } from 'rxjs';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { Price } from '../../interfaces';
import { LazyLoadImageDirective } from "src/app/modules/shared/directives/lazy-load-image.directive";
import { StripHtmlPipe } from "src/app/Common/pipes/strip-html.pipe";
@Component({
  selector: 'create-trip-details',
  standalone: true,
  imports: [CommonModule, MatStepperModule, TranslateModule, FormsModule, ReactiveFormsModule, MatSliderModule, LazyLoadImageDirective, StripHtmlPipe],
  templateUrl: './create-trip-details.component.html',
  styleUrls: ['./create-trip-details.component.scss'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class CreateTripDetailsComponent {
  private subscriptions: Subscription[] = [];
  private isBrowser: boolean;
  currentLanguage: string;

  @Input() totalDays: any;
  @Output() getTripDetails = new EventEmitter();
  @Output() backToPreviousStep = new EventEmitter();

  pricesList: Price[] = [];
  pricesIds: any = [];

  isLoadingPrices: any;

  private messageService = inject(MessageService);
  private placesService = inject(PlacesService);
  private alertsService = inject(AlertsService);
  private publicService = inject(PublicService);
  private fb = inject(FormBuilder);
  private platformId = inject(PLATFORM_ID);

  priceForm: any = this.fb?.group({
    price: [null, Validators.required]
  });

  tripPlanForm: any = this.fb?.group({
    visitPlace: ['trip', Validators.required],
    placesNum: [null, Validators.required],
  });

  vehicleForm: any = this.fb?.group({
    vehicleType: ['', Validators.required],
  });
  visitedNum: number = 4;
  stepVisitedNum: number = 1;


  constructor() {
    this.isBrowser = isPlatformBrowser(this.platformId);
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.currentLanguage = this.publicService.getCurrentLanguage();
    }

    if (this.isBrowser) {
      this.getPrices();
    }
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['totalDays'] && changes['totalDays'].currentValue !== changes['totalDays'].previousValue) {
      this.updatePlacesNum();
    }
  }

  private updatePlacesNum(): void {
    this.tripPlanForm?.controls?.placesNum?.setValue(this.totalDays * 1);
  }

  getPrices(): void {
    this.isLoadingPrices = true;
    let priceSubscription: any = this.placesService?.getPrices()?.pipe(
      tap(res => this.handleSuccessPrices(res)),
      catchError(err => this.handleError(err))
    ).subscribe();
    this.subscriptions.push(priceSubscription);
  }

  private handleSuccessPrices(res: any): void {
    if (res?.code == 200) {
      this.isLoadingPrices = false;
      this.processPricesData(res.data);
    } else {
      this.handleError(res?.error?.message || 'An error has occurred');
    }
  }

  private processPricesData(data: any[]): void {
    const pricesDescriptions = this.placesService?.getPricesDescriptions();
    const arr: any = [];
    data.forEach((item: any) => {
      item['isSelected'] = true;
      switch (item?.id) {
        case 1:
          item['description'] = pricesDescriptions?.free;
          break;
        case 2:
          item['description'] = pricesDescriptions?.high;
          break;
        case 3:
          item['description'] = pricesDescriptions?.low;
          break;
        case 4:
          item['description'] = pricesDescriptions?.medium;
          break;
      }
      arr.push(item?.id);
      this.pricesList?.push(item);
    });
    this.pricesIds = arr;
    this.priceForm?.controls?.price?.setValue(this.pricesIds);
  }
  private handleError(err: any): any {
    this.setErrorMessage(err || 'An error has occurred');
  }
  private setErrorMessage(message: string): void {
    if (this.isBrowser && message) {
      message ? this.alertsService?.openToast('error', message) : ''
    }
    this.isLoadingPrices = false
  }

  toggleItemSelection(item: any): void {
    item.isSelected = !item.isSelected; // Toggle the item's selection
    this.selectPrices(); // Call the selectPrices function to update the selected items
  }

  items = [1, 2, 3, 4];
  activeIndex: number | null = 0;

  updateColors(index: number): void {
    this.activeIndex = index;
  }

  selectPrices(): void {
    let arr: any = [];
    this.pricesList?.forEach((item: any) => {
      if (item?.isSelected) {
        arr?.push(item?.id);
      }
    });
    this.pricesIds = arr;

    // Assuming priceForm is a FormGroup and price is a control within it
    this.priceForm?.controls?.price?.setValue(this.pricesIds);
  }

  selectVehicle(vehicleType: string): void {
    this.vehicleForm.get('vehicleType')?.setValue(vehicleType);
  }


  changeVisitNumValue(type?: any): void {
    if (type == 'increment') {
      this.visitedNum < 4 ? this.visitedNum += this.stepVisitedNum : '';
    } else {
      if (this.visitedNum > 1) {
        this.visitedNum -= this.stepVisitedNum;
      }
    }
    this.tripPlanForm?.controls?.placesNum?.setValue(this.visitedNum);
  }

  changeNumValue(slider: any): void {
    this.visitedNum = +slider?.value;
    this.tripPlanForm?.controls?.placesNum?.setValue(this.visitedNum);
  }
  getInputValue(event: any): void {
    this.tripPlanForm?.controls?.placesNum?.setValue(event.target.value)
  }

  sendDetailsEmitter(): void {
    this.getTripDetails.emit({
      type: this.tripPlanForm?.value?.visitPlace,
      placesNum: this.tripPlanForm?.value?.placesNum,
      vehicleType: this.vehicleForm?.value?.vehicleType,
      price: this.pricesIds
    })
  }

  next(stepper: any): void {
    this.messageService.clear();
    switch (stepper?.selectedIndex) {
      case 0:
        this.handleStepOne(stepper);
        break;
      case 2:
        this.handleStepThree(stepper);
        break;
      default:
        stepper?.next();
        break;
    }
  }

  handleStepOne(stepper: any): void {
    if (this.pricesIds?.length == 0) {
      this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.pleaseSelectCostType'));
    } else {
      stepper?.next();
    }
  }

  handleStepThree(stepper: any): void {
    if (this.vehicleForm?.value?.vehicleType) {
      this.sendDetailsEmitter();
    } else {
      if (!this.vehicleForm?.value?.vehicleType) {
        this.alertsService?.openToast('info', this.publicService?.translateTextFromJson('warn.pleaseSelectVehicleType'));
      }
    }
  }

  back(stepper: any): void {
    stepper.previous();
    // this.sendDetailsEmitter();
  }
  changeStep(event: any): void {
    // this.sendDetailsEmitter();
  }
  backToPrevStep(): void {
    this.backToPreviousStep.emit();
  }
  ngOnDestroy(): void {
    this.subscriptions?.forEach((sb) => sb?.unsubscribe());
  }
}
