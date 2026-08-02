import { Component, inject, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShareComponent } from 'src/app/modules/shared/components/share/share.component';
import { DialogService } from 'primeng/dynamicdialog';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { TranslateModule } from '@ngx-translate/core';

@Component({
  selector: 'app-share-social',
  standalone: true,
  imports: [CommonModule, TranslateModule],
  templateUrl: './share-social.component.html',
  styleUrls: ['./share-social.component.scss']
})
export class ShareSocialComponent {
  @Input() fullUrl: string;
  @Input() webSite: string;
  @Input() isButton: boolean = false;
  @Input() whiteIconColor: boolean = false;

  private dialogService = inject(DialogService);
  public publicService = inject(PublicService);
  share(link: any): void {
    const ref = this.dialogService.open(ShareComponent, {
      header: this.publicService?.translateTextFromJson('general.share'),
      width: '40%',
      baseZIndex: 10000,
      data: {
        link: this.fullUrl,
      },
      styleClass: 'rate',
    });
  }
}
