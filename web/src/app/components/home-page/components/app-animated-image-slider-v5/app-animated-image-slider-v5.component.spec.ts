import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AppAnimatedImageSliderV5Component } from './app-animated-image-slider-v5.component';

describe('AppAnimatedImageSliderV5Component', () => {
  let component: AppAnimatedImageSliderV5Component;
  let fixture: ComponentFixture<AppAnimatedImageSliderV5Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AppAnimatedImageSliderV5Component]
    });
    fixture = TestBed.createComponent(AppAnimatedImageSliderV5Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
