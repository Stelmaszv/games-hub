import { Component} from '@angular/core';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent{
  constructor(public translationService: TranslationService) {}
}
