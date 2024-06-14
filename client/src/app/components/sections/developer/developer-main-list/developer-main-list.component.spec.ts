import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DeveloperMainListComponent } from './developer-main-list.component';

describe('DeveloperMainListComponent', () => {
  let component: DeveloperMainListComponent;
  let fixture: ComponentFixture<DeveloperMainListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DeveloperMainListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DeveloperMainListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
