import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VideosSliderComponent } from './videos-slider.component';

describe('VideosSliderComponent', () => {
  let component: VideosSliderComponent;
  let fixture: ComponentFixture<VideosSliderComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [VideosSliderComponent]
    });
    fixture = TestBed.createComponent(VideosSliderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
