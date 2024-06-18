import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShowPublisherGeneralInformationComponent } from './show-publisher-general-information.component';




describe('ShowPublisherGeneralInformationComponent', () => {
  let component: ShowPublisherGeneralInformationComponent;
  let fixture: ComponentFixture<ShowPublisherGeneralInformationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShowPublisherGeneralInformationComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ShowPublisherGeneralInformationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
