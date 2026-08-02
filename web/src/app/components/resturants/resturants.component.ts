import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { Component } from '@angular/core';

@Component({
  selector: 'app-resturants',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule
  ],
  templateUrl: './resturants.component.html',
  styleUrls: ['./resturants.component.scss']
})
export class ResturantsComponent {

}
