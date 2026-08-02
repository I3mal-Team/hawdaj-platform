import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeRestaurantsV2Component } from './home-restaurants-v2.component';

describe('HomeRestaurantsV2Component', () => {
  let component: HomeRestaurantsV2Component;
  let fixture: ComponentFixture<HomeRestaurantsV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HomeRestaurantsV2Component]
    });
    fixture = TestBed.createComponent(HomeRestaurantsV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
