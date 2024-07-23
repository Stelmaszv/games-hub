import { Injectable } from '@angular/core';
import { TranslationService } from '../translation/translation.service';
import { Language } from 'src/app/components/interface';
import { Languages } from 'src/app/components/sections/publishers/enum';

@Injectable({
  providedIn: 'root'
})
export class LanguageService {

  constructor(private translationService: TranslationService) { }

  async returnFields(){
    let languages: Language[] = await this.translationService.setLanguagesList();

    languages = languages.map((language, index) => {
        return {
            ...language, // Zachowaj istniejące klucze
            index: 'descriptions['+index+']url' // Dodaj nowy klucz `index`, wartość to index + 1
        };
    });

    return languages;
  }

  async resetFormDescription() {
    let languages: Language[] = await this.translationService.setLanguagesList();

    const descriptions = languages.reduce((acc, language) => {
        acc[language.key] = null;
        return acc;
    }, {} as Record<string, null>);

    return descriptions;
  }

  async setDescriptionsValues(form :any ){
    let languages: Language[] = await this.translationService.setLanguagesList();

    const descriptions = languages.reduce((acc, language) => {
      acc[language.key] = form?.get(language.key).value;
      return acc;
    }, {} as Record<string, null>);

    return descriptions;
  }

  setDescriptionsScraper(Language : any,response : any,form :any){
    let languages = Language;

    const descriptions = languages.reduce((acc:any, language:any) => {
      acc[language.key] = this.getDescription('en',response['description'],form)
      return acc;
    }, {} as Record<string, null>);

    return descriptions;
  }

  private getDescription(lng: string, response: any,form :any): string {
    let description: string = '';

    if (form?.get(lng)?.value === null || form?.get(lng)?.value === '') {
        description = response[lng] ?? lng;
    } else {
        description = form?.get(lng)?.value ?? '';
    }

    return description;
  }

  static descriptionsFields(language:any){
    
    return {
      en: null,
      pl: null,
      fr: null,
    }
  }
}
