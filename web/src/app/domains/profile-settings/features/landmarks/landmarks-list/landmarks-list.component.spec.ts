import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LandmarksListComponent } from './landmarks-list.component';

describe('LandmarksListComponent', () => {
  let component: LandmarksListComponent;
  let fixture: ComponentFixture<LandmarksListComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [LandmarksListComponent]
    });
    fixture = TestBed.createComponent(LandmarksListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
