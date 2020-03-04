import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { ResponseData } from '../shared/responseData';
import { BehaviorSubject, Observable, throwError} from 'rxjs';
import { retry, catchError } from 'rxjs/operators';
import { RequestParams } from '../shared/requestParams';

const API_URL = environment.apiUrl;

@Injectable({
  providedIn: 'root'
})
export class DataServiceService {

  constructor(private http: HttpClient) {
    this.remoteData$ = new BehaviorSubject([]);
  }

  remoteData$: BehaviorSubject<any>;

  getData(params?: RequestParams): Observable<ResponseData> {
      const responseData = this.sendRequest(params);
      responseData.subscribe(dataContent => {
        console.log(dataContent);
        this.remoteData$.next(dataContent);
      });
      return responseData;
  }

  errorHandler(error) {
    let errorMessage = '';
    if (error.error instanceof ErrorEvent) {
      errorMessage = error.error.message;
    } else {
      errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
    }
    console.log(errorMessage);
    return throwError(errorMessage);
  }

  private sendRequest(params?: RequestParams): Observable<ResponseData> {

    let requestParams = new HttpParams();
    if (params) {
      requestParams = requestParams.append('Hdd', params.Hdd);
      requestParams = requestParams.append('Ram', params.Ram);
      requestParams = requestParams.append('Location', params.Location);
      requestParams = requestParams.append('Storage', params.Storage);
    }

    // @ts-ignore
    return this.http.get<ResponseData>(API_URL, {params: requestParams})
      .pipe(
        retry(1),
        catchError(this.errorHandler)
      );
  }
}
