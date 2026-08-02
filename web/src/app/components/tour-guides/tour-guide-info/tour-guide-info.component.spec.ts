import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuideInfoComponent } from './tour-guide-info.component';

describe('TourGuideInfoComponent', () => {
  let component: TourGuideInfoComponent;
  let fixture: ComponentFixture<TourGuideInfoComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuideInfoComponent]
    });
    fixture = TestBed.createComponent(TourGuideInfoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
