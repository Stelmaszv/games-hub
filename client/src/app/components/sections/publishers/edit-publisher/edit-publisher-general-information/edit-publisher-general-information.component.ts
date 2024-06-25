import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { Publisher } from '../../interfaces';
import { FormBuilder} from '@angular/forms';
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
  @Output() publisherChange = new EventEmitter<Publisher>();
  private id: number = 0;
  name: string = ''
  headquarter: string | null  = null
  origin: string | null  = null
  founded: string | null  = null
  website: string | null  = null


  private updatePublisher(newPublisher: Publisher): void {
    this.publisher = newPublisher;
    this.publisherChange.emit(this.publisher);
  }
  
  private changePublisherData(): void {
    if(this.publisher !== null){
      const updatedPublisher: Publisher = this.publisher;
      this.updatePublisher(updatedPublisher);
    }
  }

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

    let postData : Publisher = {
      'id' : this.id,
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
          this.showAlert('alert-publisher','alert-success',this.translationService.translate('editPublisherGeneralInformationSuccess'))
          this.updatePublisher(postData)
        }
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'generalInformation','generalInformation.')
      } 
    });
  }

  ngOnInit(): void {
    this.id = (this.publisher?.id) ? this.publisher?.id : 0
    this.name = (this.publisher?.generalInformation.name) ? this.publisher?.generalInformation.name : ''
    this.headquarter = (this.publisher?.generalInformation.headquarter) ? this.publisher?.generalInformation.headquarter : null
    this.origin = (this.publisher?.generalInformation.origin) ? this.publisher?.generalInformation.origin : null
    this.founded =  (this.publisher?.generalInformation.founded) ? this.publisher?.generalInformation.founded : null
    this.website = (this.publisher?.generalInformation.website) ? this.publisher?.generalInformation.website : null 
  }
}
