import { Component } from '@angular/core';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-publishers-main-list',
  templateUrl: './publishers-main-list.component.html',
  styleUrls: ['./publishers-main-list.component.scss']
})
export class PublishersMainListComponent {
  canListPublishers : boolean| undefined;
  canAddPublishers : boolean| undefined;
  constructor(private httpServiceService: HttpServiceService,private isGrantedService : IsGrantedService) { }

  ngOnInit(): void {
    this.isGrantedService.setData('CAN_LIST_PUBLISHERS');
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canListPublishers = el
    });

    this.isGrantedService.setData('CAN_ADD_PUBLISHER', { "entity": "", "id":  65});
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canAddPublishers = el
    });

  }
}
