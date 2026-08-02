// Modules
import { DynamicDialogRef, DialogService } from 'primeng/dynamicdialog';
import { CommonModule, isPlatformBrowser, Location } from '@angular/common';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
// Components
import { CreateTripComponent } from 'src/app/components/create-trip/create-trip.component';
// Servics
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { keys } from 'src/app/modules/shared/configs/localstorage-key';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { DropdownModule } from 'primeng/dropdown';
import { InputTextModule } from 'primeng/inputtext';
import { SkeletonComponent } from 'src/app/modules/shared/components/skeleton/skeleton.component';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    InputTextModule,
    RouterModule,
    SkeletonComponent
  ],
  selector: 'app-create-trip-modal',
  templateUrl: './create-trip-modal.component.html',
  styleUrls: ['./create-trip-modal.component.scss']
})
export class CreateTripModalComponent {
  navigationHistory: any = JSON.parse(window.localStorage.getItem(keys.navigationHistory) || '[]');

  constructor(
    private publicService: PublicService,
    private dialogService: DialogService,
    private ref: DynamicDialogRef,
    private location: Location,
    private router: Router,
    @Inject(PLATFORM_ID) private platformId: Object
  ) {
    if (isPlatformBrowser(this.platformId)) {
      this.navigationHistory = JSON.parse(localStorage.getItem(keys.navigationHistory) || '[]');
    }
  }

  ngOnInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.publicService.closeModal.subscribe((res: boolean) => {
        if (res == true) {
          this.ref.close();
          this.publicService.closeModal.next(false);
        }
      });
    }
  }

  createTrip(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.ref?.close();
      this.router?.navigate(['/trips/list']);
      const ref: any = this?.dialogService?.open(PrepearTripStepperComponent, {
        width: '65%',
        height: '100vh',
        dismissableMask: false,
        styleClass: 'start-trip-dialog',
        baseZIndex: 10001,
      });
    }
  }
  prev(): void {
    if (isPlatformBrowser(this.platformId)) {
      if (this.navigationHistory && this.navigationHistory?.length > 1) {
        this.location?.back();
      } else {
        this.router?.navigate(['/home']);
      }

      this.ref?.close();
    }
  }
}
