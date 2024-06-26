import { Component,Input,Output,EventEmitter } from '@angular/core';
import { Publisher } from '../../interfaces';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'edit-publisher-descriptions',
  templateUrl: './edit-publisher-descriptions.component.html',
  styleUrls: ['./edit-publisher-descriptions.component.scss']
})
export class EditPublisherDescriptionsComponent {

  @Input() publisher!: Publisher|null;
  @Output() publisherChange = new EventEmitter<Publisher>();

  constructor(public translationService: TranslationService){}

  public onSubmit(){

  }
}
