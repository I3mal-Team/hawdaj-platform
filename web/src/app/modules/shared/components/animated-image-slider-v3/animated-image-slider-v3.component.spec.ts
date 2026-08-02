import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnimatedImageSliderV3Component } from './animated-image-slider-v3.component';

describe('AnimatedImageSliderV3Component', () => {
  let component: AnimatedImageSliderV3Component;
  let fixture: ComponentFixture<AnimatedImageSliderV3Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AnimatedImageSliderV3Component]
    });
    fixture = TestBed.createComponent(AnimatedImageSliderV3Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
