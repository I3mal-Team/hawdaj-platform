import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EarthStatisticsComponent } from './earth-statistics.component';

describe('EarthStatisticsComponent', () => {
  let component: EarthStatisticsComponent;
  let fixture: ComponentFixture<EarthStatisticsComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [EarthStatisticsComponent]
    });
    fixture = TestBed.createComponent(EarthStatisticsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
