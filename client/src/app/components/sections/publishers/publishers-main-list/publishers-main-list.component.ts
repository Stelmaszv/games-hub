import { Component } from '@angular/core';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-publishers-main-list',
  templateUrl: './publishers-main-list.component.html',
  styleUrls: ['./publishers-main-list.component.scss']
})
export class PublishersMainListComponent {
  public canListPublishers : boolean | undefined;
  public publishers: any[] = [];

  public constructor(private HttpServiceService : HttpServiceService,private isGrantedService : IsGrantedService) { }

  public ngOnInit(): void {
    this.getList()
    this.setList()
  }

  private getList(){
    this.isGrantedService.setPermisionForList('CAN_LIST_PUBLISHERS').subscribe((list: any) => {
      this.canListPublishers = list
    });
  }

  private setList(){
    this.HttpServiceService.getData('http://localhost/api/publisher/list')
    .subscribe((data: any) => {
      data.results.forEach((element: any) => {

        this.isGrantedService.setPermision('CAN_SHOW_PUBLISHER', element, 'canShowPublisher', 'Publisher')
        this.isGrantedService.setPermision('CAN_EDIT_PUBLISHER', element, 'canEditPublisher', 'Publisher' )
        this.isGrantedService.setPermision('CAN_DELETE_PUBLISHER', element, 'canDeletePublisher', 'Publisher' )

        this.publishers.push(element);
      });
    });
  }
}
