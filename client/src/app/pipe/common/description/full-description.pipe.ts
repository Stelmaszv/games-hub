import { Pipe, PipeTransform } from '@angular/core';
import { StringLengthPipe } from '../stringLength/string-length.pipe';

@Pipe({
  name: 'desc'
})
export class FullDescriptionPipe implements PipeTransform {

  public transform(string:string | null | undefined,limit:number): string {
    return new StringLengthPipe().transform(string,limit)
  }
}
