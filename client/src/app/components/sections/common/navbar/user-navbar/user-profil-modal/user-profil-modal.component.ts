import { Component, OnInit  } from '@angular/core';
import { AuthService } from 'src/app/services/auth/auth.service';
import { TranslationService } from 'src/app/services/translation/translation.service';

@Component({
  selector: 'user-profil-modal',
  templateUrl: './user-profil-modal.component.html',
  styleUrls: ['./user-profil-modal.component.scss']
})
export class UserProfilModalComponent implements OnInit {

  public title: string | null = null

  constructor(public translationService: TranslationService, public authService: AuthService) {}

  ngOnInit(): void {
    this.title = this.translationService.translate('profil');
  }

  setTittle(tittle: string){
    this.title = this.translationService.translate(tittle);
  }

  logOut(){
    this.authService.removeToken();
  }

}
