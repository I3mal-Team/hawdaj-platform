import { Component } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { DialogService } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-start-trip',
  standalone: true,
  imports: [CommonModule, TranslateModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './start-trip.component.html',
  styleUrls: ['./start-trip.component.scss']
})
export class StartTripComponent {
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
