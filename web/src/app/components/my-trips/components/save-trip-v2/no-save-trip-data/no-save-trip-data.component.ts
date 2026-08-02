// Modules
import { DynamicDialogRef, DialogService } from 'primeng/dynamicdialog';
import { CommonModule, isPlatformBrowser, Location } from '@angular/common';
import { Component, Inject, PLATFORM_ID } from '@angular/core';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
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
import { DynamicDialogConfig } from 'primeng/dynamicdialog';
import { PrepearTripStepperComponent } from 'src/app/domains';

@Component({
  selector: 'app-no-save-trip-data',
  standalone: true,
  imports: [
    CommonModule,
    TranslateModule,
    ReactiveFormsModule,
    FormsModule,
    DropdownModule,
    InputTextModule,
    RouterModule,
  ], templateUrl: './no-save-trip-data.component.html',
  styleUrls: ['./no-save-trip-data.component.scss']
})
export class NoSaveTripDataComponent {


  navigationHistory: any = JSON.parse(window.localStorage.getItem(keys.navigationHistory) || '[]');
  token: string;
  constructor(
    private publicService: PublicService,
    private dialogService: DialogService,
    private ref: DynamicDialogRef,
    private location: Location,
    private router: Router,
    private route: ActivatedRoute,
    public config: DynamicDialogConfig,
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
    this.token = this.config.data?.token;
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
    this.route.paramMap.subscribe(params => {
      this.token = params.get('token');
    });

  }
  viewTripList(): void {
    if (isPlatformBrowser(this.platformId) && this.token) {
      this.router.navigate(['/trips/list']);
    }
    this.ref?.close();
  }
}
