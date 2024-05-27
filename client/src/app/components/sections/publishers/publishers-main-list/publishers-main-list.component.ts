import { Component } from '@angular/core';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-publishers-main-list',
  templateUrl: './publishers-main-list.component.html',
  styleUrls: ['./publishers-main-list.component.scss']
})
export class PublishersMainListComponent {
  canListPublishers : boolean | undefined;
  canShowPublisher : boolean | undefined;
  canEditPublisher : boolean | undefined;
  canDeletePublisher : boolean | undefined;

  constructor(private isGrantedService : IsGrantedService) { }

  ngOnInit(): void {
    this.isGrantedService.setData('CAN_LIST_PUBLISHERS');
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canListPublishers = el
    });

    this.isGrantedService.setData('CAN_SHOW_PUBLISHER', { "entity": "Publisher","id":65 });
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canShowPublisher = el
    });

    this.isGrantedService.setData('CAN_EDIT_PUBLISHER', { "entity": "Publisher","id":65 });
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canEditPublisher = el
    });

    this.isGrantedService.setData('CAN_DELETE_PUBLISHER', { "entity": "Publisher", "id":65 });
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canDeletePublisher = el
    });

  }
}
