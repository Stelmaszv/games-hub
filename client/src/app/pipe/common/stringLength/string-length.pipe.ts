import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'stringLength'
})
export class StringLengthPipe implements PipeTransform {

  private shortString(string:string,limit:number): string{
    let str=''
    for (let i = 0; i < string.length; i++) {
      if (i<limit){
        str=str+string[i]
      }
    }
    str=str+'...'
    return str

  }

  public transform(string: string | null | undefined,limit:number): string {
    if(string === null || string === undefined){
      return ''
    }

    if (string.length>limit){
      return this.shortString(string,limit)
    }
    return string;
  }

}
