import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SkeletonModule } from 'primeng/skeleton';

@Component({
  selector: 'app-rate-site-shimmer',
  standalone: true,
  imports: [CommonModule, SkeletonModule],
  templateUrl: './rate-site-shimmer.component.html',
  styleUrls: ['./rate-site-shimmer.component.scss']
})
export class RateSiteShimmerComponent {

}
