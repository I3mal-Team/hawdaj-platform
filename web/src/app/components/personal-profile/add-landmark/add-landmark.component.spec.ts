import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddLandmarkComponent } from './add-landmark.component';

describe('AddLandmarkComponent', () => {
  let component: AddLandmarkComponent;
  let fixture: ComponentFixture<AddLandmarkComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AddLandmarkComponent]
    });
    fixture = TestBed.createComponent(AddLandmarkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
