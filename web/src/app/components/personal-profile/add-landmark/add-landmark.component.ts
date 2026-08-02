import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
import { AddNewLandmarkComponent } from './add-new-landmark/add-new-landmark.component';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-add-landmark',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './add-landmark.component.html',
  styleUrls: ['./add-landmark.component.scss']
})
export class AddLandmarkComponent {

  constructor(
    private dialogService: DialogService,
    public publicService: PublicService,
    private ref: DynamicDialogRef
  ) { }

  addNew(type?: any): void {
    this.ref.close();
    if (type == 'trip') {
      this.startTrip();
    } else {
      const ref = this.dialogService?.open(AddNewLandmarkComponent, {
        data: type,
        dismissableMask: true,
        header: this.publicService.translateTextFromJson('profile.addNewLandmark'),
        width: '40%',
        styleClass: 'custom-modal',
      });
    }
  }
  startTrip(): void {
    const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
      width: '65%',
      height: '100vh',
      dismissableMask: false,
      styleClass: 'start-trip-dialog',
      baseZIndex: 100011,
    });
  }
}
