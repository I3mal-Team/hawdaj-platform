import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideImageComponent } from './tour-guide-image.component';

describe('TourGuideImageComponent', () => {
  let component: TourGuideImageComponent;
  let fixture: ComponentFixture<TourGuideImageComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideImageComponent]
    });
    fixture = TestBed.createComponent(TourGuideImageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
