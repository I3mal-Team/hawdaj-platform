import { TextTruncateDirective } from 'src/app/modules/shared/directives/text-truncate.directive';
import { PublicService } from 'src/app/modules/shared/services/public.service';
import { AppItem } from 'src/app/components/applications/dots/applications';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { TranslateModule } from '@ngx-translate/core';
import { Component, Input } from '@angular/core';
import { StripHtmlPipe } from 'src/app/Common/pipes/strip-html.pipe';

@Component({
  selector: 'app-application-card-home',
  standalone: true,
  imports: [CommonModule, TranslateModule, NgOptimizedImage, TextTruncateDirective, StripHtmlPipe],
  templateUrl: './application-card-home.component.html',
  styleUrls: ['./application-card-home.component.scss']
})
export class ApplicationCardHomeComponent {

  @Input() item: AppItem;
  currentLanguage: string = '';

  constructor(
    private publicService: PublicService
  ) { }

  ngOnInit(): void {
    this.currentLanguage = this.publicService.getCurrentLanguage();
  }
}
