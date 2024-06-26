import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditPublisherGeneralInformationComponent } from './edit-publisher-general-information.component';

describe('EditPublisherGeneralInformationComponent', () => {
  let component: EditPublisherGeneralInformationComponent;
  let fixture: ComponentFixture<EditPublisherGeneralInformationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditPublisherGeneralInformationComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EditPublisherGeneralInformationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
