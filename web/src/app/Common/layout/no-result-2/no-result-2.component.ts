import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { DialogService } from 'primeng/dynamicdialog';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-no-result-2',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './no-result-2.component.html',
  styleUrls: ['./no-result-2.component.scss']
})
export class NoResult2Component {
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
