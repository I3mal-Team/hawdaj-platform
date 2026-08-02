import { Component } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { DialogService } from 'primeng/dynamicdialog';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-home-trip-v2',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage],
  templateUrl: './home-trip-v2.component.html',
  styleUrls: ['./home-trip-v2.component.scss']
})
export class HomeTripV2Component {
  constructor(
    private dialogService: DialogService
  ) { }

  startTrip(): void {
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
