import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddNewLandmarkComponent } from './add-new-landmark.component';

describe('AddNewLandmarkComponent', () => {
  let component: AddNewLandmarkComponent;
  let fixture: ComponentFixture<AddNewLandmarkComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AddNewLandmarkComponent]
    });
    fixture = TestBed.createComponent(AddNewLandmarkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
