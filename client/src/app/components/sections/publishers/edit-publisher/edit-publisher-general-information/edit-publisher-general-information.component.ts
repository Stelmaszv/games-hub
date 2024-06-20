import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { Publisher } from '../../interfaces';
import { FormBuilder, FormGroup } from '@angular/forms';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'edit-publisher-general-information',
  templateUrl: './edit-publisher-general-information.component.html',
  styleUrls: ['./edit-publisher-general-information.component.scss']
})
export class EditPublisherGeneralInformationComponent implements OnInit {
  constructor(public translationService: TranslationService,private fb: FormBuilder, private httpServiceService: HttpServiceService){}
  @Input() publisher!: Publisher|null;
  name:string =''
  headquarter:string = ''
  origin:string = ''
  founded:string = ''
  website:string = ''

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

    this.httpServiceService.putData('http://localhost/api/publisher/edit/general-information/37',postData  ).subscribe({  
      next: (response : any) => {
        console.log('edit put')
        console.log(response)
      },
      error: (errorList: HttpErrorResponse) => {
        console.log(errorList)
      } 
    });
  }

  ngOnInit(): void {
    console.log('edit')
    console.log(this.publisher?.generalInformation)
  }
}
