import { Component,OnInit } from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.scss']
})
export class FooterComponent implements OnInit {
  constructor(public translationService: TranslationService){}
  lang: string | null = null;

  languages: Array<any> = [];

  ngOnInit(): void {
    this.lang = this.translationService.getLang();
    this.languages = this.translationService.getLanguagesList()
  }

  isChosen(lang: string){
    return (this.lang === lang);
  }

  langChosen(lang: string) {
    this.lang = lang
  }

  langSaved(){
    this.translationService.setLang(this.lang)
    location.reload()
  }

}
