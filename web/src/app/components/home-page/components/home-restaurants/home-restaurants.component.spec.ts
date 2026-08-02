import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeRestaurantsComponent } from './home-restaurants.component';

describe('HomeRestaurantsComponent', () => {
  let component: HomeRestaurantsComponent;
  let fixture: ComponentFixture<HomeRestaurantsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomeRestaurantsComponent]
    });
    fixture = TestBed.createComponent(HomeRestaurantsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
