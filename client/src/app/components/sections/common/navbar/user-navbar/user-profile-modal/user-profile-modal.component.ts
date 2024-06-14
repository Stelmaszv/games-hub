import { Component, OnInit  } from '@angular/core';
import { AuthService } from 'src/app/services/common/auth/auth.service';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'user-profile-modal',
  templateUrl: './user-profile-modal.component.html',
  styleUrls: ['./user-profile-modal.component.scss']
})
export class UserProfileModalComponent implements OnInit {

  public title: string|null = null

  constructor(public translationService: TranslationService, public authService: AuthService) {}

  ngOnInit() {
    setTimeout(() => {
      this.title = this.translationService.translate('profile');
    }, 1);
  }

  public setTittle(tittle: string){
    this.title = this.translationService.translate(tittle);
  }

  public logOut(){
    this.authService.removeToken();
    location.reload();
  }

}
