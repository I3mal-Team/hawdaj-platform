import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SvgSaudiMapComponent } from './svg-saudi-map.component';

describe('SvgSaudiMapComponent', () => {
  let component: SvgSaudiMapComponent;
  let fixture: ComponentFixture<SvgSaudiMapComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [SvgSaudiMapComponent]
    });
    fixture = TestBed.createComponent(SvgSaudiMapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
