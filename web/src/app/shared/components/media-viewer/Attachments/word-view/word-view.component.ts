import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { LazyLoadImageDirective } from 'src/app/modules/shared/directives/lazy-load-image.directive';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-word-view',
  standalone: true,
  imports: [CommonModule, LazyLoadImageDirective, NgOptimizedImage],
  templateUrl: './word-view.component.html',
  styleUrls: ['./word-view.component.scss']
})
export class WordViewComponent {
  @Input() word: any;

  downloadFile(filePath: string) {
    const fullPath = filePath;
    window.open(fullPath, '_blank');
  }
}
