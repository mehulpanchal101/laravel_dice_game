import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { first } from 'rxjs/operators';
import { timer } from 'rxjs';
import { User } from '../_models/user';
import { UserService } from '../_services/user.service';
import { ScoreService } from '../_services/score.service';
import { AuthenticationService } from '../_services/authentication.service';
import { Role } from '../_models/role';


@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  currentUser: User;
  userFromApi: User;
  gameName: string;
  gameId: string;
  round: number = 0;
  score: number = 0;
  played: number = 0;
  hide_play: boolean = false;
  gameloading: boolean = false;
  isProsess: boolean = false;
  timeTaken: number = 1;
  interval;
  subscribeTimer: any;

  constructor(
    private userService: UserService,
    private scoreService: ScoreService,
    private authenticationService: AuthenticationService,
    private el: ElementRef
  ) {
    this.currentUser = this.authenticationService.currentUserValue;
  }

  ngOnInit() {
    this.gameloading = true;
    this.scoreService.getRandomGame().pipe(first()).subscribe(res => {
      this.gameId = res.result._id;
      this.gameName = res.result.name;
      this.round = res.result.round_of_play;
      this.gameloading = false;
      this.startTimer();
    });
  }

  get isAdmin() {
    return this.currentUser && this.currentUser.role === Role.Admin;
  }

  hidePlay() {
    return this.played != this.round;
  }
  rollDice() {
    this.isProsess = true;
    if (this.played < this.round) {
      let random;
      random = this.getRandomNumber(1, 6);
      const dice = [this.el.nativeElement.querySelectorAll('.die-list')];
      dice.forEach(die => {
        this.toggleClasses(die);
        this.el.nativeElement.querySelector('.die-list').dataset.roll = random;
      });
      setTimeout(() => {
        this.played++;
        this.isProsess = false;
        this.score = this.score + random;
        if (this.played == this.round) {
          this.scoreService.saveScore({
            "game_id": this.gameId,
            "user_id": this.currentUser.id,
            "score": this.score,
            "time_taken": this.transform(this.timeTaken)
          }).pipe(first()).subscribe(res => {
            console.log(res)
          });
        }
      }, this.played < (this.round - 1) ? 5000 : 5000);
    } else {
      this.isProsess = true;
    }

  }

  toggleClasses(die) {
    // this.el.nativeElement.toggle("odd-roll");
    // this.el.nativeElement.toggle("even-roll");
    this.el.nativeElement.querySelector('.die-list').classList.toggle('odd-roll');
    this.el.nativeElement.querySelector('.die-list').classList.toggle('even-roll');
    // die.classList.toggle("odd-roll");
    // die.classList.toggle("even-roll");
  }

  getRandomNumber(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  oberserableTimer() {
    const source = timer(1000, 2000);
    const abc = source.subscribe(val => {
      this.subscribeTimer = this.timeTaken - val;
    });
  }

  startTimer() {
    this.interval = setInterval(() => {
      if (this.timeTaken > 0) {
        this.timeTaken++;
      } else {
        this.timeTaken = 1;
      }
    }, 1000);
  }

  pauseTimer() {
    clearInterval(this.interval);
  }

  transform(value: number): string {
    const minutes: number = Math.floor(value / 60);
    return minutes.toString().padStart(2, '0') + ':' + (value - minutes * 60).toString().padStart(2, '0');
  }

}
