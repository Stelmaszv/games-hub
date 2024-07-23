import { HttpErrorResponse } from '@angular/common/http';
import { Component,OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { GeneralInformationResponse, GeneralInformationScraper, PublisherAddForm, PublisherDescriptions, PublisherDescriptionsScraper, PublisherDescriptionsScraperResponse, PublisherGeneralInformation} from '../interfaces';
import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Language, Response } from 'src/app/components/interface';
import { LanguageService } from 'src/app/services/common/language/language.service';

@Component({
  selector: 'app-add-publishers',
  templateUrl: './add-publishers.component.html',
  styleUrls: ['./add-publishers.component.scss']
})
export class AddPublishersComponent implements OnInit {
  public section: String = 'general_information_normal';
  public languages: Language[] = [];

  private generalInformationValidation : boolean = false
  private add : boolean = false

  constructor(
    private fb: FormBuilder,
    private httpServiceService: HttpServiceService ,
    private formValidatorService: FormValidatorService,
    private router: Router,
    public translationService: TranslationService,
    public languageService :LanguageService
  ) {}

  async ngOnInit(): Promise<void> {
    this.languages = await this.languageService.returnFields()

  }

  public generalInformation: FormGroup = this.fb.group({
    name: null,
    origin: null,
    founded: null,
    website: null,
    headquarter: null
  });

  public descriptions: FormGroup = this.fb.group(LanguageService.descriptionsFields(this.languages));

  public publisherForm : FormGroup = this.fb.group({
    generalInformation: this.generalInformation,
    descriptions: this.descriptions
  })

  public generalInformationScraperForm : FormGroup = this.fb.group({
    url: null,
  })

  public descriptionsScraperForm : FormGroup = this.fb.group(LanguageService.descriptionsFields(this.languages))

  private getDescription(lng: string, response: PublisherDescriptions): string {
    let description: string = '';

    if (this.descriptions?.get(lng)?.value === null || this.descriptions?.get(lng)?.value === '') {
        description = response[lng] ?? lng;
    } else {
        description = this.descriptions?.get(lng)?.value ?? '';
    }

    return description;
  }

  public async onDescriptionsScraperSubmit() : Promise<void> 
  {
    let postData : PublisherDescriptionsScraper = {    
      "descriptions":[
        {"url":this.descriptionsScraperForm?.get('en')?.value,"lng":"en"},
        {"url":this.descriptionsScraperForm?.get('pl')?.value,"lng":"pl"},
        {"url":this.descriptionsScraperForm?.get('fr')?.value,"lng":"fr"},
      ],
    }
    
    this.httpServiceService.postData('http://localhost/api/publisher/web-scraper/add/descriptions', postData ).subscribe({  
      next: (response) => {

        this.languageService.setDescriptionsScraper(this.languages,response,this.descriptions)

        const publisherDescriptions  = this.languageService.setDescriptionsScraper(this.languages,response,this.descriptions)
        this.descriptions.setValue(publisherDescriptions);
        this.formValidatorService.restNotUseInputsMultiError('scraper')
      },
      error: (errorList: HttpErrorResponse) => {
        if(errorList.status == 500){
          console.log('Server Error')
        }
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'descriptions')
      }
    });
  }

  public onGeneralInformationScraperSubmit() : void 
  {
    let postData : GeneralInformationScraper = {    
      url: (this.generalInformationScraperForm?.get('url')?.value)? this.generalInformationScraperForm?.get('url')?.value : ''
    }

    this.httpServiceService.postData('http://localhost/api/publisher/web-scraper/add/general-information',postData).subscribe({  
      next: (response : GeneralInformationResponse ) => {
        this.formValidatorService.restNotUseInputs({})
        const data = response['generalInformation']
        this.generalInformation.setValue(data);
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors,postData,'generalInformationScraperForm')
      }
    });
  }

  public async onSubmit() : Promise<void> 
  {     
    const generalInformation : PublisherGeneralInformation = {
      name: this.generalInformation?.get('name')?.value,
      origin: this.generalInformation?.get('origin')?.value,
      founded: this.generalInformation?.get('founded')?.value,
      website: this.generalInformation?.get('website')?.value,
      headquarter: this.generalInformation?.get('headquarter')?.value,
    };

    let values = await this.languageService.setDescriptionsValues(this.descriptions)
  
    let postData = {
      'generalInformation' : generalInformation,
      'descriptions' :values,
      'add' : this.add
    }

    this.httpServiceService.postData('http://localhost/api/publisher/add',postData ).subscribe({
      next: (response : Response) => {
        if(this.generalInformationValidation && response.success){
          this.router.navigate(['publisher/show', response.id]);
        }
      },
      error: (errorList: HttpErrorResponse) => {
        const generalInformationKeys = Object.keys(errorList.error.errors).filter(key => key.startsWith('generalInformation.'));
        this.generalInformationValidation = (generalInformationKeys.length === 0);
        if (this.generalInformationValidation) {
          this.section = 'descriptions_normal';
        }

        this.formValidatorService.processErrors(errorList.error.errors, postData ,'generalInformation','generalInformation.')
      }
    });
  }

  public showGeneralInformation() : void
  {
    this.section = 'general_information_normal'
  }

  public restGeneralInformation() : void {
    const publisherGeneralInformation : PublisherGeneralInformation = {
      name: '',
      origin: null,
      founded: null,
      website: null,
      headquarter: null
    }
    this.generalInformation.setValue(publisherGeneralInformation);
  }

  public addPublisher() : void {
    this.add = true;
    this.onSubmit()
  }

  public async restDescription() : Promise<void> {
    let reset = await this.languageService.resetFormDescription()
    const publisherDescriptions = reset
    
    this.descriptions.setValue(publisherDescriptions);
  }

  public checkErrors() : boolean {
    return this.generalInformationValidation
  }

}
