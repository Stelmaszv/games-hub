import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Language } from 'src/app/components/interface';
import { firstValueFrom } from 'rxjs';
import { TranslationService } from '../translation/translation.service';

@Injectable({
  providedIn: 'root'
})
export class LanguageService {

  constructor(private translationService: TranslationService) { }

  async returnFields(){
    let languages = await this.translationService.setLanguagesList()
    console.log(languages)
  }
}
