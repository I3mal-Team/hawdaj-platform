import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GeoSaudiRegionMapComponent } from './geo-saudi-region-map.component';

describe('GeoSaudiRegionMapComponent', () => {
  let component: GeoSaudiRegionMapComponent;
  let fixture: ComponentFixture<GeoSaudiRegionMapComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [GeoSaudiRegionMapComponent]
    });
    fixture = TestBed.createComponent(GeoSaudiRegionMapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
