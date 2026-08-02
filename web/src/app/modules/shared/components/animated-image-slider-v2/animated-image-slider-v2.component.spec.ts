import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnimatedImageSliderV2Component } from './animated-image-slider-v2.component';

describe('AnimatedImageSliderV2Component', () => {
  let component: AnimatedImageSliderV2Component;
  let fixture: ComponentFixture<AnimatedImageSliderV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AnimatedImageSliderV2Component]
    });
    fixture = TestBed.createComponent(AnimatedImageSliderV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
