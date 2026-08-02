import { PublicService } from 'src/app/modules/shared/services/public.service';
import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { AppItem } from '../dots/applications';
import { TextTruncateDirective } from 'src/app/modules/shared/directives/text-truncate.directive';
import { TranslateModule } from '@ngx-translate/core';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-application-card',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, TextTruncateDirective, StripHtmlPipe],
  templateUrl: './application-card.component.html',
  styleUrls: ['./application-card.component.scss']
})
export class ApplicationCardComponent {

  @Input() item: AppItem;
  currentLanguage: string = '';

  constructor(
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }
}
