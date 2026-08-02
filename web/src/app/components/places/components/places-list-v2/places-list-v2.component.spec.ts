import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PlacesListV2Component } from './places-list-v2.component';

describe('PlacesListV2Component', () => {
  let component: PlacesListV2Component;
  let fixture: ComponentFixture<PlacesListV2Component>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [PlacesListV2Component]
    });
    fixture = TestBed.createComponent(PlacesListV2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
