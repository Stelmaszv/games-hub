import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { firstValueFrom } from 'rxjs';
import { Language } from 'src/app/components/interface';

@Injectable({
  providedIn: 'root'
})
export class TranslationService {
  private translations: { [key: string]: string } = {};
  private languagesList: Language[] = [];
  public lang : string | null = null;
  private defaultLand : string = 'eng';

  constructor(private http: HttpClient) {
    this.initializeService();
  }

  private async initializeService(): Promise<void> {
    try {
      this.languagesList = await this.setLanguagesList();
      const long = localStorage.getItem('lang');

      if (long !== null) {
        this.loadTranslations(long);
        this.lang = long;
      } else {
        const preferredLanguage = navigator.language || this.defaultLand;
        const supportedLanguages = this.languagesList.map(language => language.key);;

        if (supportedLanguages.includes(preferredLanguage)) {
          document.documentElement.lang = preferredLanguage;
        } else {
          document.documentElement.lang = this.defaultLand;
        }

        this.loadTranslations(document.documentElement.lang);
        this.lang = document.documentElement.lang;
      }

    } catch (error) {
      console.error('Error initializing TranslationService', error);
    }
  }


  async setLanguagesList(): Promise<Language[]> {
    try {
      const data = await firstValueFrom(this.http.get<Language[]>('http://localhost/api/list_languages'));
      return data;
    } catch (error) {
      console.error('Error fetching languages', error);
      throw error;
    }
  }

  private loadTranslations(lang: string): void {
    this.http.get<any>(`assets/i18n/${lang}.json`).subscribe(translations => {
      for (const key in translations) {
        if (translations.hasOwnProperty(key)) {
          this.translations[key] = translations[key];
        }
      }
    });
  }

  public setLang(lang: string|null): void{
    if(lang === null){
      return;
    }

    localStorage.setItem('lang',lang)
  }

  public getLang() : string|null{
    return this.lang
  }

  public getLanguageName(langKey: string) : string {
    return this.getDataFromSubArray('languages',langKey)
  }

  private getDataFromSubArray(subArray:string, key:string) :string {
    const subData :any = this.translations[subArray];
    const subDataKey = subData.find((lang: any) => lang.hasOwnProperty(key));

    if (!subDataKey) {
      console.error('Language not found for key: ' + key);
      return '';
    }

    return subDataKey[key]
  }

  public translateMonth(monthNumber: number){
    const months = this.translate('month');
    if(Array.isArray(months)){
        return months[monthNumber];
    }
  }

  public translateLanguage(key: string){
    const found = this.languagesList.find(element => element.key === key);
    
    if(found !== undefined){
        return found.name;
    }

    return '';
    
  }

  public translate(key: string, variables?: { [key: string]: string }): string {
    let translatedText = this.translations[key] && this.translations[key] ? this.translations[key] : key;
    if (variables) {
      for (const variable in variables) {
        if (variables.hasOwnProperty(variable)) {
          translatedText = translatedText.replace(`{{${variable}}}`, variables[variable]);
        }
      }
    }
    return translatedText;
  }
}