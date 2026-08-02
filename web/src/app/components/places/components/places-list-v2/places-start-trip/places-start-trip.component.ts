import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { DialogService } from 'primeng/dynamicdialog';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-places-start-trip',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './places-start-trip.component.html',
  styleUrls: ['./places-start-trip.component.scss']
})
export class PlacesStartTripComponent {
  private dialogService = inject(DialogService);

  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 10001,
    });
  }
}
