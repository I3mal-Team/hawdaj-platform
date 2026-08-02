import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AnimatedImageSliderV4Component } from './animated-image-slider-v4component';

describe('AnimatedImageSliderV4Component', () => {
  let component: AnimatedImageSliderV4Component;
  let fixture: ComponentFixture<AnimatedImageSliderV4Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AnimatedImageSliderV4Component]
    });
    fixture = TestBed.createComponent(AnimatedImageSliderV4Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
