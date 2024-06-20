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

  closeModal(id: string): void {
    var modal = document.getElementById(id);
    if (modal) {
      modal.classList.remove('show');
      modal.classList.add('fade');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
  
      var backdrop = document.getElementsByClassName('modal-backdrop')[0];
      if (backdrop) {
        backdrop?.parentNode?.removeChild(backdrop);
      }
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
