import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TourGuidesListComponent } from './tour-guides-list.component';

describe('TourGuidesListComponent', () => {
  let component: TourGuidesListComponent;
  let fixture: ComponentFixture<TourGuidesListComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [TourGuidesListComponent]
    });
    fixture = TestBed.createComponent(TourGuidesListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
