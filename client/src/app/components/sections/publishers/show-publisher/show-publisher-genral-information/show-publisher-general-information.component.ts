import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { Publisher } from '../../interfaces';
import { TranslationService } from 'src/app/services/common/translation/translation.service';

@Component({
  selector: 'show-publisher-general-information',
  templateUrl: './show-publisher-general-information.component.html',
  styleUrls: ['./show-publisher-general-information.component.scss']
})
export class ShowPublisherGeneralInformationComponent implements OnInit {
  @Input() publisher!: Publisher|null;

  constructor(public translationService: TranslationService){

  }

  public ngOnInit(): void {
    console.log(this.publisher)
  }
}
