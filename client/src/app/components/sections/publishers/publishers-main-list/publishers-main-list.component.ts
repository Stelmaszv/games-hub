import { Component } from '@angular/core';
import { IsGranted } from 'src/app/interface/common';
import { PublisherListElement } from 'src/app/interface/publisher';
import { RespanseList } from 'src/app/interface/respanse';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-publishers-main-list',
  templateUrl: './publishers-main-list.component.html',
  styleUrls: ['./publishers-main-list.component.scss']
})
export class PublishersMainListComponent {
  public canListPublishers : boolean | undefined;
  public publishers: PublisherListElement[] = [];

  public constructor(private HttpServiceService : HttpServiceService, private isGrantedService : IsGrantedService) { }

  public ngOnInit() : void 
  {
    this.getList()
    this.setList()
  }

  private getList() : void
  {
    this.isGrantedService.getPermisionForList('CAN_LIST_PUBLISHERS').subscribe((isGrantedList: IsGranted) => {
      this.canListPublishers = isGrantedList?.success
    });
  }

  private setList() : void
  {
    this.HttpServiceService.getData('http://localhost/api/publisher/list')
    .subscribe((data: RespanseList ) => {
      data.results.forEach((element: PublisherListElement) => {

        this.isGrantedService.setPermision('CAN_SHOW_PUBLISHER', element, 'canShowPublisher', 'Publisher')
        this.isGrantedService.setPermision('CAN_EDIT_PUBLISHER', element, 'canEditPublisher', 'Publisher' )
        this.isGrantedService.setPermision('CAN_DELETE_PUBLISHER', element, 'canDeletePublisher', 'Publisher' )

        this.publishers.push(element);
      });
    });
  }
}
