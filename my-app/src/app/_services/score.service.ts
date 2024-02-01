import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ScoreService {

  constructor(private http: HttpClient) { }

    getRandomGame() {
        return this.http.get<any>(environment.apiURL + '/get_game');
    }

    getScore() {
        return this.http.get(environment.apiURL + '/get_score');
    }

    saveScore(param) {
        return this.http.post(environment.apiURL + '/save_score', param);
    }

    getPDF(): Observable<Blob> {
        return this.http.get(environment.apiURL + '/exporttopdf', { responseType: 'blob' });
    }

    getExcel(): Observable<Blob> {
        return this.http.get(environment.apiURL + '/exporttoexcel', { responseType: 'blob' });
    }
}
