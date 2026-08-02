import { ComponentFixture, TestBed } from '@angular/core/testing';

import { StoresListComponent } from './stores-list.component';

describe('StoresListComponent', () => {
  let component: StoresListComponent;
  let fixture: ComponentFixture<StoresListComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [StoresListComponent]
    });
    fixture = TestBed.createComponent(StoresListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
