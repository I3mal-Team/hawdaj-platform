import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShareSocialComponent } from "../../../Common/component/share-social/share-social.component";
import { TranslateModule } from '@ngx-translate/core';
import { environment } from 'src/environments/environment';
import { SafeHtmlPipe } from 'src/app/Common/pipes/safe-html.pipe';

@Component({
  selector: 'app-story-content',
  standalone: true,
  imports: [CommonModule, ShareSocialComponent, TranslateModule, SafeHtmlPipe],
  templateUrl: './story-content.component.html',
  styleUrls: ['./story-content.component.scss']
})
export class StoryContentComponent {

  @Input() item: any;
  @Input() fullUrl: string;
  protected getCategoryBackground(index: number): string {
    const backgrounds = [
      '#CAD6FF',
      '#EDD3FF',
      '#D3F5FF',
      '#FFE2D3',
      '#D3FFE5',
      '#FFF7D3',
      '#F0D3FF',
    ];

    return backgrounds[index % backgrounds.length];
  }
}
