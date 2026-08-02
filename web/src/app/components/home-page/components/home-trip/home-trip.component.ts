import { TranslateModule } from '@ngx-translate/core';
import { DialogService } from 'primeng/dynamicdialog';
import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-home-trip',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './home-trip.component.html',
  styleUrls: ['./home-trip.component.scss']
})
export class HomeTripComponent {

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
