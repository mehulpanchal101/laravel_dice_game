import { Component, OnInit } from '@angular/core';
import { ScoreService } from '../_services/score.service';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit {
    data: any = [];
    constructor(private scoreService: ScoreService) { }

    ngOnInit() {
        this.scoreService.getScore().subscribe(res => {
            this.data = res;
        });
    }

    getPDFFile() {
        this.scoreService.getPDF()
            .subscribe(x => {
                // It is necessary to create a new blob object with mime-type explicitly set
                // otherwise only Chrome works like it should
                var newBlob = new Blob([x], { type: "application/pdf" });
                // IE doesn't allow using a blob object directly as link href
                // instead it is necessary to use msSaveOrOpenBlob
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    window.navigator.msSaveOrOpenBlob(newBlob, "score_list.pdf");
                    return;
                }

                // For other browsers: 
                // Create a link pointing to the ObjectURL containing the blob.
                const data = window.URL.createObjectURL(newBlob);

                var link = document.createElement('a');
                link.href = data;
                link.download = "score_list.pdf";
                // this is necessary as link.click() does not work on the latest firefox
                link.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true, view: window }));

                setTimeout(function () {
                    // For Firefox it is necessary to delay revoking the ObjectURL
                    window.URL.revokeObjectURL(data);
                    link.remove();
                }, 100);
            });
    }    
    getExcelFile() {
        this.scoreService.getExcel()
            .subscribe(x => {
                // It is necessary to create a new blob object with mime-type explicitly set
                // otherwise only Chrome works like it should
                var newBlob = new Blob([x], { type: "application/xlsx" });
                // IE doesn't allow using a blob object directly as link href
                // instead it is necessary to use msSaveOrOpenBlob
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    window.navigator.msSaveOrOpenBlob(newBlob, "score_list.xlsx");
                    return;
                }

                // For other browsers: 
                // Create a link pointing to the ObjectURL containing the blob.
                const data = window.URL.createObjectURL(newBlob);

                var link = document.createElement('a');
                link.href = data;
                link.download = "score_list.xlsx";
                // this is necessary as link.click() does not work on the latest firefox
                link.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true, view: window }));

                setTimeout(function () {
                    // For Firefox it is necessary to delay revoking the ObjectURL
                    window.URL.revokeObjectURL(data);
                    link.remove();
                }, 100);
            });
    }
}