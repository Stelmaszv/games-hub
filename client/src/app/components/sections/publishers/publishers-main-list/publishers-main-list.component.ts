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
  publishers: any[] = [];

  constructor(private HttpServiceService : HttpServiceService,private isGrantedService : IsGrantedService) { }

  ngOnInit(): void {
    this.HttpServiceService.getData('http://localhost/api/publisher/list')
    .subscribe((data: any) => {
      data.results.forEach((element: any) => {

        this.isGrantedService.setData('CAN_SHOW_PUBLISHER', { "entity": "Publisher","id":element.id });
    
        this.isGrantedService.responseData.subscribe((el) => {
          element.canShowPublisher = el
        });

        this.isGrantedService.setData('CAN_EDIT_PUBLISHER', { "entity": "Publisher","id":element.id });
    
        this.isGrantedService.responseData.subscribe((el) => {
          element.canEditPublisher = el
        });

        this.isGrantedService.setData('CAN_DELETE_PUBLISHER', { "entity": "Publisher","id":element.id });
    
        this.isGrantedService.responseData.subscribe((el) => {
          element.canDeletePublisher = el.success
        });
   
        this.publishers.push(element);
      });
    });

    this.isGrantedService.setData('CAN_LIST_PUBLISHERS');
    
    this.isGrantedService.responseData.subscribe((el) => {
      this.canListPublishers = el
    });
  }
}
