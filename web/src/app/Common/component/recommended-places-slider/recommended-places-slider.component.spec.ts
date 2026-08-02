import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RecommendedPlacesSliderComponent } from './recommended-places-slider.component';

describe('RecommendedPlacesSliderComponent', () => {
  let component: RecommendedPlacesSliderComponent;
  let fixture: ComponentFixture<RecommendedPlacesSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [RecommendedPlacesSliderComponent]
    });
    fixture = TestBed.createComponent(RecommendedPlacesSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
