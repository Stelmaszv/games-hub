import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';

@Component({
  selector: 'app-add-publishers',
  templateUrl: './add-publishers.component.html',
  styleUrls: ['./add-publishers.component.scss']
})
export class AddPublishersComponent {
  public section: String = 'descriptions_normal';
  private generalInformationValidation : boolean = false

  constructor(
    private fb: FormBuilder,
    private httpServiceService: HttpServiceService ,
    private formValidatorService: FormValidatorService,
    private router: Router
  ) {}

  public generalInformation: FormGroup = this.fb.group({
    name: '',
    origin: '',
    founded: '',
    website: '',
    headquarter: ''
  });

  public descriptions: FormGroup = this.fb.group({
    eng: '',
    pl: '',
    fr: '',
  });

  public publisherForm : FormGroup = this.fb.group({
    generalInformation: this.generalInformation,
    descriptions: this.descriptions
  })

  public generalInformationScraperForm : FormGroup = this.fb.group({
    url: '',
  })

  public descriptionsScraperForm : FormGroup = this.fb.group({
    eng: '',
    pl: '',
    fr: '',
  })

  public updateSection(){
    if(this.generalInformationValidation){
      this.section = 'descriptions_normal'
    }
  }

  public onDescriptionsScraperSubmit(){
    this.httpServiceService.postData('http://localhost/api/publisher/web-scraber/add/descriptions',{    
      "descriptions":[
        {"url":this.descriptionsScraperForm?.get('eng')?.value,"lng":"eng"},
        {"url":this.descriptionsScraperForm?.get('pl')?.value,"lng":"pl"},
        {"url":this.descriptionsScraperForm?.get('fr')?.value,"lng":"fr"},
      ],
    }).subscribe({  
      next: (response) => {
        const data = response['description'];
        this.descriptions.setValue(data);
      },
      error: (errorList: HttpErrorResponse) => {
        const generalInformationKeys = Object.keys(errorList.error.errors);
        this.generalInformationValidation = (generalInformationKeys.length === 0)
        this.updateSection();
      
        let generalInformationErrors: { [key: string]: any } = {};
  
        for (let error of Object.entries(errorList.error.errors)) {
          if (generalInformationKeys.indexOf(error[0].toString()) !== -1) { 
            const key = error[0].replace(/\./g, "");
            generalInformationErrors[key] = error;
          }
        }
  
        this.formValidatorService.setForm('descriptions')
        this.formValidatorService.showErrors(generalInformationErrors,'')
        this.formValidatorService.restNotUseInputs(generalInformationErrors)
      }
    });
    
  }

  public onGeneralInformationScraperSubmit(){
    this.httpServiceService.postData('http://localhost/api/publisher/web-scraber/add/',{    
      url: this.generalInformationScraperForm?.get('url')?.value
    }).subscribe({  
      next: (response) => {
        const data = response['generalInformation']
        this.generalInformation.setValue(data);
      },
      error: (errorList: HttpErrorResponse) => {
        console.log(errorList.error.errors)
        const generalInformationKeys = Object.keys(errorList.error.errors);
        this.generalInformationValidation = (generalInformationKeys.length === 0)
        this.updateSection();
      
        let generalInformationScraperErrors: { [key: string]: any } = {};
  
        for (let error of Object.entries(errorList.error.errors)) {
          if (generalInformationKeys.indexOf(error[0].toString()) !== -1) { 
            const key = error[0].replace(/\./g, "");
            generalInformationScraperErrors[key] = error;
          }
        }
  
        this.formValidatorService.setForm('generalInformationScraperForm')
        this.formValidatorService.showErrors(generalInformationScraperErrors,{    
          url: this.generalInformationScraperForm?.get('url')?.value
        })
        this.formValidatorService.restNotUseInputs(generalInformationScraperErrors)
        
      }
    });
  }

  public onSubmit() {   
    const generalInformation = {
      name: this.generalInformation?.get('name')?.value,
      origin: this.generalInformation?.get('origin')?.value,
      founded: this.generalInformation?.get('founded')?.value,
      website: this.generalInformation?.get('website')?.value,
      headquarter: this.generalInformation?.get('headquarter')?.value,
    };
  
    const descriptions = {
      eng: this.descriptions?.get('eng')?.value,
      pl: this.descriptions?.get('pl')?.value,
      fr: this.descriptions?.get('fr')?.value
    };
  
    this.httpServiceService.postData('http://localhost/api/publisher/add',{ 
      'generalInformation' : generalInformation,
      'descriptions' :descriptions,
      'add' : this.generalInformationValidation
    }).subscribe({
      next: (response) => {
        if(this.generalInformationValidation && response.success){
          this.router.navigate(['publisher/show', response.id]);
        }
      },
      error: (errorList: HttpErrorResponse) => {
        const generalInformationKeys = Object.keys(errorList.error.errors).filter(key => key.startsWith('generalInformation.'));
        this.generalInformationValidation = (generalInformationKeys.length === 0)
        this.updateSection();
      
        let generalInformationErrors: { [key: string]: any } = {};
  
        for (let error of Object.entries(errorList.error.errors)) {
          if (generalInformationKeys.indexOf(error[0].toString()) !== -1) { 
            const key = error[0].replace(/\./g, "");
            generalInformationErrors[key] = error;
          }
        }
  
        this.formValidatorService.setForm('generalInformation')
        this.formValidatorService.showErrors(generalInformationErrors, generalInformation)
        this.formValidatorService.restNotUseInputs(generalInformationErrors)
      }
    });
  }
}
