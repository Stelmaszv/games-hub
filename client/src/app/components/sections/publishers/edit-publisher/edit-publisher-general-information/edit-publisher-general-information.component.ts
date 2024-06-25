import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { Publisher } from '../../interfaces';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { HttpErrorResponse } from '@angular/common/http';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';
import { BootstrapService } from 'src/app/services/common/bootstrap/bootstrap.service';


@Component({
  selector: 'edit-publisher-general-information',
  templateUrl: './edit-publisher-general-information.component.html',
  styleUrls: ['./edit-publisher-general-information.component.scss']
})
export class EditPublisherGeneralInformationComponent implements OnInit {
  constructor(public translationService: TranslationService, private bootstrapService: BootstrapService,private httpServiceService: HttpServiceService,private formValidatorService: FormValidatorService){}
  
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

  public onSubmit(): void {

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
          this.bootstrapService.closeModal('editGeneralInformation');
          this.bootstrapService.showAlert('alert-publisher','alert-success',this.translationService.translate('editPublisherGeneralInformationSuccess'))
          this.updatePublisher(postData)
        }
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'generalInformation','generalInformation.')
      } 
    });
  }

  public ngOnInit(): void {
    this.id = (this.publisher?.id) ? this.publisher?.id : 0
    this.name = (this.publisher?.generalInformation.name) ? this.publisher?.generalInformation.name : ''
    this.headquarter = (this.publisher?.generalInformation.headquarter) ? this.publisher?.generalInformation.headquarter : null
    this.origin = (this.publisher?.generalInformation.origin) ? this.publisher?.generalInformation.origin : null
    this.founded =  (this.publisher?.generalInformation.founded) ? this.publisher?.generalInformation.founded : null
    this.website = (this.publisher?.generalInformation.website) ? this.publisher?.generalInformation.website : null 
  }
}
