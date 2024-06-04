import { Component,OnInit } from '@angular/core';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'add-publishers-button',
  templateUrl: './add-publishers-button.component.html',
  styleUrls: ['./add-publishers-button.component.scss']
})
export class AddPublishersButtonComponent implements OnInit {
  public canAddPublisher : boolean | undefined;
  
  constructor(public translationService: TranslationService,private isGrantedService : IsGrantedService){}

  async ngOnInit(): Promise<void> {
    const isGranted = await this.isGrantedService.checkIfGuardCanActivate('CAN_ADD_PUBLISHER');
    this.canAddPublisher = isGranted
  }
}