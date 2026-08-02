import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HighlightsStatisticsV2Component } from './highlights-statistics-v2.component';

describe('HighlightsStatisticsV2Component', () => {
  let component: HighlightsStatisticsV2Component;
  let fixture: ComponentFixture<HighlightsStatisticsV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HighlightsStatisticsV2Component]
    });
    fixture = TestBed.createComponent(HighlightsStatisticsV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
