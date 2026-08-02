import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LandmarksFormComponent } from './landmarks-form.component';

describe('LandmarksFormComponent', () => {
  let component: LandmarksFormComponent;
  let fixture: ComponentFixture<LandmarksFormComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [LandmarksFormComponent]
    });
    fixture = TestBed.createComponent(LandmarksFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
