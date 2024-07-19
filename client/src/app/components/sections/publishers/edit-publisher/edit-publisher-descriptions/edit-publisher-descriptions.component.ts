import { Component,Input,Output,EventEmitter } from '@angular/core';
import { Publisher, PublisherDescriptionsScraper, PublisherDescriptionsScraperResponse } from '../../interfaces';
import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { FormValidatorService } from 'src/app/services/common/form-validator/form-validator.service';
import { HttpErrorResponse } from '@angular/common/http';
import { BootstrapService } from 'src/app/services/common/bootstrap/bootstrap.service';
import { Response } from '../../../../interface';

interface Language {
  key: string;
  value: string;
  index: string
}

@Component({
  selector: 'edit-publisher-descriptions',
  templateUrl: './edit-publisher-descriptions.component.html',
  styleUrls: ['./edit-publisher-descriptions.component.scss']
})

export class EditPublisherDescriptionsComponent {

  @Input() publisher!: Publisher|null;
  @Output() publisherChange = new EventEmitter<Publisher>();

  private id: number = 0;
  public languagesForm : any = null;
  public languagesScraper: Language[] = [];
  public allLanguage : boolean = false;
  public mode = 'scraper';
  
  constructor(public translationService: TranslationService, private httpServiceService: HttpServiceService,private formValidatorService: FormValidatorService, private bootstrapService: BootstrapService){}

  private normalSubmit() : void 
  {
    let postData : Publisher = {

      'id' : this.id,

      'generalInformation' :{
        'name':(this.publisher?.generalInformation.name)? this.publisher?.generalInformation.name : '',
        'headquarter':(this.publisher?.generalInformation.headquarter)? this.publisher?.generalInformation.headquarter : null,
        'origin':(this.publisher?.generalInformation.origin)? this.publisher?.generalInformation.origin : null,
        'founded':(this.publisher?.generalInformation.founded)? this.publisher?.generalInformation.founded : null,
        'website': (this.publisher?.generalInformation.website)? this.publisher?.generalInformation.website : null
      },

      'descriptions' : {
        'fr':(this.languagesForm.find((language: { key: any; })  => language.key === 'fr'))?  this.languagesForm.find((language: { key: any; })  => language.key === 'fr').value : '',
        'pl':(this.languagesForm.find((language: { key: any; })  => language.key === 'pl'))?  this.languagesForm.find((language: { key: any; })  => language.key === 'pl').value : '',
        'en':(this.languagesForm.find((language: { key: any; })  => language.key === 'en'))?  this.languagesForm.find((language: { key: any; })  => language.key === 'en').value : '',
      }

    }

    this.httpServiceService.putData(`http://localhost/api/publisher/edit/${this.id}`,postData  ).subscribe({  
      next: (response : Response) => {
        if(response.success){
          this.formValidatorService.restNotUseInputs({})
          this.bootstrapService.closeModal('editDescriptions');
          this.bootstrapService.showAlert('alert-publisher','alert-success',this.translationService.translate('editPublisherGeneralInformationSuccess'))
          this.updatePublisher(postData)
        }
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'generalInformation','generalInformation.')
      } 
    });
  }

  private scraperSubmit(): void
  {
    let postData : PublisherDescriptionsScraper = {    
      "descriptions":[
        {"url":this.findLanguageByIndex('fr')?.value || '',"lng":"fr"},
        {"url":this.findLanguageByIndex('en')?.value || '',"lng":"en"},
        {"url":this.findLanguageByIndex('pl')?.value || '',"lng":"pl"},
      ]
    }
    
    this.httpServiceService.postData('http://localhost/api/publisher/web-scraper/add/descriptions', postData ).subscribe({  
      next: (response : PublisherDescriptionsScraperResponse) => {
        /*
        const publisherDescriptions : PublisherDescriptions = {
          'en' : this.getDescription('en',response['description']),
          'fr': this.getDescription('fr',response['description']),
          'pl':this.getDescription('pl',response['description']),
        }
        this.descriptions.setValue(publisherDescriptions);
        */
        this.formValidatorService.restNotUseInputsMultiError('scraper')
      },
      error: (errorList: HttpErrorResponse) => {
        this.formValidatorService.processErrors(errorList.error.errors, postData ,'descriptions')
      }
    });
  }

  private findLanguageByIndex(key: string): Language | undefined {
    return this.languagesScraper.find((language) => language.key === key);
  }

  public onSubmit(): void {
    if(this.mode == 'scraper'){
      this.scraperSubmit()
    }else{
      this.normalSubmit()
    }
  }

  public ngOnInit(): void {
    this.id = this.publisher?.id || 0;
    let lang = this.translationService.getLang()

    this.languagesForm = [
      {
        'key' : 'fr',
        'value' : (this.publisher?.descriptions.fr) ? this.publisher?.descriptions.fr : '',
        'active' : (lang === 'fr'),
      },
      {
        'key' : 'en',
        'value' : (this.publisher?.descriptions.en) ? this.publisher?.descriptions.en : '',
        'active' : (lang === 'en'),
      },
      {
        'key' : 'pl',
        'value' : (this.publisher?.descriptions.pl) ? this.publisher?.descriptions.pl : '',
        'active' : (lang === 'pl'),
      }
    ]

    this.languagesScraper = [
      {
        'key' : 'fr',
        'value' : 'https://pl.wikipedia.org/wiki/Ubisoft',
        'index' : 'descriptions[0]url'
      },
      {
        'key' : 'en',
        'value' :'https://pl.wikipedia.org/wiki/Ubisoft',
        'index' :'descriptions[1]url'
      },
      {
        'key' : 'pl',
        'value' :  'https://pl.wikipedia.org/wiki/Ubisoft',
        'index' :'descriptions[2]url'
      }
    ]

  }

  public showAllLanguage() : void {
    this.allLanguage = true;
  }

  public scraperSwitch(): void {
    this.mode = this.mode === 'form' ? 'scraper' : 'form';
  }

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
}
