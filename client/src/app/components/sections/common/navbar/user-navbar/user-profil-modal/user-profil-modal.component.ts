import { Component, OnInit  } from '@angular/core';
import { AuthService } from 'src/app/services/common/auth/auth.service';
import { TranslationService } from 'src/app/services/common/translation/translation.service';
import { Observable, of } from 'rxjs';
import { firstValueFrom } from 'rxjs';


@Component({
  selector: 'user-profil-modal',
  templateUrl: './user-profil-modal.component.html',
  styleUrls: ['./user-profil-modal.component.scss']
})
export class UserProfilModalComponent implements OnInit {

  public title: string|null = null

  constructor(public translationService: TranslationService, public authService: AuthService) {}

  async ngOnInit() {
    setTimeout(() => {
      this.title = this.translationService.translate('profile');
    }, 1000);
  }

  public setTittle(tittle: string){
    this.title = this.translationService.translate(tittle);
  }

  public logOut(){
    this.authService.removeToken();
    location.reload();
  }

}
