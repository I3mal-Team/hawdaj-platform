import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddAnotherPlaceComponent } from './add-another-place.component';

describe('AddAnotherPlaceComponent', () => {
  let component: AddAnotherPlaceComponent;
  let fixture: ComponentFixture<AddAnotherPlaceComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AddAnotherPlaceComponent]
    });
    fixture = TestBed.createComponent(AddAnotherPlaceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
