import { Component,OnInit } from '@angular/core';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'add-publishers',
  templateUrl: './add-publishers.component.html',
  styleUrls: ['./add-publishers.component.scss']
})
export class AddPublishersComponent implements OnInit {
  public canAddPublisher : boolean | undefined;
  
  constructor(public translationService: TranslationService,private isGrantedService : IsGrantedService){}

  async ngOnInit(): Promise<void> {
    const isGranted = await this.isGrantedService.checkIfGuardCanActivate('CAN_ADD_PUBLISHER');
    this.canAddPublisher = isGranted
  }
}