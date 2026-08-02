import { PublicService } from 'src/app/modules/shared/services/public.service';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { environment } from '../../../../../environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { SafeHtmlPipe } from 'src/app/Common/pipes/safe-html.pipe';

@Component({
  standalone: true,
  imports: [
    TranslateModule,
    CommonModule,
    SafeHtmlPipe
  ],
  selector: 'app-offer-details-modal',
  templateUrl: './offer-details-modal.component.html',
  styleUrls: ['./offer-details-modal.component.scss']
})
export class OfferDetailsModalComponent {
  offerDetails: any;

  constructor(
    private publicService: PublicService,
    private config: DynamicDialogConfig,
    private ref: DynamicDialogRef
  ) { }

  ngOnInit(): void {
    this.publicService.closeModal.subscribe((res: boolean) => {
      if (res == true) {
        this.ref.close();
        this.publicService.closeModal.next(false);
      }
    });
    this.offerDetails = this.config?.data;
  }
}
