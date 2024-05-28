export interface PaginatorData {
    totalCount: number,
    endPage: number,
    startPage: number,
    current: number,
    pageCount: number,
    previous: boolean | null,
    next: boolean | null
}

export interface IsGranted{
  message : String,
  success : boolean
}