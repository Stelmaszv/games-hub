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

  ngOnInit(): void {
    this.isGrantedService.setData('CAN_ADD_PUBLISHER', { "entity": "Publisher" });
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canAddPublisher = el
    });
  }
}
