import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnimatedImageSliderComponent } from './animated-image-slider.component';

describe('AnimatedImageSliderComponent', () => {
  let component: AnimatedImageSliderComponent;
  let fixture: ComponentFixture<AnimatedImageSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AnimatedImageSliderComponent]
    });
    fixture = TestBed.createComponent(AnimatedImageSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
