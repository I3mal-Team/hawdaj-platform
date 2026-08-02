import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideHomeComponent } from './tour-guide-home.component';

describe('TourGuideHomeComponent', () => {
  let component: TourGuideHomeComponent;
  let fixture: ComponentFixture<TourGuideHomeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideHomeComponent]
    });
    fixture = TestBed.createComponent(TourGuideHomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
