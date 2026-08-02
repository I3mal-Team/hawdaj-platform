import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { environment } from 'src/environments/environment';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';

@Component({
  selector: 'app-pdf-view',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './pdf-view.component.html',
  styleUrls: ['./pdf-view.component.scss']
})
export class PdfViewComponent {
  @Input() pdf: any;

  downloadFile(filePath: string) {
    const fullPath = filePath;
    window.open(fullPath, '_blank');
  }
}
