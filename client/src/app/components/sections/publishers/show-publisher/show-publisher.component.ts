import { Component, OnInit  } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpServiceService } from 'src/app/services/common/http-service/http-service.service';
import { Publisher } from '../interfaces';
import { IsGrantedService } from 'src/app/services/common/is-granted/is-granted.service';

@Component({
  selector: 'app-show-publisher',
  templateUrl: './show-publisher.component.html',
  styleUrls: ['./show-publisher.component.scss']
})
export class ShowPublisherComponent implements OnInit {
  
  public publisher: Publisher | null = null;
  
  private permissionAttributes: { [key: string]: boolean | undefined } = {};

  public constructor(private route: ActivatedRoute, private HttpServiceService : HttpServiceService, private isGrantedService : IsGrantedService) { }

  public ngOnInit(): void {
    this.setPublisher()
  }

  public showPublisherGeneralInformation(){
    return (this.publisher?.id && Object.keys(this.permissionAttributes).length)
  }

  private async checkPermission(attribute:string , id:number){
    this.permissionAttributes[attribute] = await this.isGrantedService.checkIfGuardCanActivate(attribute,'publisher', id);
  }

  private async setPublisher() : Promise<void>
  {
    await this.getPublisher()
  }

  public checkIfGranted(attribute:string){
    return this.permissionAttributes[attribute];
  }

  private async getPublisher(){
    this.route.params.subscribe(params => {
      this.HttpServiceService.getData('http://localhost/api/publisher/show/'+params['id']).subscribe((publisher: Publisher ) => {
        this.publisher = publisher
        this.checkPermission('CAN_SHOW_PUBLISHER', publisher.id)
        this.checkPermission('CAN_EDIT_PUBLISHER', publisher.id)
      });
    });
  }
}