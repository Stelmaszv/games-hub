import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Component, Input, Output, EventEmitter, OnInit, ElementRef,ViewChild } from '@angular/core';
import { Publisher } from '../../interfaces';
import { FormBuilder, FormGroup } from '@angular/forms';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { HttpErrorResponse } from '@angular/common/http';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';


@Component({
  selector: 'edit-publisher-general-information',
  templateUrl: './edit-publisher-general-information.component.html',
  styleUrls: ['./edit-publisher-general-information.component.scss']
})
export class EditPublisherGeneralInformationComponent implements OnInit {
  constructor(public translationService: TranslationService,private fb: FormBuilder, private httpServiceService: HttpServiceService,private formValidatorService: FormValidatorService){}
  
  @Input() publisher!: Publisher|null;
  name: string | undefined =''
  headquarter: string | null | undefined = ''
  origin: string | null | undefined = ''
  founded: string | null | undefined = ''
  website: string | null | undefined = ''

  public generalInformation: FormGroup = this.fb.group({
    name: null,
    origin: null,
    founded: null,
    website: null,
    headquarter: null
  });

  public descriptions: FormGroup = this.fb.group({
    eng: null,
    pl: null,
    fr: null,
  });

  public editGeneralInformationForm : FormGroup = this.fb.group({
    generalInformation: this.generalInformation,
    descriptions: this.descriptions
  })

  showAlert(id: string, className:string ,messages: string, status : string|null = null ): void {
    var alert = document.getElementById(id);

    if (alert && alert.style) {
      alert.style.display = 'block';
      let alertClass = alert.querySelector('.alert');
      let alertHeading = alert.querySelector('.alert-heading');
      let alertBody = alert.querySelector('.alert-body');

      if (alertHeading && status) {
        alertHeading.innerHTML = status
      }

      if (alertClass) {
        alertClass.classList.add(className);
      }

      if (alertBody) {
        alertBody.innerHTML = messages
      }
    }

    setTimeout(() => {
      var alert = document.getElementById(id);

      if (alert && alert.style) {
        alert.style.display = 'none'
      }

    }, 5000);
  
  }

  closeModal(id: string): void {
    const modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('show');
      modal.classList.add('fade');
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');

      document.body.classList.remove('modal-open');
      
      const backdrop = document.getElementsByClassName('modal-backdrop')[0];
      if (backdrop) {
        backdrop.parentNode?.removeChild(backdrop);
      }
    }
  }

  openModal(id: string): void {
    const modal = document.getElementById(id);
    if (modal) {
      modal.style.display = 'block';
      modal.setAttribute('aria-hidden', 'false');
      modal.classList.add('show');
      modal.classList.remove('fade');

      document.body.classList.add('modal-open');
      
      const backdrop = document.createElement('div');
      backdrop.className = 'modal-backdrop show';
      document.body.appendChild(backdrop);
    }
  }

  onSubmit(): void {

    let postData = {
      'generalInformation' :{
        'name':this.name,
        'headquarter':this.headquarter,
        'origin':this.origin,
        'founded':this.founded,
        'website': this.website
      },
      descriptions: []
    }

    this.httpServiceService.putData(`http://localhost/api/publisher/edit/general-information/${this.publisher?.id}`,postData  ).subscribe({  
      next: (response : any) => {
        if(response.success){
          this.closeModal('editGeneralInformation');
          this.showAlert('alert-publisher','alert-success','Edycjia sie powiodła !')
        }
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'generalInformation','generalInformation.')
      } 
    });
  }

  ngOnInit(): void {
    this.name = this.publisher?.generalInformation.name
    this.headquarter = this.publisher?.generalInformation.headquarter
    this.origin = this.publisher?.generalInformation.origin
    this.founded = this.publisher?.generalInformation.founded
    this.website = this.publisher?.generalInformation.website
  }
}
