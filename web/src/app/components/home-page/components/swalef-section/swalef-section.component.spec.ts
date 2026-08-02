import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SwalefSectionComponent } from './swalef-section.component';

describe('SwalefSectionComponent', () => {
  let component: SwalefSectionComponent;
  let fixture: ComponentFixture<SwalefSectionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SwalefSectionComponent]
    });
    fixture = TestBed.createComponent(SwalefSectionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
