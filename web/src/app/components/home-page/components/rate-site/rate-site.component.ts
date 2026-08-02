import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { environment } from 'src/environments/environment';
import { RateSiteShimmerComponent } from "./rate-site-shimmer/rate-site-shimmer.component";
import { FileType } from 'src/app/Common/enums/Attachments.enum';

@Component({
  selector: 'app-rate-site',
  standalone: true,
  imports: [CommonModule, RateSiteShimmerComponent],
  templateUrl: './rate-site.component.html',
  styleUrls: ['./rate-site.component.scss']
})
export class RateSiteComponent {
  @Input() item: any = {};
  @Input() isLoadingPlaceDetails: boolean;
  FileType = FileType;

  ngOnInit() {
    if (!this.item || !this.item.galleries) {
      return;
    }

    this.item.galleries = this.item.galleries.filter(item =>
      item?.mime_type === FileType.IMAGE
    );
  }

}
